<div class="ibox-content">
    {{-- 1. En-tête / Informations générales de la permission --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h4 class="text-primary mb-3">
                        <i class="fa fa-key"></i> {{ $permission->display_name ?? $permission->name }}
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <strong><i class="fa fa-folder"></i> {{ __("Module") }} :</strong> <br>
                            <span class="badge badge-primary px-2 py-1">{{ $permission->module ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong><i class="fa fa-cogs"></i> {{ __("Fonctionnalité") }} :</strong> <br>
                            <span class="badge badge-info px-2 py-1">{{ $permission->feature ?? 'Non spécifiée' }}</span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong><i class="fa fa-code"></i> {{ __("Nom technique (BDD)") }} :</strong> <br>
                            <code class="text-dark font-weight-bold">{{ $permission->name }}</code>
                        </div>

                        <div class="col-md-12 my-3">
                            <strong><i class="fa fa-align-left"></i> {{ __("Description") }} :</strong><br>
                            <span class="text-muted">{{ $permission->description ?? __('Aucune description disponible pour cette permission.') }}</span>
                        </div>

                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <i class="fa fa-calendar-alt"></i> {{ __("Créée le") }} : 
                                {{ $permission->created_at ? $permission->created_at->format('d/m/Y à H:i') : 'N/A' }}
                            </small>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <i class="fa fa-history"></i> {{ __("Dernière modification") }} : 
                                {{ $permission->updated_at ? $permission->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- 2. Rôles qui possèdent cette permission --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="mb-3">
                <i class="fa fa-shield-alt"></i> {{ __("Rôles associés à cette permission") }} 
                <span class="badge badge-secondary rounded-pill">{{ $permission->roles->count() }}</span>
            </h5>

            @if($permission->roles->count() > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($permission->roles as $role)
                        <span class="badge badge-info p-2 mb-2 mr-2" style="font-size: 0.9rem;">
                            <i class="fa fa-user-shield text-primary"></i> 
                            {{ $role->display_name ?? $role->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning mb-0 py-2">
                    <small><i class="fa fa-exclamation-circle"></i> {{ __("Cette permission n'est actuellement attribuée à aucun rôle.") }}</small>
                </div>
            @endif
        </div>
    </div>

    <hr>

    {{-- 3. Boutons d'action et de navigation --}}
    <div class="d-flex justify-content-between align-items-center">
        <a class="btn btn-secondary btn-rounded" href="{{ route('permissions.index') }}">
            <i class="fa fa-arrow-left"></i>&nbsp; {{ __('Retour à la liste') }}
        </a>

        <div>
            <a class="btn btn-success btn-rounded" href="{{ route('permissions.edit', $permission->id) }}">
                <i class="fa fa-pencil"></i>&nbsp; {{ __('Modifier cette permission') }}
            </a>
        </div>
    </div>
</div>