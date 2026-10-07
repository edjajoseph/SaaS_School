<form method="POST" action="{{ route('organisation.classes.update', $class) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Sélection de l'École / Établissement -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-university text-success mr-1"></i> {{ __("Établissement / École") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $class->school_id) == $school->id ? 'selected' : '' }}>
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
                        <i class="fa fa-graduation-cap text-success mr-1"></i> {{ __("Niveau d'enseignement") }} <span class="text-danger">*</span>
                    </label>
                    <select name="level_id" 
                            id="level_id_edit" 
                            class="form-control form-control-alternative @error('level_id') is-invalid @enderror" 
                            required 
                            onchange="updateTuitionFeeFromLevelEdit(this)">
                        <option value="" data-tuition="0">{{ __("Sélectionnez un niveau") }}</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" 
                                    data-tuition="{{ $level->tuition_fee ?? 0 }}"
                                    {{ old('level_id', $class->level_id) == $level->id ? 'selected' : '' }}>
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

            <!-- Filière / Séries (Nouveau combo intégré) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tags text-success mr-1"></i> {{ __("Filière / Séries") }}
                    </label>
                    <select name="serie_id" class="form-control form-control-alternative @error('serie_id') is-invalid @enderror">
                        <option value="">{{ __("Sélectionnez une filière") }}</option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" {{ old('serie_id', $class->serie_id) == $serie->id ? 'selected' : '' }}>
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
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code de la classe") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code', $class->code) }}" 
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
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-success mr-1"></i> {{ __("Nom de la classe") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name', $class->name) }}" 
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
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-success mr-1"></i> {{ __("Capacité (Élèves)") }}
                    </label>
                    <input type="number" 
                           name="capacity" 
                           class="form-control form-control-alternative @error('capacity') is-invalid @enderror" 
                           value="{{ old('capacity', $class->capacity) }}" 
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
                        <i class="fa fa-money text-success mr-1"></i> {{ __("Frais de scolarité (FCFA)") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           name="tuition_fee" 
                           id="tuition_fee_edit" 
                           class="form-control form-control-alternative @error('tuition_fee') is-invalid @enderror" 
                           value="{{ old('tuition_fee', $class->tuition_fee) }}" 
                           min="0"
                           step="500"
                           required>
                    <small class="form-text text-muted">
                        <i class="fa fa-info-circle mr-1"></i> Saisissez la valeur ou laissez-la se mettre à jour au changement de niveau.
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
                           id="is_active_class_edit" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           {{ old('is_active', $class->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark mb-0" for="is_active_class_edit" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Classe active") }}
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
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>

<script>
    function updateTuitionFeeFromLevelEdit(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const defaultTuition = selectedOption ? selectedOption.getAttribute('data-tuition') : 0;
        const tuitionInput = document.getElementById('tuition_fee_edit');
        if (tuitionInput && defaultTuition > 0) {
            tuitionInput.value = defaultTuition;
        }
    }
</script>