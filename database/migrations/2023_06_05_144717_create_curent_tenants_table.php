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
        Schema::create('curent_tenants', function (Blueprint $table) {
            $table->id();
            $table->boolean('state')->default(false)->comment("Etat de la location");
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('curent_note')->nullable();
            $table->boolean('is_an_extension')->default(false)->comment("Est une prolongation d ela location");
            $table->foreignId('payement_id')->nullable()->constrained('payements');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('car_id')->nullable()->constrained('cars');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curent_tenants');
    }
};
