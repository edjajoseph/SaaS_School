<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('period_type_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_type_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // ex: Semestre 1, Session de Mai
            $table->string('code')->nullable(); // ex: S1, S2, S_MAY
            $table->integer('sequence_order')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['period_type_id', 'sequence_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('period_type_items');
    }
};