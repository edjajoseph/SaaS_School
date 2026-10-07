<form method="POST" action="{{ route('settings.academic.subjects.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code / Sigle") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ex: MATH-GEN" required>
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom de la Matière -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Nom de la matière") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Mathématiques Générales" required>
                    @error('name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-primary mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" rows="3" class="form-control form-control-alternative @error('description') is-invalid @enderror" placeholder="Saisir un bref descriptif du cours...">{{ old('description') }}</textarea>
                    @error('description')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="col-md-12 mt-2">
                <div class="checkbox-fade fade-in-primary">
                    <label for="is_active_create" class="font-weight-bold text-dark">
                        <input type="checkbox" name="is_active" id="is_active_create" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span>{{ __("Activer cette matière dans le référentiel") }}</span>
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