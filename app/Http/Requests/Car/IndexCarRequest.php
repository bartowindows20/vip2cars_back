<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class IndexCarRequest extends FormRequest
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
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'integer'   => 'El :attribute debe ser un número entero.',
            'min'       => 'El campo :attribute debe ser al menos :min.',
            'max'       => 'El campo :attribute no debe ser mayor que :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'per_page'  => 'elementos por página',
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->has('per_page')) {
            $this->merge([
                'per_page' => 15,
            ]);
        }
    }
}
