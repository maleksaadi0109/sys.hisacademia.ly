<?php

namespace App\Enums;

enum UserType: string
{
    const admin = 'admin';
    const dataEntry = 'data_entry';
    const financialEmployee = 'financial_employee';
    const student = 'student';
    const teacher = 'teacher';

    static function userTypeAr(): array{
        return [
            'admin' => 'مسؤول النظام',
            'data_entry' => 'مدخل البيانات',
            'financial_employee' => 'موظف مالي',
            'student' => 'طالب',
            'teacher' => 'معلم',
        ];
    }
}