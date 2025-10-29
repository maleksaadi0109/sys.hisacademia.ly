<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'applicable_to',
        'specific_course_id',
        'specific_diploma_id',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'description'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer'
    ];

    /**
     * Generate a unique coupon code
     */
    public static function generateCode($length = 8)
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Check if coupon is valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($originalAmount)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $originalAmount);
        } else { // percentage
            return ($originalAmount * $this->value) / 100;
        }
    }

    /**
     * Apply coupon and increment usage count
     */
    public function apply()
    {
        $this->increment('used_count');
    }

    /**
     * Check if coupon applies to specific type (course/diploma)
     */
    public function appliesTo($type)
    {
        return $this->applicable_to === 'both' || $this->applicable_to === $type;
    }

    /**
     * Get formatted value display
     */
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'fixed') {
            return $this->value . ' دينار';
        } else {
            return $this->value . '%';
        }
    }

    /**
     * Get the specific course for this coupon
     */
    public function specificCourse()
    {
        return $this->belongsTo(\App\Models\Course::class, 'specific_course_id');
    }

    public function specificDiploma()
    {
        return $this->belongsTo(\App\Models\Diploma::class, 'specific_diploma_id');
    }
}
