<?php

namespace App\Modules\School\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\Room;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Subject;
use App\Modules\School\Services\ScheduleConflictService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    
    public function index(Request $request)
    {
        $selectedClassId = $request->get('school_class_id');
        $schoolId = session('active_school_id', 1);

        // Liste des classes pour le sélecteur
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $rooms = Room::where('school_id', $schoolId)->where('is_active', true)->get();
        $subjects = Subject::where('school_id', $schoolId)->where('is_active', true)->get();
        $teachers = Staff::where('school_id', $schoolId)->where('role_type', 'teacher')->with('personne')->get();

        // Récupération des cours de la classe sélectionnée
        $schedules = collect();
        if ($selectedClassId) {
            $schedules = Schedule::with(['subject', 'teacher.personne', 'room'])
                ->where('school_class_id', $selectedClassId)
                ->get();
        }

        // Configuration des créneaux horaires (Ex: 08h00 à 18h00)
        $timeSlots = [
            '08:00', '09:00', '10:00', '11:00', '12:00', 
            '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        $days = [
            'monday'    => 'Lundi',
            'tuesday'   => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday'  => 'Jeudi',
            'friday'    => 'Vendredi',
            'saturday'  => 'Samedi',
        ];

        return view('school::schedules.index', compact(
            'classes', 
            'rooms', 
            'subjects', 
            'teachers', 
            'schedules', 
            'selectedClassId', 
            'timeSlots', 
            'days'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id'       => 'required|exists:schools,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'staff_id'        => 'nullable|exists:staff,id',
            'room_id'         => 'nullable|exists:rooms,id',
            'term_id'         => 'nullable|exists:terms,id',
            'subject_name'    => 'required|string|max:255',
            'day_of_week'     => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'      => 'required|date_format:H:i',
            'end_time'        => 'required|date_format:H:i|after:start_time',
        ]);

        // Vérification des conflits avant enregistrement
        $conflicts = ScheduleConflictService::checkConflicts($validated);

        if (!empty($conflicts)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['schedule_conflict' => $conflicts]);
        }

        // Création de la séance
        $schedule = Schedule::create($validated);

        // TODO: Déclencher l'évènement de synchronisation vers Moodle si activé
        
        return redirect()->back()->with('success', 'Séance planifiée avec succès sans aucun conflit.');
    }
}