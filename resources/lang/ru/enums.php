<?php

use App\Enums\{Mails\SendCodeContentTextEnum, Reviews\ReviewStatusEnum};

return [
    ReviewStatusEnum::class => [
        ReviewStatusEnum::Default => 'Не общались',
        ReviewStatusEnum::Success => 'Сделка состоялась',
        ReviewStatusEnum::Fail => 'Сделка сорвалась',
    ],
    SendCodeContentTextEnum::class => [
        SendCodeContentTextEnum::REGISTER_CONFIRMATION_CODE => 'Похоже Вы пытаетесь зарегистрировать новый аккаунт. Для входа Вам понадобится код активации, который мы выслали в этом письме',
        SendCodeContentTextEnum::RESET_PASS_CONFIRMATION_CODE => 'Мы получили запрос на восстановление пароля для вашего аккаунта. Для продолжения процесса и создания нового пароля, пожалуйста, используйте следующий код подтверждения:',
        SendCodeContentTextEnum::CHANGE_EMAIL_OR_PHONE_SETTINGS_CONFIRMATION_CODE => 'Похоже, вы пытаетесь изменить существующий адрес электронной почты или добавить новый адрес электронной почты к вашему аккаунту. Для завершения этого процесса, пожалуйста, используйте следующий код активации:',
        SendCodeContentTextEnum::CHANGE_PASSWORD_CONFIRMATION_CODE => 'Вы запросили изменение пароля. Пожалуйста, используйте следующий код, чтобы подтвердить это изменение:',
    ]
];
