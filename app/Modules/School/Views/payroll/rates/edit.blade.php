<form method="POST" action="{{ route('school.accounting.teacher-rates.update', $subjectRate->id) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <!-- Informations résumées sur le cours -->
        <div class="alert alert-info border-info mb-4">
            <div class="row">
                <div class="col-md-4">
                    <strong>Enseignant :</strong> {{ $subjectRate->staff->personne->nom_complet ?? 'N/A' }}
                </div>
                <div class="col-md-4">
                    <strong>Classe :</strong> {{ $subjectRate->schoolClass->name ?? 'N/A' }}
                </div>
                <div class="col-md-4">
                    <strong>Matière :</strong> {{ $subjectRate->subject->subject->name ?? 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Section 1 : Volumes Horaires Prévus -->
        <h6 class="font-weight-bold text-dark mb-3">
            <i class="fa fa-clock-o text-primary mr-1"></i> {{ __("VOLUMES HORAIRES PRÉVUS (HEURES)") }}
        </h6>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Vol. CM (h)") }}</label>
                    <input type="number" 
                           name="volume_cm" 
                           class="form-control form-control-alternative @error('volume_cm') is-invalid @enderror" 
                           value="{{ old('volume_cm', $subjectRate->volume_cm) }}" 
                           min="0">
                    @error('volume_cm')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Vol. TD (h)") }}</label>
                    <input type="number" 
                           name="volume_td" 
                           class="form-control form-control-alternative @error('volume_td') is-invalid @enderror" 
                           value="{{ old('volume_td', $subjectRate->volume_td) }}" 
                           min="0">
                    @error('volume_td')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Vol. TP (h)") }}</label>
                    <input type="number" 
                           name="volume_tp" 
                           class="form-control form-control-alternative @error('volume_tp') is-invalid @enderror" 
                           value="{{ old('volume_tp', $subjectRate->volume_tp) }}" 
                           min="0">
                    @error('volume_tp')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Vol. Exam (h)") }}</label>
                    <input type="number" 
                           name="volume_examen" 
                           class="form-control form-control-alternative @error('volume_examen') is-invalid @enderror" 
                           value="{{ old('volume_examen', $subjectRate->volume_examen) }}" 
                           min="0">
                    @error('volume_examen')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>

        <hr>

        <!-- Section 2 : Taux Horaires -->
        <h6 class="font-weight-bold text-dark mb-3">
            <i class="fa fa-money text-success mr-1"></i> {{ __("TAUX HORAIRES NÉGOCIÉS (FCFA)") }}
        </h6>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Taux CM") }} <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_cm" 
                           class="form-control form-control-alternative @error('rate_cm') is-invalid @enderror" 
                           value="{{ old('rate_cm', $subjectRate->rate_cm) }}" 
                           min="0" step="500" required>
                    @error('rate_cm')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Taux TD") }} <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_td" 
                           class="form-control form-control-alternative @error('rate_td') is-invalid @enderror" 
                           value="{{ old('rate_td', $subjectRate->rate_td) }}" 
                           min="0" step="500" required>
                    @error('rate_td')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Taux TP") }} <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_tp" 
                           class="form-control form-control-alternative @error('rate_tp') is-invalid @enderror" 
                           value="{{ old('rate_tp', $subjectRate->rate_tp) }}" 
                           min="0" step="500" required>
                    @error('rate_tp')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Taux Examen") }} <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="rate_examen" 
                           class="form-control form-control-alternative @error('rate_examen') is-invalid @enderror" 
                           value="{{ old('rate_examen', $subjectRate->rate_examen) }}" 
                           min="0" step="500" required>
                    @error('rate_examen')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('school.accounting.teacher-rates.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Enregistrer l\'ajustement') }}
        </button>
    </div>
</form>