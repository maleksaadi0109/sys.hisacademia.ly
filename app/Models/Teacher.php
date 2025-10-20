<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'en_name',
        'id_number',
        'nationality',
        'date_of_birth',
        'academic_qualification',
        'phone',
        'pass_image',
        'user_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }
}
