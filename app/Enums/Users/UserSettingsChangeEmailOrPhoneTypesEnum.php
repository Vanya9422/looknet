<?php declare(strict_types=1);

namespace App\Enums\Users;

/**
 * В настройке  пользователя есть страничка где меняется или добавляется почта или телефон.
 * Когда пользователь доходить да этого странички уже у него будет либо телефон либо  почта
 *
 * @method static static isValidEmail()
 * @method static static isValidPhone()
 * @method static static isValidEmailCodeSendToPhone()
 * @method static static isValidPhoneCodeSendToEmail()
 */
enum UserSettingsChangeEmailOrPhoneTypesEnum: string {

    /**
     * это тот случай когда код должен отправится на почту
     * не важно новая она или старая меняется или добавляется
     */
    const isValidEmail  = 'isValidEmail';

    /**
     * это тот случай когда код должен отправится на номер
     * не важно новая она или старая меняется или добавляется
     */
    const isValidPhone  = 'isValidPhone';

    /**
     * это тот случай когда код должен отправится на номер, которая имеется в аккаунте пользователя !
     *
     * Но по сути может меняется или добавится почта в аккаунте
     */
    const isValidEmailCodeSendToPhone  = 'isValidEmailCodeSendToPhone';

    /**
     * это тот случай когда код должен отправится на почту, которая имеется в аккаунте пользователя !
     *
     * Но по сути может меняется или добавится номер телефона
     */
    const isValidPhoneCodeSendToEmail  = 'isValidPhoneCodeSendToEmail';
}
