<?php

namespace App\Http\Requests\Api\Category;

use App\Traits\ApiTraits;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'name' => 'required',
            ];
        }else{
            return [
                'name' => 'sometimes|required',
            ];
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response =  $this->responseJsonFailedValidate($validator->errors());
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
