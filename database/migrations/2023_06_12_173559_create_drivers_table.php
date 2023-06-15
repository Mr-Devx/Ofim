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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->string('profil')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_path')->nullable();
            $table->date('license_expire_date')->nullable();
            $table->integer('note')->nullable();

            $table->foreignId('owner_id')->nullable()->constrained('users')->comment("Propriétaire du chauffeur");
            $table->boolean('is_active')->default(false)->comment("Etat du chauffeur");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
