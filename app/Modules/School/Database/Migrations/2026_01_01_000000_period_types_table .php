<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('period_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: Semestriel, Trimestriel, Sessionnel
            $table->string('code')->unique(); // ex: SEMESTER, TRIMESTER, SESSION
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('period_types');
    }
};