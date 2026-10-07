<div class="modal-header bg-info text-white">
    <h5 class="modal-title"><i class="fa fa-edit mr-2"></i>Modifier l'Emploi du Temps</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form method="POST" action="{{ route('schedule.schedules.update', $schedule) }}" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            @if($isAdmin)
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Établissement <span class="text-danger">*</span></label>
                <select name="school_id" id="edit_school_id_select" class="form-control" required>
                    <option value="">Sélectionner un établissement</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $schedule->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
                <input type="hidden" name="school_id" id="edit_school_id_select" value="{{ $schedule->school_id }}">
            @endif

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Classe <span class="text-danger">*</span></label>
                <select name="school_class_id" id="edit_school_class_id" class="form-control" required>
                    <option value="">Sélectionner une classe</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('school_class_id', $schedule->school_class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Matière <span class="text-danger">*</span></label>
                <select name="subject_id" id="edit_subject_id" class="form-control" required>
                    <option value="">Sélectionner une matière</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->school_subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->custom_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Enseignant <span class="text-danger">*</span></label>
                <select name="staff_id" id="edit_staff_id" class="form-control" required>
                    <option value="">Sélectionner un enseignant</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('staff_id', $schedule->staff_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->prenoms ?? $teacher->first_name }} {{ $teacher->nom ?? $teacher->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Salle de classe</label>
                <select name="room_id" id="edit_room_id" class="form-control">
                    <option value="">Sélectionner une salle (Optionnel)</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', $schedule->room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Période académique <span class="text-danger">*</span></label>
                <select name="academic_period_id" id="edit_academic_period_id" class="form-control" required>
                    <option value="">Sélectionner une période</option>
                    @foreach($academicPeriods as $period)
                        <option value="{{ $period->id }}" {{ old('academic_period_id', $schedule->academic_period_id) == $period->id ? 'selected' : '' }}>
                            {{ $period->name ?? $period->periodTypeItem->name ?? 'Période #'.$period->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Type de session <span class="text-danger">*</span></label>
                <select name="session_type" id="edit_session_type" class="form-control" required>
                    <option value="CM" {{ old('session_type', $schedule->session_type) == 'CM' ? 'selected' : '' }}>Cours Magistral (CM)</option>
                    <option value="TD" {{ old('session_type', $schedule->session_type) == 'TD' ? 'selected' : '' }}>Travaux Dirigés (TD)</option>
                    <option value="TP" {{ old('session_type', $schedule->session_type) == 'TP' ? 'selected' : '' }}>Travaux Pratiques (TP)</option>
                    <option value="CC" {{ old('session_type', $schedule->session_type) == 'CC' ? 'selected' : '' }}>Contrôle Continu (CC)</option>
                    <option value="EXAM" {{ old('session_type', $schedule->session_type) == 'EXAM' ? 'selected' : '' }}>Examen (EXAM)</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Jour de la semaine <span class="text-danger">*</span></label>
                <select name="day_of_week" class="form-control" required>
                    <option value="1" {{ old('day_of_week', $schedule->day_of_week) == 1 ? 'selected' : '' }}>Lundi</option>
                    <option value="2" {{ old('day_of_week', $schedule->day_of_week) == 2 ? 'selected' : '' }}>Mardi</option>
                    <option value="3" {{ old('day_of_week', $schedule->day_of_week) == 3 ? 'selected' : '' }}>Mercredi</option>
                    <option value="4" {{ old('day_of_week', $schedule->day_of_week) == 4 ? 'selected' : '' }}>Jeudi</option>
                    <option value="5" {{ old('day_of_week', $schedule->day_of_week) == 5 ? 'selected' : '' }}>Vendredi</option>
                    <option value="6" {{ old('day_of_week', $schedule->day_of_week) == 6 ? 'selected' : '' }}>Samedi</option>
                    <option value="7" {{ old('day_of_week', $schedule->day_of_week) == 7 ? 'selected' : '' }}>Dimanche</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Heure de début <span class="text-danger">*</span></label>
                <input type="time" name="start_time" class="form-control" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Heure de fin <span class="text-danger">*</span></label>
                <input type="time" name="end_time" class="form-control" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <a href="{{ route('schedule.schedules.index') }}" class="btn btn-secondary btn-round waves-effect"><i class="fa fa-times mr-1"></i> Fermer</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Mettre à jour</button>
    </div>
</form>