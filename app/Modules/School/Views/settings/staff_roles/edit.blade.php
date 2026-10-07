<form method="POST" action="{{ route('settings.rh.staff-roles.update', $staffRole) }}" autocomplete="off">
    @csrf @method('PUT')
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-barcode text-success mr-1"></i> Code</label>
                    <input type="text" name="code" class="form-control form-control-alternative" value="{{ old('code', $staffRole->code) }}">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-briefcase text-success mr-1"></i> Libellé <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-alternative" value="{{ old('name', $staffRole->name) }}" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Fermer</button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm"><i class="fa fa-sync-alt mr-1"></i> Mettre à jour</button>
    </div>
</form>