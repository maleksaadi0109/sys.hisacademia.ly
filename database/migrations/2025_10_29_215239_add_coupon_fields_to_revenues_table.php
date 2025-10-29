<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('revenue', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('value_rem');
            $table->decimal('original_price', 10, 2)->nullable()->after('coupon_code');
            $table->decimal('discount_amount', 10, 2)->nullable()->after('original_price');
            $table->string('coupon_type')->nullable()->after('discount_amount'); // fixed or percentage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenue', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'original_price', 'discount_amount', 'coupon_type']);
        });
    }
};