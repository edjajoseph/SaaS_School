<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subject::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $subjects = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('School::settings.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('School::settings.subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:subjects,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Subject::create($validated);

        return redirect()
             ->route('settings.academic.subjects.index')
            ->with('success', 'Matière ajoutée au référentiel avec succès.');
    }

    public function show(Subject $subject): View
    {
        $subject->loadCount('schoolSubjects');

        return view('School::settings.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('School::settings.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subject->update($validated);

        return redirect()
             ->route('settings.academic.subjects.index')
            ->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        if ($subject->schoolSubjects()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette matière car elle est liée à une ou plusieurs écoles.');
        }

        $subject->delete();

        return redirect()
             ->route('settings.academic.subjects.index')
            ->with('success', 'Matière supprimée du référentiel.');
    }
}