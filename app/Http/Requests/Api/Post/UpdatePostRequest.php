<?php

namespace App\Http\Requests\Api\Post;

use App\Traits\ApiTraits;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
                'title' => 'required',
                'body' => 'required',
                'category_id' => 'required|exists:categories,id',
                'tags' => 'nullable|array',
                "tags*" => 'required|exists:tags,id',
            ];
        }else{
            return [
                'title' => 'sometimes|required',
                'body' => 'sometimes|required',
                'category_id' => 'sometimes|required|exists:categories,id',
                'tags' => 'nullable|array',
                "tags*" => 'required|exists:tags,id',
            ];
        }
        
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response =  $this->responseJsonFailedValidate($validator->errors());
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
