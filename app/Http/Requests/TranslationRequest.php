<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class TranslationRequest extends FormRequest
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
        $id = $this->route('translation')?->id ?? null;

        return [
            'locale'    => 'required|string|max:3',
            'key'       => [
                'required',
                'string',
                'max:255',
                Rule::unique('translations')
                    ->where(fn ($query) => 
                        $query->where('locale', $this->input('locale'))
                    )->ignore($id)
            ],
            'content'   => 'required|string',
            'tags'      => 'nullable|array',
            'tags.*'    => 'required|string|in:web,mobile',
        ];
    }

    public function messages()
    {
        return [
            'key.unique' => 'The combination of locale and key must be unique.',
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
