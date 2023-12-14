<?php

namespace App\Http\Requests\V1\Chat;

use App\Http\Requests\V1\FormRequest;
use App\Traits\StripTagsAble;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class StoreMessage
 * @package App\Http\Requests\V1\Chat
 */
class StoreMessage extends FormRequest
{
    use StripTagsAble;

    private array $striping_columns = ['message'];

    /**
     * @return string[]
     */
    public function rules(): array {
        return [
            'message' => 'required_without:files|string|max:300|min:1',
            'files' => 'required_without:message|array|max:5',
            'files.*' => 'required_without:message|mimetypes:image/jpeg,image/jpg,image/heic,image/png,image/svg+xml,image/gif,image/webp,application/xml,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/json,audio/mpeg,text/plain,text/rtf,application/pdf,application/msword,application/vnd.ms-excel,application/vnd.ms-powerpoint,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.oasis.opendocument.presentation|max:10240', // Максимальный размер в килобайтах (5 МБ = 5120 КБ)
        ];
    }
}
