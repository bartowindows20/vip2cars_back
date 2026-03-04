<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarRequest extends FormRequest
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
            'car_model_id'      => ['required', 'exists:car_models,id'],
            'plate'             => [
                'required',
                Rule::unique('cars')->whereNull('deleted_at')
            ],
            'year_manufacture'  => ['required', 'date_format:Y']
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => 'El campo :attribute es requerido.',
            'unique'        => 'El valor del campo :attribute ya esta en uso.',
            'exists'        => 'El campo :attribute no existe.',
            'date_format'   => 'El valor del campo :attribute no esta en el formato correcto'
        ];
    }

    public function attributes()
    {
        return [
            'car_model_id'      => 'modelo de vehículo',
            'plate'             => 'número de placa',
            'year_manufacture'  => 'año de fabricación',
        ];
    }
}
