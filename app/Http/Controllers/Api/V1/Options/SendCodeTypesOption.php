<?php

namespace App\Http\Controllers\Api\V1\Options;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Контроллер для получения типов кодов отправки.
 *
 * Этот контроллер обрабатывает запросы к API для предоставления информации
 * о различных типах кодов подтверждения, используемых в логике отправки кодов.
 */
class SendCodeTypesOption extends Controller {

    /**
     * Обработка входящего запроса.
     *
     * Этот метод вызывается при обращении к маршруту, связанному с этим контроллером.
     * Он возвращает клиенту список доступных типов кодов подтверждения.
     *
     * @param Request $request Входящий HTTP-запрос.
     * @return array
     */
    public function __invoke(Request $request): array {
        return [
            'message' => 'Доступные типы кодов подтверждения из логики отправки кодов.',
            'types' => SendCodeContentTextEnum::getValues(),
            'instances' => SendCodeContentTextEnum::getInstances(),
        ];
    }
}
