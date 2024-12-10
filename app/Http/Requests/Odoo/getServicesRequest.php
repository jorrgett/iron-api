<?php

namespace App\Http\Requests\Odoo;

use Illuminate\Foundation\Http\FormRequest;

class getServicesRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'filter_contact' => 'required|array',
            'filter_contact.*.field' => 'required_with:filter_contact',
            'filter_contact.*.operator' => 'required_with:filter_contact',
            'filter_contact.*.value'    => 'required_with:filter_contact'
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
            'filter_contact.required' => 'El :attribute es obligatorio.',
            'filter_contact.array' => 'El :attribute debe ser un array válido.',
            'filter_contact.*.field.required' => 'El field debe ser un atributo dentro array de :attribute',
            'filter_contact.*.operator.required' => 'El field debe ser un atributo dentro array de :attribute',
            'filter_contact.*.value.required' => 'El field debe ser un atributo dentro array de :attribute',
        ];
    }

    /**
    * Get custom attributes for validator errors.
    *
    * @return array<string, string>
    */
    public function attributes(): array
    {
        return [
            'filter_contact' => 'filtro de contactos',
        ];
    }
}
