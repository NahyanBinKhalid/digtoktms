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
            $userId = $this->route('user');
            return [
                'name'          =>  'required|string',
                'email'         =>  ['required', 'email', 'string', Rule::unique('users')->ignore($userId, 'uuid')],
                'password'      =>  'nullable|string|min:6|confirmed'
            ];
        } else {
            return [    
                'name'          =>  'required|string',
                'email'         =>  'required|email|string|unique:users',
                'password'      =>  'required|string|min:6|confirmed'
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
