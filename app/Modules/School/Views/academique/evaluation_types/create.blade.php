<form method="POST" action="{{ route('academic.evaluation-types.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="row">
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
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Ex: CC" required>
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Libellé du type") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Controle Continu" required>
                    @error('name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-balance-scale text-primary mr-1"></i> {{ __("Poids par défaut") }}
                    </label>
                    <input type="number" step="0.01" name="default_weight" class="form-control form-control-alternative @error('default_weight') is-invalid @enderror" value="{{ old('default_weight', 1.00) }}">
                    @error('default_weight') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center mt-3">
                <div class="form-check checkbox-def font-weight-bold text-dark">
                    <input type="checkbox" name="is_catch_up" class="form-check-input" id="is_catch_up" value="1" {{ old('is_catch_up') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_catch_up">
                        <i class="fa fa-undo text-warning mr-1"></i> {{ __("S'agit-il d'une session de rattrapage ?") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

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