<form method="POST" action="{{ route('organisation.classes.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Sélection de l'École / Établissement -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-primary mr-1"></i> {{ __("Établissement / École") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $currentSchoolId ?? '') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Niveau d'enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-primary mr-1"></i> {{ __("Niveau d'enseignement") }} <span class="text-danger">*</span>
                    </label>
                    <select name="level_id" 
                            id="level_id_create" 
                            class="form-control form-control-alternative @error('level_id') is-invalid @enderror" 
                            required 
                            onchange="updateTuitionFeeFromLevel(this)">
                        <option value="" data-tuition="0">{{ __("Sélectionnez un niveau") }}</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" 
                                    data-tuition="{{ $level->tuition_fee ?? 0 }}"
                                    {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }} ({{ $level->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-primary mr-1"></i> {{ __("Filière / Séries") }} <span class="text-danger">*</span>
                    </label>
                    <select name="serie_id" 
                            id="serie_id_create" 
                            class="form-control form-control-alternative @error('serie_id') is-invalid @enderror" 
                            required 
                            onchange="updateTuitionFeeFromLevel(this)">
                        <option value="" data-tuition="0">{{ __("Sélectionnez une filière") }}</option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" 
                                    data-tuition="{{ $serie->tuition_fee ?? 0 }}"
                                    {{ old('serie_id') == $serie->id ? 'selected' : '' }}>
                                {{ $serie->name }} ({{ $serie->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('serie_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Code de la classe -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code de la classe") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code') }}" 
                           placeholder="Ex: TLE_D1" 
                           required>
                    @error('code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Nom de la classe -->
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-primary mr-1"></i> {{ __("Nom de la classe") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Ex: Terminale D1" 
                           required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Capacité d'élèves -->
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> {{ __("Capacité") }}
                    </label>
                    <input type="number" 
                           name="capacity" 
                           class="form-control form-control-alternative @error('capacity') is-invalid @enderror" 
                           value="{{ old('capacity', 50) }}" 
                           min="1">
                    @error('capacity')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Frais de scolarité appliqués -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-money text-primary mr-1"></i> {{ __("Frais de scolarité (FCFA)") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="tuition_fee" 
                           id="tuition_fee_create" 
                           class="form-control form-control-alternative @error('tuition_fee') is-invalid @enderror" 
                           value="{{ old('tuition_fee', 0) }}" 
                           min="0"
                           step="500"
                           required>
                    <small class="form-text text-muted">
                        <i class="fa fa-info-circle mr-1"></i> Pré-rempli automatiquement selon le niveau sélectionné.
                    </small>
                    @error('tuition_fee')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Statut d'activation -->
            <div class="col-md-12 mt-2">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           class="form-control" 
                           id="is_active_class_create" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark mb-0" for="is_active_class_create" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Activer immédiatement cette classe") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('organisation.classes.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
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

<script>
    function updateTuitionFeeFromLevel(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const defaultTuition = selectedOption ? selectedOption.getAttribute('data-tuition') : 0;
        const tuitionInput = document.getElementById('tuition_fee_create');
        if (tuitionInput) {
            tuitionInput.value = defaultTuition || 0;
        }
    }
</script>