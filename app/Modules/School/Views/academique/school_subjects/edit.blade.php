<form method="POST" action="{{ route('academic.school-subjects.update', $schoolSubject) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Unité d'Enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-secondary mr-1"></i> {{ __("Unité d'Enseignement (UE)") }}
                    </label>
                    <select name="teaching_unit_id" class="select2-modal @error('teaching_unit_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner une UE") }}">
                        <option value=""></option>
                        @foreach($teachingUnits as $unit)
                            <option value="{{ $unit->id }}" {{ old('teaching_unit_id', $schoolSubject->teaching_unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('teaching_unit_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Matière Générale / Référence (Ajustable lors de l'édition) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-list text-secondary mr-1"></i> {{ __("Matière de référence") }}
                    </label>
                    <select name="subject_id" id="subject_id_select_edit" class="select2-modal @error('subject_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner la matière") }}">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                    data-name="{{ $subject->name }}" 
                                    data-code="{{ $subject->code }}"
                                    {{ $schoolSubject->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} {{ $subject->code ? '('.$subject->code.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Intitulé Personnalisé -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-edit text-secondary mr-1"></i> {{ __("Nom Personnalisé") }}
                    </label>
                    <input type="text" name="custom_name" id="custom_name_input_edit" class="form-control form-control-alternative @error('custom_name') is-invalid @enderror" value="{{ old('custom_name', $schoolSubject->custom_name) }}" placeholder="Ex: Algorithmique Avancée">
                    @error('custom_name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Code & Couleur -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-secondary mr-1"></i> {{ __("Code de la matière") }}
                    </label>
                    <input type="text" name="code" id="code_input_edit" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $schoolSubject->code) }}" placeholder="Ex: INF-201">
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-paint-brush text-secondary mr-1"></i> {{ __("Couleur d'affichage") }}
                    </label>
                    <input type="color" name="color_code" class="form-control form-control-alternative @error('color_code') is-invalid @enderror" value="{{ old('color_code', $schoolSubject->color_code) }}">
                    @error('color_code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Crédits & Coefficient -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-star text-secondary mr-1"></i> {{ __("Crédits ECTS") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="credits" class="form-control form-control-alternative @error('credits') is-invalid @enderror" value="{{ old('credits', $schoolSubject->credits) }}" min="0" step="0.5" required>
                    @error('credits') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-secondary mr-1"></i> {{ __("Coefficient") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="coefficient" class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', $schoolSubject->coefficient) }}" min="0" step="0.1" required>
                    @error('coefficient') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Heures CM, TD, TP -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures CM") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_cm" class="form-control @error('hours_cm') is-invalid @enderror" value="{{ old('hours_cm', $schoolSubject->hours_cm) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TD") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_td" class="form-control @error('hours_td') is-invalid @enderror" value="{{ old('hours_td', $schoolSubject->hours_td) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TP") }} <span class="text-danger">*</span></label>
                    <input type="number" name="hours_tp" class="form-control @error('hours_tp') is-invalid @enderror" value="{{ old('hours_tp', $schoolSubject->hours_tp) }}" min="0" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active_edit" value="1" {{ old('is_active', $schoolSubject->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold" for="is_active_edit">Activer cette matière pour l'établissement</label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" onclick="$(this).closest('.modal').modal('hide');">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
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

    // Auto-remplissage en cas de réinitialisation/changement de matière
    $('#subject_id_select_edit').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var name = selectedOption.data('name') || '';
        var code = selectedOption.data('code') || '';

        $('#custom_name_input_edit').val(name);
        $('#code_input_edit').val(code);
    });
});
</script>