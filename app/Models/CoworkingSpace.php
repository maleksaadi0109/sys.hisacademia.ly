<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoworkingSpace extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coworking_spaces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
        'daily_price',
        'weekly_price',
        'monthly_price',
        'three_month_price',
        'is_open_weekdays',
        'open_time',
        'close_time',
        'internet_speed',
        'address_line1',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_open_weekdays' => 'boolean',
        'daily_price' => 'decimal:2',
        'weekly_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'three_month_price' => 'decimal:2',
    ];

    /**
     * Get the bookings associated with the coworking space.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
