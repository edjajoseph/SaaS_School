<form method="POST" action="{{ route('academic.school-subjects.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Établissement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-secondary mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" id="school_id_select" class="select2-modal @error('school_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un établissement") }}" required>
                        <option value=""></option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $selectedSchoolId) == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Unité d'Enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-secondary mr-1"></i> {{ __("Unité d'Enseignement (UE)") }}
                    </label>
                    <select name="teaching_unit_id" id="teaching_unit_id_select" class="select2-modal @error('teaching_unit_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner une UE") }}">
                        <option value=""></option>
                        @foreach($teachingUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('teaching_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('teaching_unit_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Matière Générale (avec data-attributes pour le JS) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-list text-secondary mr-1"></i> {{ __("Matière de référence") }} <span class="text-danger">*</span>
                    </label>
                    <select name="subject_id" id="subject_id_select" class="select2-modal @error('subject_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner la matière") }}" required>
                        <option value=""></option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                    data-name="{{ $subject->name }}" 
                                    data-code="{{ $subject->code }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} {{ $subject->code ? '('.$subject->code.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Intitulé Personnalisé -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-edit text-secondary mr-1"></i> {{ __("Nom Personnalisé") }}
                    </label>
                    <input type="text" name="custom_name" id="custom_name_input" class="form-control form-control-alternative @error('custom_name') is-invalid @enderror" value="{{ old('custom_name') }}" placeholder="Ex: Algorithmique Avancée">
                    @error('custom_name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Code & Couleur -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-secondary mr-1"></i> {{ __("Code de la matière") }}
                    </label>
                    <input type="text" name="code" id="code_input" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ex: INF-201">
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-paint-brush text-secondary mr-1"></i> {{ __("Couleur d'affichage") }}
                    </label>
                    <input type="color" name="color_code" class="form-control form-control-alternative @error('color_code') is-invalid @enderror" value="{{ old('color_code', '#4099ff') }}">
                    @error('color_code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Crédits & Coefficient -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-star text-secondary mr-1"></i> {{ __("Crédits ECTS") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="credits" class="form-control form-control-alternative @error('credits') is-invalid @enderror" value="{{ old('credits', 2) }}" min="0" step="0.5" required>
                    @error('credits') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-secondary mr-1"></i> {{ __("Coefficient") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="coefficient" class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', 1) }}" min="0" step="0.1" required>
                    @error('coefficient') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Heures CM, TD, TP -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures CM") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_cm" class="form-control @error('hours_cm') is-invalid @enderror" value="{{ old('hours_cm', 0) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TD") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_td" class="form-control @error('hours_td') is-invalid @enderror" value="{{ old('hours_td', 0) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TP") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_tp" class="form-control @error('hours_tp') is-invalid @enderror" value="{{ old('hours_tp', 0) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active_check" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold" for="is_active_check">Activer cette matière pour l'établissement</label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" onclick="$(this).closest('.modal').modal('hide');">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> {{ __('Réinitialiser') }}
            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> {{ __('Enregistrer') }}
            </button>
        </div>
    </div>
</form>

<style>
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single {
    background-color: #ffffff !important;
    background-image: none !important;
    background: #ffffff !important;
    border: 1px solid #cccccc !important;
    border-radius: 4px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered {
    background-color: transparent !important;
    background-image: none !important;
    background: transparent !important;
    color: #495057 !important;
    line-height: normal !important;
    padding-left: 0.75rem !important;
    padding-right: 2rem !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin: 0 !important;
    display: block !important;
    width: 100% !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #888888 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
    right: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
    background-image: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    position: static !important;
    margin: 0 !important;
    border-color: #666 transparent transparent transparent !important;
}
</style>

<script>
$(document).ready(function() {
    $('.select2-modal').select2({
        dropdownParent: $('.select2-modal').closest('.modal'),
        width: '100%',
        allowClear: true
    });

    // Auto-remplissage dynamique lors du choix de la matière de référence
    $('#subject_id_select').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var name = selectedOption.data('name') || '';
        var code = selectedOption.data('code') || '';

        // Pré-remplir les champs
        $('#custom_name_input').val(name);
        $('#code_input').val(code);
    });

    // Cascade Établissement -> Unités d'Enseignement
    $('#school_id_select').on('change', function() {
        var schoolId = $(this).val();
        var $teachingUnitSelect = $('#teaching_unit_id_select');

        $teachingUnitSelect.empty().append('<option value=""></option>');

        if (schoolId) {
            $.ajax({
                url: "{{ route('academic.school-subjects.teaching-units') }}",
                type: 'GET',
                data: { school_id: schoolId },
                success: function(data) {
                    $.each(data, function(index, unit) {
                        $teachingUnitSelect.append(new Option(unit.name + ' (' + unit.code + ')', unit.id, false, false));
                    });
                    $teachingUnitSelect.trigger('change');
                }
            });
        }
    });
});
</script>