<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Revenue extends Model
{
    use HasFactory;

    protected $table = 'revenue';

    protected $fillable = [
        'course_id',
        'type',
        'date_of_rec',
        'value',
        'currency',
        'user_id',
        'value_rec',
        'value_rem',
        'diploma_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id','course_id');
    }

    public function diploma(): HasOne
    {
        return $this->hasOne(Diploma::class, 'id','diploma_id');
    }
}
