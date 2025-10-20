<?php

namespace App\Enums;

enum CourseType: string
{
    const regular = 'regular';
    const translation = 'translation';
    const methodological = 'methodological';
    const assignment = 'assignment';
    const diploma = 'diploma';
    const childrens = 'childrens';
    const conversation = 'conversation';

    static function courseTypeAr(): array{
        return [
            'regular' => 'نظامي',
            'translation' => 'ترجمة',
            'methodological' => 'منهجي',
            'assignment' => 'مكلف',
            'diploma' => 'دبلوم',
            'childrens' => 'أطفال',
            'conversation' => 'محادثة',
        ];
    }
}