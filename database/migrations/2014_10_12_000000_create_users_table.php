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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')/*->unique()*/;
            $table->string('address')->nullable();
            $table->string('profil')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(false)->comment("Etat du compte");
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('id_role'); // Correction ici

            $table->unsignedBigInteger('id_ChefSection');
            $table->unsignedBigInteger('id_ChefDivision');

            // Définition des clés étrangères
            $table->foreign('id_role')->references('id')->on('roles');
            $table->foreign('id_ChefSection')->references('id')->on('sections');
            $table->foreign('id_ChefDivision')->references('id')->on('divisions');

            /*$table->Unsign('user_id')->nullable()->constrained('users');*/

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
