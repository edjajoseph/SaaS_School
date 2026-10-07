<form method="POST" action="{{ route('school-subjects.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            @if(isset($isAdmin) && $isAdmin)
                <!-- Sélection de l'Établissement (Mode Admin) -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">
                            <i class="fa fa-building text-primary mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                        </label>
                        <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un établissement --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $selectedSchoolId ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
            @else
                <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
            @endif

            <!-- Sélection de la Matière Principale -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Matière du Référentiel") }} <span class="text-danger">*</span>
                    </label>
                    <select name="subject_id" class="form-control form-control-alternative @error('subject_id') is-invalid @enderror" required>
                        <option value="">-- Choisir une matière --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Unité d'Enseignement -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-layer-group text-primary mr-1"></i> {{ __("Unité d'Enseignement (UE)") }}
                    </label>
                    <select name="teaching_unit_id" class="form-control form-control-alternative @error('teaching_unit_id') is-invalid @enderror">
                        <option value="">-- Aucune UE --</option>
                        @foreach($teachingUnits as $tu)
                            <option value="{{ $tu->id }}" {{ old('teaching_unit_id') == $tu->id ? 'selected' : '' }}>
                                {{ $tu->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teaching_unit_id')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom Personnalisé -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-pencil text-primary mr-1"></i> {{ __("Nom personnalisé") }}
                    </label>
                    <input type="text" name="custom_name" class="form-control form-control-alternative @error('custom_name') is-invalid @enderror" value="{{ old('custom_name') }}" placeholder="Ex: Algorithmique Avancée L1">
                    @error('custom_name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Code Interne -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code Interne") }}
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ex: INF-101">
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Crédits -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-star text-primary mr-1"></i> {{ __("Crédits ECTS") }}
                    </label>
                    <input type="number" step="0.5" name="credits" class="form-control form-control-alternative @error('credits') is-invalid @enderror" value="{{ old('credits', 0) }}">
                    @error('credits')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Coefficient -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-primary mr-1"></i> {{ __("Coefficient") }}
                    </label>
                    <input type="number" step="0.25" name="coefficient" class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', 1) }}">
                    @error('coefficient')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Couleur -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-paint-brush text-primary mr-1"></i> {{ __("Couleur Planning") }}
                    </label>
                    <input type="color" name="color_code" class="form-control form-control-alternative @error('color_code') is-invalid @enderror h-auto" value="{{ old('color_code', '#3B82F6') }}">
                    @error('color_code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Volume Horaire -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures CM") }}</label>
                    <input type="number" name="hours_cm" class="form-control form-control-alternative @error('hours_cm') is-invalid @enderror" value="{{ old('hours_cm', 0) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TD") }}</label>
                    <input type="number" name="hours_td" class="form-control form-control-alternative @error('hours_td') is-invalid @enderror" value="{{ old('hours_td', 0) }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Heures TP") }}</label>
                    <input type="number" name="hours_tp" class="form-control form-control-alternative @error('hours_tp') is-invalid @enderror" value="{{ old('hours_tp', 0) }}">
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="checkbox-fade fade-in-primary">
                    <label for="is_active_create_ss" class="font-weight-bold text-dark">
                        <input type="checkbox" name="is_active" id="is_active_create_ss" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span>{{ __("Activer immédiatement cette matière pour l'établissement") }}</span>
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