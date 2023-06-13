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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender')->nullable()->constrained('users')->comment("Celui qui envoie le message");
            $table->foreignId('receiver')->nullable()->constrained('users')->comment("Celui qui recois le message");
            $table->foreignId('curent_tenant_id')->nullable()->constrained('curent_tenants')->comment("La reservation associé a la discussion");
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
