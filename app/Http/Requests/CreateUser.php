<?php

namespace Emrad\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => 'required|string|max:20',
            'lastName' => 'required|string|max:30',
            'middleName' => 'string',
            'gender' => 'nullable',
            'pathToAvater' => 'nullable',
            'phoneNumber' => 'nullable',
            'email' => 'required|unique:users',
            'dob' => 'date|nullable',
            'password' => 'required|string',
            'userType' => 'required|exists:roles,name'
        ];
    }

    public function messages(): array
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
        throw new HttpResponseException(
            response([
                'status' => false,
                'message' => implode(', ', $validator->errors()->all()),
                'data' => null
            ], 400)
        );
    }
}
