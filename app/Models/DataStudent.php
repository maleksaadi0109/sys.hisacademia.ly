<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStudent extends Model
{
    protected $table = 'data_student';

    use HasFactory;

    protected $fillable = [
        'section',
        'level',
        'attend',
        'course_type',
        'course_days',
        'course_start_time',
        'course_end_time',
        'classroom_name',
        'cultural_activity',
        'payment_method',
        'signature',
        'student_id',
    ];
}
