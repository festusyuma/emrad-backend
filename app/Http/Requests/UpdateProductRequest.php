<?php

namespace Emrad\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('product')->user_id == auth('api')->id();
    }

    public function rules(): array
    {
        return [
            'categoryId' => 'required',
            'name' => 'required',
//            'sku' => 'required|unique:products,sku',
            'price' => 'required',
            'sellingPrice' => 'required',
            'size' => 'nullable',
        ];
    }


    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response([
                'status' => false,
                'message' => implode(', ', $validator->errors()->all()),
                'data' => null
            ], 400)
        );
    }
}
