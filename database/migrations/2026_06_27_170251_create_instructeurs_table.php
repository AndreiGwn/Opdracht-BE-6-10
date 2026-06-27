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
        Schema::create('instructeurs', function (Blueprint $table) {
            $table->id('Id');
            $table->string('Voornaam');
            $table->string('Tussenvoegsel')->nullable();
            $table->string('Achternaam')->nullable();
            $table->string('Mobiel');
            $table->date('DatumInDienst');
            $table->integer('AantalSterren');
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
        Schema::dropIfExists('instructeurs');
    }
};
