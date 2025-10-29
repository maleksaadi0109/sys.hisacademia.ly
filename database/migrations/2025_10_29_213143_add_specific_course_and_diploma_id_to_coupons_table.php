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
        Schema::table('coupons', function (Blueprint $table) {
            // Add specific_diploma_id only if it doesn't exist
            if (!Schema::hasColumn('coupons', 'specific_diploma_id')) {
                $table->unsignedBigInteger('specific_diploma_id')->nullable()->after('specific_course_id');
                $table->foreign('specific_diploma_id')->references('id')->on('diploma')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'specific_diploma_id')) {
                $table->dropForeign(['specific_diploma_id']);
                $table->dropColumn('specific_diploma_id');
            }
        });
    }
};