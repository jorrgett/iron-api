<?php

namespace App\Http\Requests\Odoo;

use Illuminate\Foundation\Http\FormRequest;

class SyncRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'procesado_iron'  => 'boolean',
            'page'            => 'numeric|min:1',
            'state'           => 'in:todo,running,done,cancelled',
            'size'            => 'numeric|min:1',
            'post_processed'  => 'boolean',
            'origin'            => 'in:autobox,gwmve'
        ];
    }
}
