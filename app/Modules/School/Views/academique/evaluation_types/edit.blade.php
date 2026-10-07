<form method="POST" action="{{ route('academic.evaluation-types.update', $evaluationType) }}" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $evaluationType->code) }}" required>
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-success mr-1"></i> {{ __("Libellé du type") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $evaluationType->name) }}" required>
                    @error('name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-balance-scale text-success mr-1"></i> {{ __("Poids par défaut") }}
                    </label>
                    <input type="number" step="0.01" name="default_weight" class="form-control form-control-alternative @error('default_weight') is-invalid @enderror" value="{{ old('default_weight', $evaluationType->default_weight) }}">
                    @error('default_weight') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center mt-3">
                <div class="form-check checkbox-def font-weight-bold text-dark">
                    <input type="checkbox" name="is_catch_up" class="form-check-input" id="is_catch_up" value="1" {{ old('is_catch_up', $evaluationType->is_catch_up) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_catch_up">
                        <i class="fa fa-undo text-warning mr-1"></i> {{ __("Session de rattrapage") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>