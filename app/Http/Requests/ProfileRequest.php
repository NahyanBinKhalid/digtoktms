<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'country_uuid'  =>  'nullable|exists:countries,uuid',
            'first_name'    =>  'required|string',
            'last_name'     =>  'required|string',
            'email'         => ['string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone'         => ['string', 'max:20', Rule::unique(User::class)->ignore($this->user()->id)],
            'avatar'        =>  'sometimes|nullable|string',
            'document'      =>  'sometimes|nullable|string',
            'address'       =>  'nullable|string',
            'post_code'     =>  'nullable|string',
            'gender'        =>  'nullable|string|in:male,female',
            'dob'           =>  'nullable|date'
        ];
    }
}
