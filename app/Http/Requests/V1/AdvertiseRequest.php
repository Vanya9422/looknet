<?php

namespace App\Http\Requests\V1;

use App\Enums\Admin\Filters\FilterTypesEnum;
use App\Enums\Advertise\AdvertiseStatus;
use App\Repositories\V1\Admin\Category\FilterRepository;
use App\Traits\StripTagsAble;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class AdvertiseRequest
 * @package App\Http\Requests\V1
 */
class AdvertiseRequest extends FormRequest {

    use StripTagsAble;

    private array $striping_columns = ['formatted_filters', 'fake_data'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws ValidationException
     */
    public function authorize(): bool {
        // Пример использования функции в контроллере
        $answers = $this->get('answers');

        if ($answers) $this->validateAnswers($answers);

        $this->filterStatus();

        $this->generateFakeData();

        return Auth::check();
    }

    /**
     *|---------------------------------------------------------------------------
     *| Validation Rules for Validatable trait
     *|---------------------------------------------------------------------------
     *| @var array[] $rules
     */
    protected array $rules = [
        'POST' => [
            'pictures' => 'nullable|array|max:10',
            'pictures.*.file' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'file' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'fake_data' => 'nullable|array',
            'pictures.*.media_id' => 'nullable|exists:media,id',
            'pictures.*.order' => 'required|numeric',
            'name' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:5000|min:20',
            'formatted_filters' => 'nullable|array',
            'refusal_comment' => 'nullable|string|max:1000|min:20',
            'price' => 'required|numeric',
            'is_fake' => 'nullable|boolean',
            'hide_address' => 'nullable|boolean',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'contacts' => 'nullable|numeric|between:0,2',
            'contact_phone_numeric' => 'required_unless:contacts,!=,2|numeric',
            'contact_phone' => 'required_unless:contacts,!=,2|string',
            'address' => 'required|string|max:255',
            'link' => 'nullable|string',
            'price_policy' => 'required|numeric|between:0,3',
            'auto_renewal' => 'nullable|boolean',
            'available_cost' => 'nullable|boolean',
            'show_details' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'answers' => 'nullable|array'
        ],
        'PUT' => [
            'pictures' => 'nullable|array|max:10',
            'pictures.*.file' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'file' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'fake_data' => 'nullable|array',
            'pictures.*.media_id' => 'nullable|exists:media,id',
            'pictures.*.order' => 'required|numeric',
            'name' => 'nullable|string|max:255|min:2',
            'description' => 'nullable|string|max:5000|min:20',
            'price' => 'nullable|numeric',
            'latitude' => 'nullable|string',
            'is_fake' => 'nullable|boolean',
            'formatted_filters' => 'nullable|array',
            'longitude' => 'nullable|string',
            'refusal_comment' => 'nullable|string|max:1000|min:20',
            'address' => 'nullable|string|max:255',
            'link' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'auto_renewal' => 'nullable|boolean',
            'contact_phone_numeric' => 'nullable|numeric',
            'available_cost' => 'nullable|boolean',
            'show_details' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'price_policy' => 'nullable|numeric|between:0,3',
            'contacts' => 'nullable|numeric|between:0,2',
            'answers' => 'nullable|array'
        ],
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
       return $this->rules[$this->getMethod()];
    }


    /**
     * Эта функция служит для фильтрации параметра 'status' в запросе. Если параметр 'status'
     * не соответствует значениям статусов Draft или NotVerified, он будет удален из запроса.
     *
     */
    public function filterStatus()
    {
        if ($this->has('status')) {
            $status = $this->get('status');
            $draft = AdvertiseStatus::fromValue(AdvertiseStatus::Draft);
            $notVerified = AdvertiseStatus::fromValue(AdvertiseStatus::NotVerified);

            if (!$draft->is(+$status) && !$notVerified->is(+$status)) {
                $this->request->remove('status');
            }
        }
    }

    /**
     * Генерирует фейковые данные и обновляет параметр 'fake_data' запроса.
     * Добавляет случайно сгенерированную дату в 'created_at'.
     */
    public function generateFakeData()
    {
        if ($this->has('fake_data')) {
            $startDate = Carbon::create(2023, 1, 23); // Начальная дата
            $endDate = Carbon::now(); // Текущая дата
            $randomTimestamp = mt_rand($startDate->timestamp, $endDate->timestamp);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp)->toDateTimeString();
            $data = array_merge($this->get('fake_data'), ['created_at' => $randomDate]);

            $this->request->set('fake_data', $data);
        }
    }

    /**
     * Валидирует массив ответов фильтров.
     *
     * @param array $answers Массив ответов на фильтры.
     * @return void
     * @throws ValidationException Если ответы не соответствуют заданным критериям валидации.
     */
    function validateAnswers(array $answers): void {
        $filterRepo = app(FilterRepository::class);
        $withValidate = AdvertiseStatus::fromValue(FilterTypesEnum::WITH_VALIDATIONS_VALUES);

        foreach ($answers as $answer) {
            if (isset($answer['filter_id'])) {
                $filter = $filterRepo->find($answer['filter_id']);

                // Проверяем, является ли фильтр типом, требующим валидации
                if ($withValidate->is($filter->type)) {
                    $numberValue = $answer['number_value'];

                    // Проверяем, существуют ли строковое значение или ID, и валидируем их
                    if (isset($answer['string_value']) || isset($answer['id'])) {
                        $validator = Validator::make([
                            'string_value' => $answer['string_value'] ?? null,
                            'id' => $answer['id'] ?? null
                        ], [
                            'string_value' => 'nullable|string', // Правило валидации: строка, может быть пустой
                            'id' => 'nullable|exists:filter_answers', // Правило валидации: существующий ID в базе данных
                        ]);

                        // Если валидация не прошла, выбросить исключение с ошибками валидации
                        if ($validator->fails()) {
                            throw new ValidationException($validator);
                        }
                    }

                    // Проверяем, соответствует ли number_value критериям фильтра
                    $min_value = $filter->min_value ?? 0;

                    if ($numberValue < $min_value) {
                        // Значение меньше минимального критерия - создаем ошибку валидации
                        throw ValidationException::withMessages([
                            'answers.*.number_value.min_value' => trans('validation.min.numeric', [
                                'attribute' => __('number_value'), // Замените на реальное имя атрибута
                                'max' => $filter->min_value // Замените на реальное максимальное значение
                            ]),
                        ]);
                    }

                    if ($numberValue > $filter->max_value) {
                        // Значение больше максимального критерия - создаем ошибку валидации
                        throw ValidationException::withMessages([
                            'answers.*.number_value.max_value' => trans('validation.max.numeric', [
                                'attribute' => __('number_value'), // Замените на реальное имя атрибута
                                'max' => $filter->max_value // Замените на реальное максимальное значение
                            ]),
                        ]);
                    }
                }
            }
        }
    }
}
