<?php

namespace App\Enums;

enum WeekDays: string
{
    const su = 'su';
    const mo = 'mo';
    const tu = 'tu';
    const we = 'we';
    const th = 'th';
    const fr = 'fr';
    const sa = 'sa';

    static function weekDaysAr(): array{
        return [
        'su' => 'الاحد',
        'mo' => 'الاثنين',
        'tu' => 'الثلاثاء',
        'we' => 'الاربعاء',
        'th' => 'الخميس',
        'fr' => 'الجمعة',
        'sa' => 'السبت',
        ];
    }
}
