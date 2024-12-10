<?php

namespace App\Http\Requests\Vehicle;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if (!$this->filled('nickname')) {
            $this->merge([
                'nickname' => 'Auto ' . $this->input('plate'),
            ]);
        }

        if (!$this->filled('register_date')) {
            $this->merge([
                'register_date' => Carbon::now()->toDateTimeString(),
            ]);
        }

        if (!$this->filled('year')) {
            $this->merge([
                'year' => Carbon::now()->year,
            ]);
        }

        if (!$this->filled('transmission')) {
            $this->merge([
                'transmission' => 'manual',
            ]);
        }

        if (!$this->filled('fuel')) {
            $this->merge([
                'fuel' => 'gasoline',
            ]);
        }

        if (!$this->filled('odometer')) {
            $this->merge([
                'odometer' => 0,
            ]);
        }

        if (!$this->filled('type_vehicle')) {
            $this->merge([
                'type_vehicle' => 'VEHICULO'
            ]);
        }

        if (!$this->filled('odometer_unit')) {
            $this->merge([
                'odometer_unit' => 'kilometers'
            ]);
        }

        if (!$this->filled('icon')) {
            $this->merge([
                'icon' => 1
            ]);
        }

        if ($this->filled('color')) {
            $colorHexMap = [
                'verde'    => '#00CC33',
                'white'    => '#FFFFFF',
                'plateado' => '#e3e4e5',
                'gris'     => '#9b9b9b',
                'beige'    => '#f5f5dc',
                'amarillo' => '#FFFF00',
                'naranja'  => '#FF6600',
                'arena'    => '#ece2c6',
                'rojo'     => '#FF0000',
                'azul'     => '#0000FF',
                'black'    => '#000000'
            ];

            $color = strtolower($this->input('color'));

            if (array_key_exists($color, $colorHexMap)) {
                $this->merge([
                    'color_hex' => $colorHexMap[$color],
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nickname'          => "sometimes|string",
            'vehicle_brand_id'  => "sometimes|integer",
            'brand_name'        => "required_with:vehicle_brand_id|string",
            'vehicle_model_id'  => "sometimes|integer",
            'model_name'        => "required_with:vehicle_model_id|string",
            'color'             => "required|string",
            'color_hex'         => "sometimes|string",
            'plate'             => "required|string"
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
            'nickname.string' => 'The nickname must be a string.',
            'brand_id.required_with' => 'The brand ID is required when the brand name is present.',
            'brand_id.integer' => 'The brand ID must be an integer.',
            'brand_name.string' => 'The brand name must be a string.',
            'model_id.required_with' => 'The model ID is required when the model name is present.',
            'model_id.integer' => 'The model ID must be an integer.',
            'model_name.string' => 'The model name must be a string.',
            'color.required' => 'The color field is required.',
            'color.string' => 'The color must be a string.',
            'color_hex.string' => 'The color hex must be a valid string.',
            'plate.required' => 'The plate field is required.',
            'plate.string' => 'The plate must be a string.'
        ];
    }
}
