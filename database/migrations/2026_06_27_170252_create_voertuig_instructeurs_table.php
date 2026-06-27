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
        Schema::create('voertuig_instructeurs', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('VoertuigId')->constrained('voertuigs', 'Id')->onDelete('cascade');
            $table->foreignId('InstructeurId')->constrained('instructeurs', 'Id')->onDelete('cascade');
            $table->date('DatumToekenning');
            // System fields
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->timestamp('DatumAangemaakt')->nullable();
            $table->timestamp('DatumGewijzigd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voertuig_instructeurs');
    }
};
