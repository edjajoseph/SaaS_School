<form method="POST" action="{{ route('settings.rh.staff-roles.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-barcode text-primary mr-1"></i> Code</label>
                    <input type="text" name="code" class="form-control form-control-alternative" placeholder="Ex: ENS">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-briefcase text-primary mr-1"></i> Libellé du Rôle <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-alternative" placeholder="Ex: Enseignant, Administrateur" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Fermer</button>
        <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm"><i class="fa fa-save mr-1"></i> Enregistrer</button>
    </div>
</form>