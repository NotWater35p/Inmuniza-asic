<?php

namespace App\Enums;

enum SubtipoPaciente: string
{
    case General           = 'general';
    case PersonalSalud     = 'personal_salud';
    case Dialisis          = 'dialisis';
    case PrivadoLibertad   = 'privado_libertad';
    case TrabajadorSexual  = 'trabajador_sexual';
    case Embarazada        = 'embarazada';

    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }
}