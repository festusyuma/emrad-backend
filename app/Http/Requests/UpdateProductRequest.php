<?php

namespace Emrad\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('product')->merchant_id == auth('api')->user()->merchant->id ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoryId' => 'required',
            'productName' => 'required|unique:products,name'.$this->product->name,
            'productSku' => 'required|unique:products,sku',
            'productPrice' => 'required',
            'productSellingPrice' => 'required',
            'productSize' => 'nullable',
        ];
    }


    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }
}
