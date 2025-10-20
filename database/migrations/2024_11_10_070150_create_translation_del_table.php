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
        Schema::create("translation_del", function (Blueprint $table) {
            $table->id();
            $table->integer("number_of_sheets")->nullable();
            $table->integer("number_of_transaction")->nullable();
            $table->string("context")->nullable();
            $table->string("language")->nullable();
            $table->unsignedBigInteger("customer_id");
            $table->float("price");
            $table->string('currency');
            $table->float("translator_share");
            $table->float("academy_share");
            $table->float("received");
            $table->float("remaining");
            $table->string("payment_method")->nullable();
            $table->date("date_of_receipt")->nullable();
            $table->date("due_date")->nullable();
            $table->date("delivery_date")->nullable();
            $table->timestamps();
            $table->foreign("customer_id")->references("id")->on("customer");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_del');
    }
};
