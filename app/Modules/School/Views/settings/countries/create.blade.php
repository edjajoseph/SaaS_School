<form method="POST" action="{{ route('settings.local.countries.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-flag text-primary mr-1"></i> Nom du pays <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-alternative" placeholder="Ex: Côte d'Ivoire" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-id-card text-primary mr-1"></i> Nationalité <span class="text-danger">*</span></label>
                    <input type="text" name="nationality" class="form-control form-control-alternative" placeholder="Ex: Ivoirienne" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">ISO Code (2 letters) <span class="text-danger">*</span></label>
                    <input type="text" name="iso_code_2" maxlength="2" class="form-control form-control-alternative" placeholder="Ex: CI" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">ISO Code (3 letters)</label>
                    <input type="text" name="iso_code_3" maxlength="3" class="form-control form-control-alternative" placeholder="Ex: CIV">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">Indicatif Tel</label>
                    <input type="text" name="phone_code" class="form-control form-control-alternative" placeholder="Ex: +225">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Fermer</button>
        <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm"><i class="fa fa-save mr-1"></i> Enregistrer</button>
    </div>
</form>