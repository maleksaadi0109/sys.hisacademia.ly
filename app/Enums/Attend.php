<?php

namespace App\Enums;

enum Attend: string
{
    const online = 'online';
    const physicist = 'physicist';

    static function attendAr(): array{
        return [
            'online' => 'اون لاين',
            'physicist' => 'فيزيائي',
        ];
    }
}