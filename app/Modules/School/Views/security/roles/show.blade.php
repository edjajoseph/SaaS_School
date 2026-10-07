<div class="p-3">
    <div class="row">
        <!-- Status Header -->
        <div class="col-md-12 mb-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-info">
                <i class="fa fa-shield-alt mr-2"></i>{{ __("FICHE DU RÔLE") }}
            </h5>
            <span class="badge badge-primary px-3 py-2"><i class="fa fa-tag mr-1"></i> {{ $role->name }}</span>
        </div>

        <!-- Section 1: Informations Générales -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-info-circle text-info mr-1"></i> {{ __("Informations Générales") }}</h6>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">{{ __("Libellé") }}</small>
                            <strong class="text-dark">{{ $role->display_name ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">{{ __("Description") }}</small>
                            <span class="text-dark">{{ $role->description ?? __('Aucune description.') }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block">{{ __("Date de Création") }}</small>
                            <span class="text-dark"><i class="fa fa-calendar-alt text-secondary mr-1"></i> {{ $role->created_at ? $role->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block">{{ __("Dernière Modification") }}</small>
                            <span class="text-dark"><i class="fa fa-history text-secondary mr-1"></i> {{ $role->updated_at ? $role->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Permissions Attribuées -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-key text-warning mr-1"></i> {{ __("Permissions accordées") }}</h6>
                    <span class="badge badge-info">{{ $role->permissions->count() }} permissions</span>
                </div>
                <div class="card-body py-3">
                    @if($role->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-light border border-secondary text-dark px-2 py-1 mr-1 mb-1 font-weight-normal">
                                    <i class="fa fa-check text-success mr-1"></i> {{ $permission->display_name ?? $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0 py-2">
                            <i class="fa fa-exclamation-triangle mr-1"></i> {{ __("Ce rôle ne possède actuellement aucune permission.") }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light d-flex justify-content-end">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>