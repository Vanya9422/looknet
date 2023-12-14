<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Contracts\UserSettingsCodeConfirmationHandlerInterface;
use App\Enums\Advertise\AdvertiseStatus;
use App\Enums\MediaCollections;
use App\Enums\Users\UserSettingsChangeEmailOrPhoneTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\ChangePasswordRequest;
use App\Http\Requests\V1\Users\UpdateAuthTypesRequest;
use App\Http\Requests\V1\Users\UpdateProfileRequest;
use App\Http\Resources\V1\AdvertiseResource;
use App\Http\Resources\V1\User\PublicUserResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\Media;
use App\Models\User;
use App\Repositories\V1\AdvertiseRepository;
use App\Repositories\V1\Users\UserRepository;
use App\Services\V1\Users\UserService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1\Users
 */
class UserController extends Controller {

    use ApiResponseAble;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     * @param UserService $userService
     */
    public function __construct(private UserRepository $userRepository, private UserService $userService) { }

    /**
     * @param UpdateProfileRequest $request
     * @throws \Throwable
     * @return JsonResponse|UserResource
     */
    public function update(UpdateProfileRequest $request): JsonResponse|UserResource  {
        try {
            $this->authorize(__FUNCTION__, $request->user());

            $user = $this->userRepository->updateUserProfile($request->user(), $request->all());

            return new UserResource($user);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * @param ChangePasswordRequest $request
     * @throws \Throwable
     * @return JsonResponse|UserResource
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse|UserResource  {
        try {
            $this->authorize('checkCode', [$request->user(), $request->get('code')]);

            $user = $this->userRepository->updateUserProfile(
                $request->user(),
                $request->all(),
                MediaCollections::USER_AVATAR_COLLECTION,
                true,
            );

            return new UserResource($user);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Метод для отправки кода подтверждения на Email или телефон пользователя.
     *
     * Этот метод обрабатывает запрос на отправку кода подтверждения для изменения email или телефона пользователя.
     * Сначала он проверяет, соответствует ли текущее значение email/телефона отправленному в запросе (на первом этапе),
     * с помощью метода validateFieldChange.
     * Если в запросе присутствует 'code', это означает второй этап процесса, где пользователь должен быть авторизован
     * для действия 'checkCode' с кодом, полученным из запроса.
     * Затем запрос обрабатывается с использованием переданного handler'а (UserSettingsCodeConfirmationHandlerInterface),
     * который отвечает за логику отправки кода подтверждения.
     *
     * @param UpdateAuthTypesRequest $request Экземпляр запроса, содержащий данные пользователя и изменяемое поле.
     * @param UserSettingsCodeConfirmationHandlerInterface $userConfirmationHandler Обработчик для отправки кода подтверждения.
     * @return JsonResponse Ответ в формате JSON, указывающий на успешность или неудачу операции.
     */
    public function sendCodForEmailOrPhone(
        UpdateAuthTypesRequest $request,
        UserSettingsCodeConfirmationHandlerInterface $userConfirmationHandler,
    ): JsonResponse {

        try {
            // Проверка соответствия текущих данных пользователя отправленным в запросе
            $this->validateFieldChange($request);

            // Проверка наличия 'code' в запросе для авторизации действия
            if ($request->has('code')) {
                // Авторизация действия 'checkCode' для текущего пользователя
                $this->authorize('checkCode', [$request->user(), $request->get('code')]);
            }

            // Обработка запроса на отправку кода
            $userConfirmationHandler->handle($request->user(), $request->all());

            return $this->success('', __('messages.SUCCESS_OPERATED'));
        } catch (\Throwable| \Exception $e) {
            // Обработка исключений и отправка соответствующего ответа клиенту
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatisticUser(Request $request): JsonResponse {
        $statistics = $this->userRepository->getStatistic(
            $request->user()->id,
            $request->query('status')
        );

        return $this->success([
            'statistics' => $statistics
        ], __('messages.SUCCESS_OPERATED'));
    }

    /**
     * Проверяет запрос на изменение email или телефона пользователя на первом этапе процесса.
     *
     * Эта функция активируется на первом этапе процесса изменения email или телефона пользователя.
     * На этом этапе пользователь должен отправить свой текущий (старый) email или номер телефона.
     * Отсутствие 'code' в запросе указывает на то, что это первый этап процесса.
     * Функция проверяет, соответствует ли отправленное значение текущему значению в профиле пользователя.
     * Если значения не совпадают, функция выбрасывает исключение, указывая на несоответствие данных.
     *
     * @param Request $request Экземпляр запроса, содержащий данные пользователя и изменяемое поле.
     * @throws \Exception Исключение выбрасывается, если отправленное значение не совпадает с текущим значением пользователя.
     */
    private function validateFieldChange(Request $request) {
        // Получение типа события из запроса
        $event = $request->get('event');

        // Проверка, является ли запрос первым этапом процесса изменения (отсутствие 'code')
        if (!$request->has('code')) {
            // Проверка, является ли событие запросом на изменение email или телефона
            if (
                $event === UserSettingsChangeEmailOrPhoneTypesEnum::isValidEmail ||
                $event === UserSettingsChangeEmailOrPhoneTypesEnum::isValidPhone
            ) {
                // Получение изменяемого поля из запроса
                $field = $request->get('field');

                // Сравнение отправленного значения поля с текущим значением пользователя
                if ($request->user()->{$field} !== $request->get($field)) {
                    // Если значения не совпадают, выбросить исключение
                    throw new \Exception("The provided {$field} does not match the current value.");
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param AdvertiseRepository $advertiseRepository
     * @return AnonymousResourceCollection
     */
    public function getAdvertises(Request $request, AdvertiseRepository $advertiseRepository): AnonymousResourceCollection {
        $collection = $advertiseRepository
            ->with(['previewImage' => fn ($query) => $query->select(Media::$selectedFields)])
            ->where('user_id', $request->user()->id)
            ->paginate($request->query('per_page'));

        return AdvertiseResource::collection($collection);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param AdvertiseRepository $advertiseRepository
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getUserInformation(
        Request $request,
        User $user,
        AdvertiseRepository $advertiseRepository
    ): AnonymousResourceCollection|JsonResponse {
        $result = AdvertiseResource::collection(
            $advertiseRepository
                ->where('is_fake', '=', false)
                ->where('user_id', $user->id)->paginate($request->query('per_page'))
        );

        $active = AdvertiseStatus::Active;
        $NotVerified = AdvertiseStatus::NotVerified;
        $inactive = AdvertiseStatus::InActive;
        $Rejected = AdvertiseStatus::Rejected;

        $user->load('avatar');
        $user->loadCount(['advertises', 'canceled_advertises']);

        return $result->additional([
            'user' => PublicUserResource::make($user),
            'counts' => \DB::table('advertises as adv')
                ->whereNull('fake_data')
                ->where('user_id', '=', $user->id)
                ->selectRaw("count(CASE WHEN adv.status = $active AND deleted_at IS NULL OR adv.status = $NotVerified AND deleted_at IS NULL THEN 1 END) as active_count")
                ->selectRaw("count(CASE WHEN adv.status = $inactive AND deleted_at IS NULL OR adv.status = $Rejected AND deleted_at IS NULL THEN 1 END) as inactive_count")
                ->first(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getFavoriteAdvertises(Request $request): JsonResponse {
        $user = $request->user()->load('advertise_favorites');
        $ids = $user->advertise_favorites->pluck('id')->toArray();
        return $this->success(['favorites' => $ids]);
    }

    /**
     * @param UpdateAuthTypesRequest $request
     * @throws \Throwable
     * @return JsonResponse|UserResource
     */
    public function changeEmailOrPhone(UpdateAuthTypesRequest $request): JsonResponse|UserResource  {
        try {
            $this->authorize('checkCode', [$request->user(), $request->get('code')]);

            $user = $this->userRepository->updateUserProfile(
                $request->user(),
                $request->all(),
                MediaCollections::USER_AVATAR_COLLECTION,
                true
            );

            return new UserResource($user);
        } catch (\Throwable| \Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
