<form method="POST" action="{{ route('settings.academic.degrees.store') }}" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-barcode text-primary mr-1"></i> {{ __("Code") }}</label>
                    <input type="text" name="code" class="form-control form-control-alternative" placeholder="Ex: LIC">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-book text-primary mr-1"></i> {{ __("Libellé") }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-alternative" placeholder="Ex: Licence Professionnelle" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-sort-numeric-asc text-primary mr-1"></i> {{ __("Rang/Niveau") }}</label>
                    <input type="number" name="level_rank" class="form-control form-control-alternative" value="1" min="0">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal"><i class="fa fa-times mr-1"></i> {{ __('Fermer') }}</button>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1"><i class="fa fa-refresh mr-1"></i> {{ __('Réinitialiser') }}</button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm"><i class="fa fa-save mr-1"></i> {{ __('Enregistrer') }}</button>
        </div>
    </div>
</form>