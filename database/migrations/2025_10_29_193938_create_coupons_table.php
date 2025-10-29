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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // كود الكوبون
            $table->enum('type', ['fixed', 'percentage']); // نوع الكوبون: مبلغ ثابت أو نسبة مئوية
            $table->decimal('value', 10, 2); // قيمة الكوبون (100 دينار أو 20%)
            $table->enum('applicable_to', ['courses', 'diplomas', 'both'])->default('both'); // ينطبق على: الكورسات، الدبلومات، أو كليهما
            $table->integer('usage_limit')->nullable(); // حد الاستخدام (null = غير محدود)
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام
            $table->date('valid_from')->nullable(); // صالح من تاريخ
            $table->date('valid_until')->nullable(); // صالح حتى تاريخ
            $table->boolean('is_active')->default(true); // نشط أم لا
            $table->text('description')->nullable(); // وصف الكوبون
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
