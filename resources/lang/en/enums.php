<?php

use App\Enums\{
    Mails\SendCodeContentTextEnum,
    Reviews\ReviewStatusEnum
};

return [
    ReviewStatusEnum::class => [
        ReviewStatusEnum::Default => 'Did not communicate',
        ReviewStatusEnum::Success => 'The deal is done',
        ReviewStatusEnum::Fail => 'Deal fell through',
    ],
    SendCodeContentTextEnum::class => [
        SendCodeContentTextEnum::REGISTER_CONFIRMATION_CODE => 'It looks like you are trying to register a new account. To log in you will need the activation code that we sent in this letter',
        SendCodeContentTextEnum::RESET_PASS_CONFIRMATION_CODE => 'We have received a request to reset your account password. To continue the process and create a new password, please use the following confirmation code:',
        SendCodeContentTextEnum::CHANGE_EMAIL_OR_PHONE_SETTINGS_CONFIRMATION_CODE => 'It looks like you are trying to change your existing email address or add a new email address to your account. To complete this process, please use the following activation code:',
        SendCodeContentTextEnum::CHANGE_PASSWORD_CONFIRMATION_CODE => 'You have requested to change your password. Please use the following code to confirm this change:',
    ]
];
