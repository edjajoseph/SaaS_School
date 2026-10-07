<div class="p-3">
    <div class="row">
        <!-- Status Header -->
        <div class="col-md-12 mb-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-info">
                <i class="fa fa-key mr-2"></i>{{ $permission->display_name ?? $permission->name }}
            </h5>
            <code class="bg-light p-2 rounded text-dark font-weight-bold">{{ $permission->name }}</code>
        </div>

        <!-- Section 1: Information Générale -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-info-circle text-info mr-1"></i> {{ __("Propriétés Générales") }}</h6>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">{{ __("Module") }}</small>
                            <span class="badge badge-primary px-2 py-1">{{ $permission->module ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">{{ __("Fonctionnalité") }}</small>
                            <span class="badge badge-info px-2 py-1">{{ $permission->feature ?? 'Non spécifiée' }}</span>
                        </div>
                        <div class="col-md-12 my-2">
                            <small class="text-muted d-block">{{ __("Description") }}</small>
                            <span class="text-dark">{{ $permission->description ?? __('Aucune description disponible.') }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block">{{ __("Date de Création") }}</small>
                            <span class="text-dark"><i class="fa fa-calendar-alt text-secondary mr-1"></i> {{ $permission->created_at ? $permission->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted d-block">{{ __("Dernière Modification") }}</small>
                            <span class="text-dark"><i class="fa fa-history text-secondary mr-1"></i> {{ $permission->updated_at ? $permission->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Rôles Attribués -->
        <div class="col-md-12 mb-3">
            <div class="card border shadow-none mb-0">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark font-weight-bold"><i class="fa fa-user-shield text-warning mr-1"></i> {{ __("Rôles associés") }}</h6>
                    <span class="badge badge-secondary">{{ $permission->roles->count() }} rôles</span>
                </div>
                <div class="card-body py-3">
                    @if($permission->roles->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($permission->roles as $role)
                                <span class="badge badge-light border border-secondary text-dark px-2 py-1 mr-1 mb-1 font-weight-normal">
                                    <i class="fa fa-shield-alt text-primary mr-1"></i> {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0 py-2">
                            <i class="fa fa-exclamation-triangle mr-1"></i> {{ __("Cette permission n'est actuellement attribuée à aucun rôle.") }}
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