<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
                Rule::unique('cars')->whereNull('deleted_at')->ignore($this->route('car')->id)
            ],
            'year_manufacture'  => ['required', 'date_format:Y'],
            'updated_at'        => ['required', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => 'El campo :attribute es requerido.',
            'unique'        => 'El valor del campo :attribute ya esta en uso.',
            'exists'        => 'El campo :attribute no existe.',
            'date_format'   => 'El valor del campo :attribute no esta en el formato correcto',
            'date'          => 'El campo :attribute debe ser una fecha válida.'
        ];
    }

    public function attributes()
    {
        return [
            'car_model_id'      => 'modelo de vehículo',
            'plate'             => 'número de placa',
            'year_manufacture'  => 'año de fabricación',
            'updated_at'        => 'fecha de actualización',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->filled('updated_at')) {

                if ($this->route('car')->updated_at->format('Y-m-d H:i:s') !== $this->input('updated_at')) {
                    $validator->errors()->add(
                        'updated_at',
                        "No tienes los ultimos cambios de este registro, por favor actualiza."
                    );
                    return;
                }
            }
        });
    }
}
