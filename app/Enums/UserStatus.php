<?php

namespace App\Enums;

enum UserStatus: string
{
    const active = 'active';
    const inactive = 'inactive';

    static function userStatusAr(): array{
        return [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        ];
    }
}
