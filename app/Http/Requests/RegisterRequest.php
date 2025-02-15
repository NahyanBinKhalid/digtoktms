<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_uuid'  =>  'nullable|exists:countries,uuid',
            'first_name'    =>  'required|string',
            'last_name'     =>  'required|string',
            'email'         =>  'required|email|string|unique:users',
            'phone'         =>  'required|string|unique:users',
            'role'          =>  'sometimes|required|in:admin,contractor,employee',
            'password'      =>  'required|string|min:8|confirmed',
            'avatar'        =>  'sometimes|nullable|string',
            'document'      =>  'sometimes|nullable|string',
            'address'       =>  'nullable|string',
            'post_code'     =>  'nullable|string',
            'gender'        =>  'nullable|string|in:male,female',
            'dob'           =>  'nullable|date',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'     =>  true,
            'message'   =>  'Validation errors',
            'data'      =>  $validator->errors()
        ], 422));
    }
}
