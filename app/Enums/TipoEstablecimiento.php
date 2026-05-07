<?php

namespace App\Enums;

enum TipoEstablecimiento: string
{
    case CP1             = 'CP1';
    case CP2             = 'CP2';
    case CP3             = 'CP3';
    case HOSPITAL        = 'HOSPITAL';
    case CDI             = 'CDI';
    case IVSS            = 'IVSS';
    case IPASME          = 'IPASME';
    case SANIDAD_MILITAR = 'SANIDAD MILITAR';
    case PRIVADO         = 'PRIVADO';
    case OTROS           = 'OTROS';

    /**
     * Devuelve un array simple con todos los valores (útil para validación)
     */
    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }
}