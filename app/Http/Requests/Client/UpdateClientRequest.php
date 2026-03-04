<?php

namespace App\Http\Requests\Client;

use App\Enums\DocumentTypeEnum;
use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
            'names'             => ['required', 'string', 'max:255'],
            'paternal_surname'  => ['required', 'string', 'max:255'],
            'maternal_surname'  => ['required', 'string', 'max:255'],
            'document_type'     => ['required', Rule::in(DocumentTypeEnum::values())],
            'document_number'   => ['required', 'string', 'max:30'],
            'email'             => ['required', 'email'],
            'phone'             => ['required', 'string', 'max:30'],
            'updated_at'        => ['required', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'required'          => 'El campo :attribute es requerido.',
            'string'            => 'El campo :attribute debe ser un texto.',
            'max'               => 'El campo :attribute no debe ser mayor que :max.',
            'in'                => 'El campo :attribute no es válido.',
            'email'             => 'El campo :attribute debe ser un email válido.',
            'date'          => 'El campo :attribute debe ser una fecha válida.'
        ];
    }

    public function attributes(): array
    {
        return [
            'names'             => 'nombres',
            'paternal_surname'  => 'apellido Paterno',
            'maternal_surname'  => 'apellido Materno',
            'document_type'     => 'tipo de Documento',
            'document_number'   => 'número de Documento',
            'email'             => 'correo Electrónico',
            'phone'             => 'teléfono',
            'updated_at'        => 'fecha de actualización'
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('document_type') && $this->filled('document_number')) {
                $type = strtoupper($this->input('document_type'));
                $number = $this->input('document_number');

                $enum = DocumentTypeEnum::tryFrom($type);

                if (!preg_match($enum->regex(), $number)) {
                    $validator->errors()->add(
                        'document_number',
                        "El número de documento no es válido para {$enum->label()}."
                    );
                    return;
                }

                if (Client::where('document_type', $type)->where('document_number', $number)->where('id', '!=', $this->route('client')->id)->exists()) {
                    $validator->errors()->add(
                        'document_number',
                        "El número de documento ya se encuentra registrado."
                    );
                    return;
                }
            }

            if ($this->filled('updated_at')) {

                if ($this->route('client')->updated_at != $this->input('updated_at')) {
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
