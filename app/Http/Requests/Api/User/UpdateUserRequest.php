<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ApiTraits;

class UpdateUserRequest extends FormRequest
{
    use ApiTraits;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable|email|unique:users,email,'.Auth::user()->id,
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response =  $this->responseJsonFailedValidate($validator->errors());
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
