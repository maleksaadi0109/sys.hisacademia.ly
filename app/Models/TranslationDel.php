<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TranslationDel extends Model
{
    use HasFactory;

    protected $table = 'translation_del';

    protected $fillable = [
        "number_of_sheets",
        "number_of_transaction",
        "context",
        "language",
        "customer_id",
        "price",
        "currency",
        "translator_share",
        "academy_share",
        "received",
        "remaining",
        "payment_method",
        "date_of_receipt",
        "due_date",
        "delivery_date",
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id','customer_id');
    }
}
