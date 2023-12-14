<?php

namespace App\Services\V1\Users;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Models\User;
use App\Enums\Users\UserSettingsChangeEmailOrPhoneTypesEnum;
use App\Contracts\UserSettingsCodeConfirmationHandlerInterface;
use App\Notifications\V1\NotificationsTypesContract;
use App\Exceptions\Users\Settings\{
    EmailNotFoundException,
    PhoneNotFoundException,
    SendCodeEventNotFoundException
};


/**
 * Обработчик подтверждения пользователя.
 *
 * Ответственный за обработку запросов на изменение или подтверждение email/phone пользователя.
 */
class UserConfirmationHandler implements UserSettingsCodeConfirmationHandlerInterface {

    /** @var User Модель текущего пользователя */
    private User $user;

    /** @var array Данные из запроса */
    private array $data;

    /** @var ?string Почта пользователья */
    public ?string $email = null;

    /**
     * Обрабатывает запрос на подтверждение или изменение данных пользователя.
     *
     * @param User $user Модель пользователя.
     * @param array $data Данные из запроса.
     * @throws \Exception В случае отсутствия необходимых данных или ошибок валидации.
     */
    public function handle(User $user, array $data): void {
        $this->user = $user;

        $this->data = $data;

        $this->validateEventExists();

        $this->validateUserProperties();

        $this->sendConfirmationNotification();
    }

    /**
     * Метод для создания и получения экземпляра уведомления, соответствующего определенному контракту.
     * Контракт (интерфейс) NotificationsTypesContract определяет обязательные методы и свойства,
     * которые должен иметь класс уведомления, что гарантирует его соответствие ожидаемым стандартам функциональности.
     *
     * @return NotificationsTypesContract Объект класса, который реализует NotificationsTypesContract,
     *                                    предназначенный для отправки уведомлений.
     */
    protected function getConfirmationInstance(): NotificationsTypesContract {
        $notificationInstance = $this->data['confirmation_type'];

        // Возвращает новый экземпляр класса уведомления, передавая в его конструктор необходимый параметр.
        // Какой именно параметр необходим конструктору, определяется через вызов метода `getFieldFromData`,
        // который должен вернуть ключ нужного параметра из массива данных 'data'.
        return new $notificationInstance(
            $this->data['code_type'],
            $this->data[$this->getFieldFromData()],
            app()->getLocale()
        );
    }

    /**
     * Проверяет наличие события в данных запроса.
     *
     * @throws SendCodeEventNotFoundException Если событие отсутствует.
     */
    private function validateEventExists(): void {
        if (!isset($this->data['event'])) {
            throw SendCodeEventNotFoundException::eventNotProvided();
        }
    }

    /**
     * Проверяет, может ли пользователь изменить или добавить свой email/phone.
     * Проверка наличия email или телефона у пользователя перед изменением.
     *
     * @throws PhoneNotFoundException Если телефон отсутствует при попытке изменить email.
     * @throws EmailNotFoundException Если email отсутствует при попытке изменить телефон.
     */
    private function validateUserProperties(): void {
        if ($this->data['event'] === UserSettingsChangeEmailOrPhoneTypesEnum::isValidEmailCodeSendToPhone && !$this->user->phone) {
            throw PhoneNotFoundException::phoneNotSetForEmailChange();
        }

        if ($this->data['event'] === UserSettingsChangeEmailOrPhoneTypesEnum::isValidPhoneCodeSendToEmail && !$this->user->email) {
            throw EmailNotFoundException::emailNotSetForPhoneChange();
        }
    }

    /**
     * Отправляет уведомление о подтверждении пользователю.
     */
    private function sendConfirmationNotification(): void {
        $this->user->notify(
            $this->getConfirmationInstance()
        );
    }

    /**
     * Обновляет значение поля пользователя на основе данных запроса.
     */
    private function changeUserField(): void {
        $this->user->{$this->getFieldFromData()} = $this->data[$this->getFieldFromData()];
    }

    /**
     * Получение поля из данных.
     *
     * @return string Название поля.
     */
    private function getFieldFromData(): string {
        return $this->data['field'];
    }
}
