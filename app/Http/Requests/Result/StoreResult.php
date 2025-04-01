<?php

namespace App\Http\Requests\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreResult extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string|mixed>
     */
    public function rules(): array
    {
        return [
            'absension' => 'required|integer',
            'invalid_bulletin' => 'required|integer',
            'expressed_suffrage' => 'required|integer',
            'office' => 'nullable',
            'user_id' => 'required|integer',
            'center_id' => 'required|integer',
            'statistics' => 'nullable'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'absension.required' => "L'absention est obligatoire",
            'absension.integer' => "L'absention doit être un entier",

            'invalid_bulletin.required' => "Le bulletin null est obligatoire",
            'invalid_bulletin.integer' => "Le bulletin null doit être un entier",

            'expressed_suffrage.required' => "Le suffrage exprimé est obligatoire",
            'expressed_suffrage.integer' => "Le suffrage exprimé doit être un entier",

            'user_id.required' => "Le représentant est obligatoire",
            'user_id.integer' => "Le représentant doit être un entier",

            'center_id.required' => "Le centre de vote est obligatoire",
            'center_id.integer' => "Le centre de vote doit être un entier",
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
