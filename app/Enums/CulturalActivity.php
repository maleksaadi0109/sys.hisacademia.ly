<?php

namespace App\Enums;

enum CulturalActivity: string
{
    const yes = 'yes';
    const no = 'no';

    static function culturalActivityAr(): array{
        return [
            'yes' => 'نعم',
            'no' => 'لا',
        ];
    }
}