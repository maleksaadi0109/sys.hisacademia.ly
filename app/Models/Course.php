<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'section',
        'start_date',
        'end_date',
        'level',
        'start_time',
        'end_time',
        'total_days',
        'average_hours',
        'total_hours',
        'n_d_per_week',
        'days',
        'price',
        'currency',
        'teacher_id',
        'diploma_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','teacher_id');
    }

    public function diploma(): HasOne
    {
        return $this->hasOne(Diploma::class, 'id','diploma_id');
    }

}
