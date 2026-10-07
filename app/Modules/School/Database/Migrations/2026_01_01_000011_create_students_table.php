<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {        
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personne_id')->constrained('personnes')->cascadeOnDelete();
            $table->string('matricule')->unique(); // Matricule unique et permanent
            $table->foreignId('nationality')->nullable()->constrained('countries')->nullOnDelete(); // Nationalité (Pays)
            
            // Filiation & Tuteurs (Permanents)
            $table->string('father_name')->nullable();
            $table->string('father_job')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_phone')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            
            $table->string('previous_school')->nullable(); // École de provenance au moment de cette inscription
            $table->enum('type', ['inscription', 'reinscription'])->default('inscription');
            $table->enum('status', ['pending','confirmed','canceled','transferred'])->default('pending');
            $table->date('enrolled_at')->useCurrent();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Empêcher d'inscrire le même étudiant 2 fois la même année académique
            $table->unique(['student_id', 'academic_year_id']);
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: Extrait de naissance
            $table->string('code')->unique(); // ex: BIRTH_CERTIFICATE
            $table->boolean('is_required')->default(false); // Optionnel : document obligatoire par défaut
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            // Lié au dossier permanent de l'étudiant
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            
            // Lié à l'acte administratif / dossier d'inscription
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->nullOnDelete();
        
            // Ajouter la clé étrangère vers document_types
            $table->foreignId('document_type_id')->constrained('document_types')->cascadeOnDelete();
            
            $table->string('title')->nullable();
            $table->boolean('is_provided')->default(false);
            $table->string('file_path')->nullable();
            $table->text('remarks')->nullable();
        
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('student_documents');
    }
};