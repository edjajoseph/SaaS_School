<div class="modal-body p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border mb-0">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted font-weight-bold d-block">{{ __('Code') }}</label>
                            <span class="badge badge-primary px-3 py-2 font-14">{{ $serie->code }}</span>
                        </div>
                        <div class="col-md-5">
                            <label class="text-muted font-weight-bold d-block">{{ __('Nom de la série') }}</label>
                            <span class="font-weight-bold text-dark font-16">{{ $serie->name }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted font-weight-bold d-block">{{ __('Statut') }}</label>
                            @if($serie->is_active)
                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>{{ __('Actif') }}</span>
                            @else
                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>{{ __('Inactif') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="form-group mb-0">
                        <label class="text-muted font-weight-bold d-block">{{ __('Description') }}</label>
                        <p class="text-dark bg-light p-3 rounded mb-0" style="min-height: 80px;">
                            {{ $serie->description ?? __('Aucune description disponible.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>