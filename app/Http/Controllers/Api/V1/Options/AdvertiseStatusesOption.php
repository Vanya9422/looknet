<?php

namespace App\Http\Controllers\Api\V1\Options;

use App\Enums\Advertise\AdvertiseStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Контроллер для обработки запросов, связанных с сстатусом публикации
 */
class AdvertiseStatusesOption extends Controller {

    /**
     * Обрабатывает входящий запрос и возвращает информацию о статусах публикации.
     *
     * @param Request $request Входящий запрос.
     * @return array Ответ с информацией о доступных типах отказов.
     */
    public function __invoke(Request $request): array {
        return [
            'message' => 'Доступные Статуси Публикации',
            'types' => AdvertiseStatus::getValues(),
            'instances' => AdvertiseStatus::getInstances(),
        ];
    }
}
