<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_number',
        'username',
        'user_type',
        'status',
        'permission',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
    
        static::created(function ($model) {
            if($model->user_type == UserType::student){
                $model->employee_number = 'stud_num_' . $model->id;
            }elseif($model->user_type == UserType::teacher){
                $model->employee_number = 'teach_num_' . $model->id;
            }else{
                $model->employee_number = 'emp_num_' . $model->id;
            }
            $model->save();
        });
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }

    public function studentCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'user_course','user_id', 'course_id');
    }

    public function studentDiploma(): BelongsToMany
    {
        return $this->belongsToMany(Diploma::class, 'user_diploma','user_id', 'diploma_id');
    }

    public function teacherCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }

    public function teacherCoursesNotEnd()
    {
        $date = date('Y-m-d');
        return $this->teacherCourses()->where('end_date', '>=', $date);
    }

    public function studentCoursesNotEnd()
    {
        $date = date('Y-m-d');
        return $this->studentCourses()->where('end_date', '>=', $date);
    }
}
