<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case DNI = 'DNI';
    case CE = 'CE';
    case PASSPORT = 'PASSPORT';
    case RUC = 'RUC';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::DNI => 'Documento Nacional de Identidad',
            self::CE => 'Carné de Extranjería',
            self::PASSPORT => 'Pasaporte',
            self::RUC => 'Registro Único de Contribuyentes',
        };
    }

    public function regex(): string
    {
        return match ($this) {
            self::DNI => '/^[0-9]{8}$/',
            self::CE => '/^[0-9]{9}$/',
            self::PASSPORT => '/^[A-Z0-9]{6,9}$/',
            self::RUC => '/^(10|20)[0-9]{9}$/',
        };
    }
}
