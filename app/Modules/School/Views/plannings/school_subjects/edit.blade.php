<form method="POST" action="{{ route('school-subjects.update', $schoolSubject) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Unité d'Enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-layer-group text-success mr-1"></i> {{ __("Unité d'Enseignement (UE)") }}
                    </label>
                    <select name="teaching_unit_id" class="form-control form-control-alternative @error('teaching_unit_id') is-invalid @enderror">
                        <option value="">-- Aucune UE --</option>
                        @foreach($teachingUnits as $tu)
                            <option value="{{ $tu->id }}" {{ old('teaching_unit_id', $schoolSubject->teaching_unit_id) == $tu->id ? 'selected' : '' }}>
                                {{ $tu->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teaching_unit_id')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom Personnalisé -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-pencil text-success mr-1"></i> {{ __("Nom personnalisé") }}
                    </label>
                    <input type="text" name="custom_name" class="form-control form-control-alternative @error('custom_name') is-invalid @enderror" value="{{ old('custom_name', $schoolSubject->custom_name) }}">
                    @error('custom_name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Code Interne -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code Interne") }}
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $schoolSubject->code) }}">
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Crédits -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-star text-success mr-1"></i> {{ __("Crédits ECTS") }}
                    </label>
                    <input type="number" step="0.5" name="credits" class="form-control form-control-alternative @error('credits') is-invalid @enderror" value="{{ old('credits', $schoolSubject->credits) }}">
                </div>
            </div>

            <!-- Coefficient -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-success mr-1"></i> {{ __("Coefficient") }}
                    </label>
                    <input type="number" step="0.25" name="coefficient" class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', $schoolSubject->coefficient) }}">
                </div>
            </div>

            <!-- Couleur -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-paint-brush text-success mr-1"></i> {{ __("Couleur Planning") }}
                    </label>
                    <input type="color" name="color_code" class="form-control form-control-alternative @error('color_code') is-invalid @enderror h-auto" value="{{ old('color_code', $schoolSubject->color_code ?? '#3B82F6') }}">
                </div>
            </div>

            <!-- Volume Horaire -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures CM") }}</label>
                    <input type="number" name="hours_cm" class="form-control form-control-alternative @error('hours_cm') is-invalid @enderror" value="{{ old('hours_cm', $schoolSubject->hours_cm) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TD") }}</label>
                    <input type="number" name="hours_td" class="form-control form-control-alternative @error('hours_td') is-invalid @enderror" value="{{ old('hours_td', $schoolSubject->hours_td) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TP") }}</label>
                    <input type="number" name="hours_tp" class="form-control form-control-alternative @error('hours_tp') is-invalid @enderror" value="{{ old('hours_tp', $schoolSubject->hours_tp) }}">
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active_ss_edit" value="1" {{ old('is_active', $schoolSubject->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="is_active_ss_edit">
                        {{ __("Matière active dans l'établissement") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>