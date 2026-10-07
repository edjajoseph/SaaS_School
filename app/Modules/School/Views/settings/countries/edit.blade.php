<form method="POST" action="{{ route('settings.local.countries.update', $country) }}" autocomplete="off">
    @csrf @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-flag text-success mr-1"></i> Nom du pays <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-alternative" value="{{ old('name', $country->name) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-id-card text-success mr-1"></i> Nationalité <span class="text-danger">*</span></label>
                    <input type="text" name="nationality" class="form-control form-control-alternative" value="{{ old('nationality', $country->nationality) }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">ISO Code 2 <span class="text-danger">*</span></label>
                    <input type="text" name="iso_code_2" maxlength="2" class="form-control form-control-alternative" value="{{ old('iso_code_2', $country->iso_code_2) }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">ISO Code 3</label>
                    <input type="text" name="iso_code_3" maxlength="3" class="form-control form-control-alternative" value="{{ old('iso_code_3', $country->iso_code_3) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">Indicatif Tel</label>
                    <input type="text" name="phone_code" class="form-control form-control-alternative" value="{{ old('phone_code', $country->phone_code) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Fermer</button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm"><i class="fa fa-sync-alt mr-1"></i> Mettre à jour</button>
    </div>
</form>