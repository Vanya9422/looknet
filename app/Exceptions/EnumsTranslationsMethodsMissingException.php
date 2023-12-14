<?php

namespace App\Exceptions;

/**
 * Исключение выбрасывается, когда в классе отсутствуют методы,
 * необходимые для перевода перечисляемых типов (enums).
 *
 * Это исключение следует использовать в тех местах, где ожидается,
 * что класс будет иметь специфические методы для работы с локализацией
 * перечислений. Если эти методы отсутствуют, исключение сообщит об этом,
 * позволяя разработчику быстро выявить и устранить проблему.
 *
 * @package App\Exceptions
 */
class EnumsTranslationsMethodsMissingException extends \Exception
{
    /**
     * Конструктор исключения EnumsTranslationsMethodsMissingException.
     *
     * @param string $message Сообщение об ошибке. Если не предоставлено, используется сообщение по умолчанию.
     * @param int $code Код исключения.
     * @param \Exception|null $previous Предыдущее исключение для цепочки исключений.
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        // Если сообщение не предоставлено, используем стандартное.
        $message = $message ?: 'The required translation methods for enums are missing.';
        // Вызываем конструктор базового класса исключения.
        parent::__construct($message, $code, $previous);
    }
}
