<div class="modal-header">
    <h4 class="modal-title"><i class="fa fa-edit text-success"></i> {{ __("MODIFICATION DE LA PERMISSION") }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form role="form" method="POST" action="{{ route('permissions.update', $permission->id) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body">
        {{-- Alertes globales pour erreurs ou succès --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            {{-- Champ Module --}}
            <div class="form-group col-md-4 mb-3">
                <label class="form-label"><b>{{ __("Module") }} <span class="text-danger">*</span></b></label> 
                <input type="text" name="module" class="form-control @error('module') is-invalid @enderror" value="{{ old('module', $permission->module) }}" placeholder="ex: Gestion des Accès" required>
                @error('module')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            {{-- Champ Fonctionnalité --}}
            <div class="form-group col-md-4 mb-3">
                <label class="form-label"><b>{{ __("Fonctionnalité") }} <span class="text-danger">*</span></b></label> 
                <input type="text" name="feature" class="form-control @error('feature') is-invalid @enderror" value="{{ old('feature', $permission->feature) }}" placeholder="ex: Rôles" required>
                @error('feature')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            {{-- Champ Action --}}
            <div class="form-group col-md-4 mb-3">
                <label class="form-label"><b>{{ __("Action") }} <span class="text-danger">*</span></b></label> 
                <select name="action" class="form-control @error('action') is-invalid @enderror" required>
                    <option value="">-- Sélectionnez l'action --</option>
                    <option value="access" {{ old('action', $currentAction ?? '') == 'access' ? 'selected' : '' }}>Access (Accès global au module)</option>
                    <option value="show" {{ old('action', $currentAction ?? '') == 'show' ? 'selected' : '' }}>Show (Consulter / Lister)</option>
                    <option value="create" {{ old('action', $currentAction ?? '') == 'create' ? 'selected' : '' }}>Create (Créer / Ajouter)</option>
                    <option value="update" {{ old('action', $currentAction ?? '') == 'update' ? 'selected' : '' }}>Update (Modifier / Éditer)</option>
                    <option value="delete" {{ old('action', $currentAction ?? '') == 'delete' ? 'selected' : '' }}>Delete (Supprimer)</option>
                </select>
                @error('action')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        {{-- Information sur le nom technique généré --}}
        <div class="alert alert-light border py-2 mb-3">
            <small class="text-muted">
                <i class="fa fa-info-circle"></i> {{ __("Identifiant technique actuel en BDD") }} : 
                <code class="font-weight-bold text-dark">{{ $permission->name }}</code>
            </small>
        </div>

        {{-- Champ Libellé d'affichage --}}
        <div class="form-group mb-3">
            <label class="form-label"><b>{{ __("Libellé d'affichage (Display Name)") }}</b></label> 
            <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name', $permission->display_name) }}" placeholder="ex: Modifier un rôle">
            @error('display_name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Champ Description --}}
        <div class="form-group mb-3">
            <label class="form-label"><b>{{ __("Description") }}</b></label> 
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Description de la permission">{{ old('description', $permission->description) }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">
            <i class="fa fa-times"></i> {{ __('Annuler') }}
        </button>
        <button class="btn btn-success btn-rounded" type="submit">
            <strong><i class="fa fa-pencil"></i>&nbsp; {{ __('Modifier') }}</strong>
        </button>
    </div>
</form>