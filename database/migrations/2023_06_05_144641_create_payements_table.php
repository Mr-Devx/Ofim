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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->index()->nullable()->comment("");
            $table->text('means_of_payment')->nullable();
            $table->text('code_payement')->nullable();
            $table->double('amount')->nullable();
            $table->boolean('state')->default(false)->comment("Etat du paiement");
            $table->dateTime('date_payement')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};
