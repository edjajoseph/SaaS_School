<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // Ex: BAC, LIC, MST, DOC
            $table->string('name'); // Ex: Licence, Master, Doctorat, CAPES
            $table->string('level'); // Permet d'ordonner par niveau d'études
            $table->integer('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('degrees');
    }
};