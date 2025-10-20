<?php

namespace App\Enums;

enum EnumPermission: string
{
    const modification = 'modification';
    const delete = 'delete';
    const view_report = 'view_report';
    
    static function enumPermissionAr(): array{
        return [
            'modification' => 'التعديل',
            'delete' => 'الحذف',
            'view_report' => 'عرض التقارير',
        ];
    }
}
