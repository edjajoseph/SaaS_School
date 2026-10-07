<div class="modal-header bg-primary text-white">
    <h5 class="modal-title"><i class="fa fa-calendar-plus mr-2"></i>Ajouter un Emploi du Temps (Multi-Créneaux)</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form method="POST" action="{{ route('schedule.schedules.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <!-- Section 1 : Attributions Générales -->
        <div class="row border-bottom pb-3 mb-3">
            <div class="col-md-12 mb-2">
                <h6 class="font-weight-bold text-primary"><i class="fa fa-info-circle mr-1"></i> Informations Générales</h6>
            </div>

            @if(!empty($isAdmin))
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Établissement <span class="text-danger">*</span></label>
                <select name="school_id" id="school_id_select" class="form-control" required>
                    <option value="">Sélectionner un établissement</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Classe <span class="text-danger">*</span></label>
                <select name="school_class_id" id="school_class_id" class="form-control" required>
                    <option value="">Sélectionner une classe</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Matière / ECUE <span class="text-danger">*</span></label>
                <select name="subject_id" id="subject_id" class="form-control" required>
                    <option value="">Sélectionner une matière</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->custom_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Enseignant <span class="text-danger">*</span></label>
                <select name="staff_id" id="staff_id" class="form-control" required>
                    <option value="">Sélectionner un enseignant</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('staff_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->first_name ?? ($teacher->personne->prenoms ?? '') }} {{ $teacher->last_name ?? ($teacher->personne->nom ?? '') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Période académique <span class="text-danger">*</span></label>
                <select name="academic_period_id" id="academic_period_id" class="form-control" required>
                    <option value="">Sélectionner une période</option>
                    @foreach($academicPeriods as $period)
                        <option value="{{ $period->id }}" {{ old('academic_period_id') == $period->id ? 'selected' : '' }}>{{ $period->periodTypeItem->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Section 2 : Volumes Horaires Globaux (Transmis à TeacherSubjectRate) -->
        <div class="row border-bottom pb-3 mb-3 bg-light p-2 rounded">
            <div class="col-md-12 mb-2">
                <h6 class="font-weight-bold text-dark"><i class="fa fa-clock-o mr-1"></i> Volumes Horaires Prévus (Heures)</h6>
                <small class="text-muted">Laissez vide pour calculer automatiquement la somme selon les créneaux ci-dessous.</small>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label font-weight-bold">Vol. CM</label>
                <input type="number" step="0.5" min="0" name="volume_cm" class="form-control" placeholder="ex: 20">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label font-weight-bold">Vol. TD</label>
                <input type="number" step="0.5" min="0" name="volume_td" class="form-control" placeholder="ex: 10">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label font-weight-bold">Vol. TP</label>
                <input type="number" step="0.5" min="0" name="volume_tp" class="form-control" placeholder="ex: 5">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label font-weight-bold">Vol. Examen</label>
                <input type="number" step="0.5" min="0" name="volume_examen" class="form-control" placeholder="ex: 2">
            </div>
        </div>

        <!-- Section 3 : Multi-Créneaux Horaires -->
        <div class="row">
            <div class="col-md-12 d-flex justify-content-between align-items-center mb-2">
                <h6 class="font-weight-bold text-primary mb-0"><i class="fa fa-list-alt mr-1"></i> Plages Horaires & Sessions</h6>
                <button type="button" id="add-session-btn" class="btn btn-sm btn-outline-success">
                    <i class="fa fa-plus-circle mr-1"></i> Ajouter un créneau
                </button>
            </div>

            <div class="col-md-12" id="sessions-container">
                <!-- Ligne de Créneau 1 -->
                <div class="session-item border p-3 mb-3 rounded position-relative bg-white shadow-sm">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Type de session <span class="text-danger">*</span></label>
                            <select name="sessions[0][session_type]" class="form-control" required>
                                <option value="CM">Cours Magistral (CM)</option>
                                <option value="TD">Travaux Dirigés (TD)</option>
                                <option value="TP">Travaux Pratiques (TP)</option>
                                <option value="CC">Contrôle Continu (CC)</option>
                                <option value="EXAM">Examen (EXAM)</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Jour <span class="text-danger">*</span></label>
                            <select name="sessions[0][day_of_week]" class="form-control" required>
                                <option value="1">Lundi</option>
                                <option value="2">Mardi</option>
                                <option value="3">Mercredi</option>
                                <option value="4">Jeudi</option>
                                <option value="5">Vendredi</option>
                                <option value="6">Samedi</option>
                                <option value="7">Dimanche</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Salle de classe</label>
                            <select name="sessions[0][room_id]" class="form-control room-select">
                                <option value="">Sélectionner une salle</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label font-weight-bold">Heure de début <span class="text-danger">*</span></label>
                            <input type="time" name="sessions[0][start_time]" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label font-weight-bold">Heure de fin <span class="text-danger">*</span></label>
                            <input type="time" name="sessions[0][end_time]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <a href="{{ route('schedule.schedules.index') }}" class="btn btn-secondary btn-round waves-effect"><i class="fa fa-times mr-1"></i> Fermer</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i> Tout Enregistrer</button>
    </div>
</form>

<script>
$(document).ready(function() {
    var sessionIndex = 1;

    $('#add-session-btn').on('click', function() {
        var roomOptionsHtml = $('#sessions-container .session-item:first .room-select').html() || '<option value="">Sélectionner une salle</option>';

        var html = '<div class="session-item border p-3 mb-3 rounded position-relative bg-white shadow-sm">' +
            '<button type="button" class="btn btn-danger btn-sm remove-session-btn position-absolute" style="top: 10px; right: 10px;" title="Supprimer ce créneau">' +
                '<i class="fa fa-times"></i>' +
            '</button>' +
            '<div class="row">' +
                '<div class="col-md-4 mb-2">' +
                    '<label class="form-label font-weight-bold">Type de session <span class="text-danger">*</span></label>' +
                    '<select name="sessions[' + sessionIndex + '][session_type]" class="form-control" required>' +
                        '<option value="CM">Cours Magistral (CM)</option>' +
                        '<option value="TD">Travaux Dirigés (TD)</option>' +
                        '<option value="TP">Travaux Pratiques (TP)</option>' +
                        '<option value="CC">Contrôle Continu (CC)</option>' +
                        '<option value="EXAM">Examen (EXAM)</option>' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-4 mb-2">' +
                    '<label class="form-label font-weight-bold">Jour <span class="text-danger">*</span></label>' +
                    '<select name="sessions[' + sessionIndex + '][day_of_week]" class="form-control" required>' +
                        '<option value="1">Lundi</option>' +
                        '<option value="2">Mardi</option>' +
                        '<option value="3">Mercredi</option>' +
                        '<option value="4">Jeudi</option>' +
                        '<option value="5">Vendredi</option>' +
                        '<option value="6">Samedi</option>' +
                        '<option value="7">Dimanche</option>' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-4 mb-2">' +
                    '<label class="form-label font-weight-bold">Salle de classe</label>' +
                    '<select name="sessions[' + sessionIndex + '][room_id]" class="form-control room-select">' +
                        roomOptionsHtml +
                    '</select>' +
                '</div>' +
                '<div class="col-md-6 mb-2">' +
                    '<label class="form-label font-weight-bold">Heure de début <span class="text-danger">*</span></label>' +
                    '<input type="time" name="sessions[' + sessionIndex + '][start_time]" class="form-control" required>' +
                '</div>' +
                '<div class="col-md-6 mb-2">' +
                    '<label class="form-label font-weight-bold">Heure de fin <span class="text-danger">*</span></label>' +
                    '<input type="time" name="sessions[' + sessionIndex + '][end_time]" class="form-control" required>' +
                '</div>' +
            '</div>' +
        '</div>';

        $('#sessions-container').append(html);
        sessionIndex++;
    });

    $(document).on('click', '.remove-session-btn', function() {
        $(this).closest('.session-item').remove();
    });

    function loadSchoolData(schoolId) {
        var $classSelect = $('#school_class_id').html('<option value="">Chargement...</option>');
        var $subjectSelect = $('#subject_id').html('<option value="">Chargement...</option>');
        var $teacherSelect = $('#staff_id').html('<option value="">Chargement...</option>');
        var $roomSelects = $('.room-select').html('<option value="">Chargement...</option>');
        var $periodSelect = $('#academic_period_id').html('<option value="">Chargement...</option>');

        if (!schoolId) return;

        var url = "{{ route('schedule.get-school-data', ':id') }}".replace(':id', schoolId);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (typeof data === 'string') data = JSON.parse(data);

                populateSelectOptions($classSelect, data.classes, 'Sélectionner une classe');
                populateSelectOptions($subjectSelect, data.subjects, 'Sélectionner une matière');
                populateSelectOptions($teacherSelect, data.teachers, 'Sélectionner un enseignant');
                populateSelectOptions($roomSelects, data.rooms, 'Sélectionner une salle (Optionnel)');
                populateSelectOptions($periodSelect, data.academicPeriods, 'Sélectionner une période');
            }
        });
    }

    function populateSelectOptions($element, items, placeholder) {
        $element.empty().append('<option value="">' + placeholder + '</option>');
        if (items && items.length > 0) {
            for (var i = 0; i < items.length; i++) {
                $element.append('<option value="' + items[i].id + '">' + items[i].name + '</option>');
            }
        }
    }

    $(document).off('change', '#school_id_select').on('change', '#school_id_select', function() {
        loadSchoolData($(this).val());
    });
});
</script>