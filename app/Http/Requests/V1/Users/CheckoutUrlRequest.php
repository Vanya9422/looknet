<?php

namespace App\Http\Requests\V1\Users;

use App\Http\Requests\V1\FormRequest;
use App\Models\Products\Advertise;

/**
 * Class ConfirmationCodeRequest
 * @package App\Http\Requests\V1\Users
 */
class CheckoutUrlRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        $this->request->set('owner', $this->owner());

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
       return [
           'id' => 'required|exists:commercial_users',
           'advertise_id' => 'required|exists:advertises,id',
           'success_url' =>  'required|string',
           'cancel_url' =>  'required|string',
       ];
    }

    /**
     * @return \App\Models\Products\Advertise
     */
    public function owner(): Advertise {
        return Advertise::find($this->get('advertise_id'));
    }
}
