<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete(); // Optionnel : spécifique à un niveau (ex: L3 CS)
            $table->foreignId('serie_id')->constrained('series')->cascadeOnDelete();
            $table->string('name');              // ex: UE Fondamentale - Génie Logiciel
            $table->string('code')->nullable();  // ex: UE-GL31
            $table->enum('type', ['fundamental', 'transversal', 'optional', 'professional'])->default('fundamental');
            
            $table->integer('credits')->default(0);    // Nombre de crédits ECTS (ex: 6 ECTS)
            $table->decimal('coefficient', 5, 2)->default(1.00); // Coefficient global de l'UE
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_units');
    }
};