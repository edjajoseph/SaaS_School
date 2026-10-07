<div class="ibox-content">
    {{-- 1. En-tête / Informations générales du rôle --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h4 class="text-primary mb-3">
                        <i class="fa fa-shield-alt"></i> {{ $role->display_name ?? $role->name }}
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-tag"></i> {{ __("Nom technique") }} :</strong> 
                            <code class="text-dark">{{ $role->name }}</code>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-font"></i> {{ __("Libellé") }} :</strong> 
                            {{ $role->display_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-12 mb-2">
                            <strong><i class="fa fa-align-left"></i> {{ __("Description") }} :</strong><br>
                            <span class="text-muted">{{ $role->description ?? __('Aucune description disponible.') }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <i class="fa fa-calendar-alt"></i> {{ __("Créé le") }} : 
                                {{ $role->created_at ? $role->created_at->format('d/m/Y à H:i') : 'N/A' }}
                            </small>
                        </div>
                        <div class="col-md-6 mt-2">
                            <small class="text-muted">
                                <i class="fa fa-history"></i> {{ __("Dernière modification") }} : 
                                {{ $role->updated_at ? $role->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- 2. Section des Permissions rattachées --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="mb-3">
                <i class="fa fa-key"></i> {{ __("Permissions accordées") }} 
                <span class="badge badge-primary rounded-pill">{{ $role->permissions->count() }}</span>
            </h5>

            @if($role->permissions->count() > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span class="badge badge-info p-2 mb-2 mr-2" style="font-size: 0.9rem;">
                            <i class="fa fa-check-circle text-success"></i> 
                            {{ $permission->display_name ?? $permission->name }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fa fa-exclamation-circle"></i> {{ __("Ce rôle ne possède actuellement aucune permission.") }}
                </div>
            @endif
        </div>
    </div>

    <hr>

    {{-- 3. Actions / Boutons de navigation --}}
    <div class="d-flex justify-content-between align-items-center">
        <a class="btn btn-secondary btn-rounded" href="{{ route('roles.index') }}">
            <i class="fa fa-arrow-left"></i>&nbsp; {{ __('Retour à la liste') }}
        </a>

        <div>
            <a class="btn btn-primary btn-rounded" href="{{ route('roles.edit', $role->id) }}">
                <i class="fa fa-pencil"></i>&nbsp; {{ __('Modifier ce rôle') }}
            </a>
        </div>
    </div>
</div>