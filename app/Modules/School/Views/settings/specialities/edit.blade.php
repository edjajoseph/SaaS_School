<form method="POST" action="{{ route('settings.rh.specialities.update', $speciality) }}" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code") }}
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $speciality->code) }}" placeholder="Ex: MATH">
                    @error('code') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-success mr-1"></i> {{ __("Libellé") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $speciality->name) }}" placeholder="Ex: Mathématiques" required>
                    @error('name') <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span> @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> {{ __('Fermer') }}</button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm"><i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}</button>
    </div>
</form>