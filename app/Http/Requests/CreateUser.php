<?php

namespace Emrad\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUser extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => 'required|string|max:20',
            'lastName' => 'required|string|max:30',
            'middleName' => 'string',
            'gender' => 'required',
            'pathToAvater' => 'nullable',
            'phoneNumber' => 'required',
            'email' => 'required|unique:users',
            'dob' => 'date|nullable',
            'password' => 'required|confirmed|string',
            'userType' => 'required|exists:roles,name'
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstName.required' => 'first name is required',
            'lastName.required' => 'last name is required',
            'gender.required' => 'gender is required',
            'email.required' => 'email is required',
            'email.unique' => 'email already exist',
            'password.required'  => 'password is required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors()->all(), 422));
    }
}
