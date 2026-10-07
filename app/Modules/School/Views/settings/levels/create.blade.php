<form method="POST" action="{{ route('settings.academic.levels.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Cycle d'enseignement -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-primary mr-1"></i> {{ __("Cycle d'enseignement") }} <span class="text-danger">*</span>
                    </label>
                    <select name="cycle_id" class="form-control form-control-alternative @error('cycle_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez un cycle") }}</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}" {{ old('cycle_id') == $cycle->id ? 'selected' : '' }}>
                                {{ $cycle->name }} ({{ $cycle->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('cycle_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Code du niveau -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           class="form-control form-control-alternative @error('code') is-invalid @enderror" 
                           value="{{ old('code') }}" 
                           placeholder="Ex: TLE_D" 
                           required>
                    @error('code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Ordre de séquence -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-sort-numeric-asc text-primary mr-1"></i> {{ __("Ordre") }}
                    </label>
                    <input type="number" 
                           name="sequence_order" 
                           class="form-control form-control-alternative @error('sequence_order') is-invalid @enderror" 
                           value="{{ old('sequence_order', 1) }}" 
                           min="1">
                    @error('sequence_order')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Nom du niveau -->
            <div class="col-md-7">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Nom du niveau") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-alternative @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="Ex: Terminale D" 
                           required>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Frais de scolarité par défaut -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-money text-primary mr-1"></i> {{ __("Scolarité par défaut (FCFA)") }}
                    </label>
                    <input type="number" 
                           name="tuition_fee" 
                           class="form-control form-control-alternative @error('tuition_fee') is-invalid @enderror" 
                           value="{{ old('tuition_fee', 0) }}" 
                           min="0"
                           step="500"
                           placeholder="Ex: 150000">
                    @error('tuition_fee')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Option Examen Officiel -->
            <div class="col-md-12 mb-3">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                        name="has_exam" 
                        value="1" 
                        class="form-control" 
                        id="has_exam_create" 
                        style="width: 18px; height: 18px; cursor: pointer;"
                        {{ old('has_exam') ? 'checked' : '' }}
                        onchange="toggleExamNameField(this.checked, 'exam_name_container_create')">
                    <label class="form-check-label font-weight-bold text-dark mb-0" for="has_exam_create" style="cursor: pointer;">
                        <i class="fa fa-certificate text-warning mr-1"></i> {{ __("Ce niveau débouche sur un examen de fin de cycle / officiel") }}
                    </label>
                </div>
            </div>

            <!-- Statut d'activation -->
            <div class="col-md-12 mt-2">
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" 
                        name="is_active" 
                        value="1" 
                        class="form-control" 
                        id="is_active_create" 
                        style="width: 18px; height: 18px; cursor: pointer;"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-dark mb-0" for="is_active_create" style="cursor: pointer;">
                        <i class="fa fa-check-circle text-success mr-1"></i> {{ __("Activer immédiatement ce niveau") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
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

<script>
    function toggleExamNameField(isChecked, containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            container.style.display = isChecked ? 'block' : 'none';
        }
    }
</script>