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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->text('model')->nullable();
            $table->text('year')->nullable();
            $table->text('color')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->double('day_price')->nullable();
            $table->double('location_price')->nullable();
            $table->double('client_price')->nullable();
            $table->double('note')->nullable();
            $table->double('km')->nullable()->comment("Kilometre");
            $table->text('registration')->nullable();
            $table->text('address')->nullable(); // Quartier-ville du véhicule
            $table->date('verified_at')->nullable();
            $table->double('percentage_reduction')->nullable();
            $table->boolean('is_manuel')->default(false)->comment("Etat du compte");

            $table->foreignId('mark_id')->nullable()->constrained('mark_cars');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('category_id')->nullable()->constrained('category_cars');
            $table->foreignId('state_id')->nullable()->constrained('state_cars');
            $table->foreignId('type_id')->nullable()->constrained('type_cars');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
