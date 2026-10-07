<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\CourseMaterial;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Services\CourseMaterialService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;


class CourseMaterialController extends Controller
{
    protected CourseMaterialService $materialService;

    public function __construct(CourseMaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Affiche le formulaire de téléversement d'un support de cours
     */
    public function create(int $teacherSubjectRateId)
    {
        $subjectRate = TeacherSubjectRate::with([
            'staff',
            'schoolClass',
            'subject'
        ])->findOrFail($teacherSubjectRateId);

        // On passe $subjectRate pour correspondre à la vue
        return view('School::payroll.rates.upload', compact('subjectRate'));
    }

    /**
     * Upload d'un support de cours par l'enseignant
     */
    public function store(Request $request, int $teacherSubjectRateId): RedirectResponse
    {
        $request->validate([
            'titles'   => 'required|array|min:1',
            'titles.*' => 'required|string|max:255',
            'types'    => 'required|array|min:1',
            'types.*'  => 'required|in:syllabus,course_note,td,tp,exam,other',
            'files'    => 'required|array|min:1',
            'files.*'  => 'required|file|mimes:pdf,docx,doc,pptx,ppt,zip,rar|max:20480', // Max 20Mo par fichier
        ]);

        $teacherSubjectRate = TeacherSubjectRate::findOrFail($teacherSubjectRateId);

        $count = $this->materialService->storeMultipleMaterials(
            $teacherSubjectRate,
            $request->only(['files', 'titles', 'types'])
        );

        return redirect()->back()->with('success', "{$count} support(s) de cours ont été téléversés avec succès.");
    }

    
    /**
     * Téléchargement d'un fichier avec son nom d'origine
     */
    public function download(int $id)
    {
        $material = CourseMaterial::findOrFail($id);

        if (!Storage::disk('public')->exists($material->file_path)) {
            return redirect()->back()->with('error', 'Le fichier demandé n\'existe pas ou a été supprimé.');
        }

        return response()->download(
            Storage::disk('public')->path($material->file_path),
            $material->original_filename
        );
    }

    /**
     * Suppression d'un support de cours
     */
    public function destroy(int $id): RedirectResponse
    {
        $material = CourseMaterial::findOrFail($id);
        $this->materialService->deleteMaterial($material);

        return redirect()->back()->with('success', 'Le support de cours a été supprimé.');
    }
}