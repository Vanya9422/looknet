<?php

namespace App\Http\Controllers\Api\V1\Options;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Enums\Refusal\RefusalTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Контроллер для обработки запросов, связанных с типами отказов.
 */
class RefusalTypesOption extends Controller {

    /**
     * Обрабатывает входящий запрос и возвращает информацию о типах отказов.
     *
     * @param Request $request Входящий запрос.
     * @return array Ответ с информацией о доступных типах отказов.
     */
    public function __invoke(Request $request): array {
        return [
            'message' => 'Доступные типы жалоб в проекте.',
            'types' => RefusalTypeEnum::getValues(),
            'instances' => RefusalTypeEnum::getInstances(),
        ];
    }
}
