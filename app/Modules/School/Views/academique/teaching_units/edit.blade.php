<form method="POST" action="{{ route('academic.teaching-units.update', $teachingUnit) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Établissement -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-secondary mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="select2-modal @error('school_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un établissement") }}" required>
                        <option value=""></option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $teachingUnit->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Niveau -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-secondary mr-1"></i> {{ __("Niveau") }} <span class="text-danger">*</span>
                    </label>
                    <select name="level_id" class="select2-modal @error('level_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un niveau") }}" required>
                        <option value=""></option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id', $teachingUnit->level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Filière / Série -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tags text-secondary mr-1"></i> {{ __("Filière / Série") }}
                    </label>
                    <select name="serie_id" class="select2-modal @error('serie_id') is-invalid @enderror" data-placeholder="{{ __("Sélectionner une filière") }}">
                        <option value=""></option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" {{ old('serie_id', $teachingUnit->serie_id) == $serie->id ? 'selected' : '' }}>
                                {{ $serie->name }} ({{ $serie->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('serie_id') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-secondary mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $teachingUnit->code) }}" required>
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Nom de l'UE -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-secondary mr-1"></i> {{ __("Intitulé de l'UE") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $teachingUnit->name) }}" required>
                    @error('name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Type (Enum Combo) -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tag text-secondary mr-1"></i> {{ __("Type") }}
                    </label>
                    <select name="type" class="select2-modal @error('type') is-invalid @enderror" data-placeholder="{{ __("Sélectionner un type") }}">
                        <option value=""></option>
                        <option value="fundamental" {{ old('type', $teachingUnit->type) == 'fundamental' ? 'selected' : '' }}>Fondamentale</option>
                        <option value="transversal" {{ old('type', $teachingUnit->type) == 'transversal' ? 'selected' : '' }}>Transversale</option>
                        <option value="optional" {{ old('type', $teachingUnit->type) == 'optional' ? 'selected' : '' }}>Optionnelle</option>
                        <option value="professional" {{ old('type', $teachingUnit->type) == 'professional' ? 'selected' : '' }}>Professionnelle</option>
                    </select>
                    @error('type') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Crédits -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-star text-secondary mr-1"></i> {{ __("Crédits ECTS") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="credits" class="form-control form-control-alternative @error('credits') is-invalid @enderror" value="{{ old('credits', $teachingUnit->credits) }}" min="1" required>
                    @error('credits') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <!-- Coefficient -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-secondary mr-1"></i> {{ __("Coefficient") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" step="0.01" name="coefficient" class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', $teachingUnit->coefficient) }}" min="0" required>
                    @error('coefficient') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('academic.teaching-units.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>

<style>
/* Reset Select2 pour supprimer le fond bleu et homogénéiser */
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
});
</script>