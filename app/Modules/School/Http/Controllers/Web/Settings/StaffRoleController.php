<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\StaffRole;
use App\Modules\School\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffRoleController extends Controller
{
    public function index(): View
    {
        $roles = StaffRole::with('school')->get();
        return view('School::settings.staff_roles.index', compact('roles'));
    }

    public function create(): View
    {
        $schools = School::all();
        return view('School::settings.staff_roles.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'exists:schools,id'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        StaffRole::create($validated);
        return redirect()->route('settings.rh.staff-roles.index')->with('success', 'Rôle du personnel ajouté avec succès.');
    }

    public function show(StaffRole $staffRole): View
    {
        $staffRole->load('school');
        return view('School::settings.staff_roles.show', compact('staffRole'));
    }

    public function edit(StaffRole $staffRole): View
    {
        $schools = School::all();
        return view('School::settings.staff_roles.edit', compact('staffRole', 'schools'));
    }

    public function update(Request $request, StaffRole $staffRole): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'exists:schools,id'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        $staffRole->update($validated);
        return redirect()->route('settings.rh.staff-roles.index')->with('success', 'Rôle du personnel mis à jour avec succès.');
    }

    public function destroy(StaffRole $staffRole): RedirectResponse
    {
        $staffRole->delete();
        return redirect()->route('settings.rh.staff-roles.index')->with('success', 'Rôle du personnel supprimé avec succès.');
    }
}