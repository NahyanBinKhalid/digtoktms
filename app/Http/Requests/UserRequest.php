<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        if ($this->route('user')) {
            $userUuid = $this->route('user');
            return [
                'first_name'    =>  'required|string',
                'last_name'     =>  'required|string',
                'email'         =>  ['required', 'email', 'string', Rule::unique('users')->ignore($userUuid, 'uuid')],
                'phone'         =>  ['required', 'string', Rule::unique('users')->ignore($userUuid, 'uuid')],
                'password'      =>  'nullable|string|min:8|confirmed'
            ];
        } else {
            return [    
                'first_name'    =>  'required|string',
                'last_name'     =>  'required|string',
                'email'         =>  'required|email|string|unique:users',
                'phone'         =>  'required|string|unique:users',
                'password'      =>  'required|string|min:8|confirmed',
                'role'          =>  'required|string|in:admin,contractor,employee'
            ];
        }
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
