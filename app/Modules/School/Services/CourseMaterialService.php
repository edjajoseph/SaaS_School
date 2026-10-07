<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\CourseMaterial;
use App\Modules\School\Models\TeacherSubjectRate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CourseMaterialService
{
    /**
     * Repertoire de stockage dans storage/app/public/course_materials
     */
    protected string $storagePath = 'course_materials';

    /**
     * Enregistre un nouveau support de cours sur le disque et en BDD.
     */
    public function storeMaterial(TeacherSubjectRate $teacherSubjectRate, UploadedFile $file, string $title, string $type = 'course_note'): CourseMaterial
    {
        // 1. Génération d'un nom de fichier unique sécurisé
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;

        // 2. Sauvegarde du fichier sur le disk 'public'
        $filePath = $file->storeAs($this->storagePath, $fileName, 'public');

        // 3. Création de l'enregistrement en BDD
        return CourseMaterial::create([
            'teacher_subject_rate_id' => $teacherSubjectRate->id,
            'title'                   => $title,
            'type'                    => $type,
            'file_path'               => $filePath,
            'original_filename'       => $file->getClientOriginalName(),
            'mime_type'               => $file->getClientMimeType(),
            'file_size'               => $file->getSize(),
            'uploaded_at'             => now(),
        ]);
    }


    /**
     * Enregistre plusieurs supports de cours en une seule fois.
     *
     * @param TeacherSubjectRate $teacherSubjectRate
     * @param array $materialsData Contient ['files' => [...], 'titles' => [...], 'types' => [...]]
     * @return int Nombre de fichiers enregistrés
     */
    public function storeMultipleMaterials(TeacherSubjectRate $teacherSubjectRate, array $materialsData): int
    {
        return DB::transaction(function () use ($teacherSubjectRate, $materialsData) {
            $count = 0;
            $files = $materialsData['files'];
            $titles = $materialsData['titles'];
            $types = $materialsData['types'];

            foreach ($files as $index => $file) {
                $this->storeMaterial(
                    $teacherSubjectRate,
                    $file,
                    $titles[$index] ?? $file->getClientOriginalName(),
                    $types[$index] ?? 'course_note'
                );
                $count++;
            }

            return $count;
        });
    }


    /**
     * Supprime logiquement (Soft Delete) ou définitivement un support de cours.
     */
    public function deleteMaterial(CourseMaterial $material, bool $forceDelete = false): bool
    {
        if ($forceDelete) {
            // Suppression du fichier physique sur le disque
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            return $material->forceDelete();
        }

        return $material->delete();
    }
}