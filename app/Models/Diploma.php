<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diploma extends Model
{
    use HasFactory;

    protected $table = 'diploma';

    protected $fillable = [
        'name',
        'number_course',
        'price',
        'currency',
    ];

    public function diplomaCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'diploma_id', 'id');
    }

}
