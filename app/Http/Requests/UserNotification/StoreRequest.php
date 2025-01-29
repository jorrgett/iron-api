<?php

namespace App\Http\Requests\UserNotification;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, Iluminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            '*.user_id'           => 'integer|required|exists:users,id',
            '*.notification_id'   => 'integer|required|exists:notifications,id',
            '*.vehicle_id'        => 'integer|required|exists:vehicles,id',
            '*.status'            => 'string|required',
        ];
    }

    public function messages(): array
    {
        return [
            '*.user_id.required'           => 'El campo user_id es obligatorio.',
            '*.user_id.integer'            => 'El campo user_id debe ser un número entero.',
            '*.user_id.exists'             => 'El usuario especificado no existe en la base de datos.',

            '*.notification_id.required'   => 'El campo notification_id es obligatorio.',
            '*.notification_id.integer'    => 'El campo notification_id debe ser un número entero.',
            '*.notification_id.exists'     => 'La notificación especificada no existe en la base de datos.',

            '*.vehicle_id.required'        => 'El campo vehicle_id es obligatorio.',
            '*.vehicle_id.integer'         => 'El campo vehicle_id debe ser un número entero.',
            '*.vehicle_id.exists'          => 'El vehículo especificado no existe en la base de datos.',

            '*.status.required'            => 'El campo status es obligatorio.',
            '*.status.string'              => 'El campo status debe ser una cadena de texto.',
        ];
    }
}
