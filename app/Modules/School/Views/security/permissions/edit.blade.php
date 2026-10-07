<form method="POST" action="{{ route('access.permissions.update', $permission->id) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-12">
                <h6 class="font-weight-bold text-success mb-3">
                    <i class="fa fa-info-circle mr-1"></i> {{ __("Propriétés de la Permission") }}
                </h6>
            </div>

            <!-- Module -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-folder text-success mr-1"></i> {{ __("Module") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="module" class="form-control form-control-alternative @error('module') is-invalid @enderror" value="{{ old('module', $permission->module) }}" placeholder="Ex: Gestion des Accès" required>
                    @error('module')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Fonctionnalité -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-cogs text-success mr-1"></i> {{ __("Fonctionnalité") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="feature" class="form-control form-control-alternative @error('feature') is-invalid @enderror" value="{{ old('feature', $permission->feature) }}" placeholder="Ex: Rôles" required>
                    @error('feature')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Action -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-bolt text-success mr-1"></i> {{ __("Action") }} <span class="text-danger">*</span>
                    </label>
                    <select name="action" class="form-control form-control-alternative @error('action') is-invalid @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="access" {{ old('action', $currentAction ?? '') == 'access' ? 'selected' : '' }}>Access (Accès global)</option>
                        <option value="show" {{ old('action', $currentAction ?? '') == 'show' ? 'selected' : '' }}>Show (Consulter / Lister)</option>
                        <option value="create" {{ old('action', $currentAction ?? '') == 'create' ? 'selected' : '' }}>Create (Créer / Ajouter)</option>
                        <option value="update" {{ old('action', $currentAction ?? '') == 'update' ? 'selected' : '' }}>Update (Modifier / Éditer)</option>
                        <option value="delete" {{ old('action', $currentAction ?? '') == 'delete' ? 'selected' : '' }}>Delete (Supprimer)</option>
                    </select>
                    @error('action')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Note d'information BDD -->
            <div class="col-md-12">
                <div class="alert alert-secondary py-2 mb-3">
                    <small class="text-dark">
                        <i class="fa fa-info-circle text-info mr-1"></i> {{ __("Identifiant technique actuel en BDD") }} : 
                        <code class="font-weight-bold text-dark">{{ $permission->name }}</code>
                    </small>
                </div>
            </div>

            <!-- Libellé -->
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-font text-success mr-1"></i> {{ __("Libellé d'affichage (Display Name)") }}
                    </label>
                    <input type="text" name="display_name" class="form-control form-control-alternative @error('display_name') is-invalid @enderror" value="{{ old('display_name', $permission->display_name) }}">
                    @error('display_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <div class="form-group mb-2">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-align-left text-success mr-1"></i> {{ __("Description") }}
                    </label>
                    <textarea name="description" class="form-control form-control-alternative @error('description') is-invalid @enderror" rows="3">{{ old('description', $permission->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Annuler') }}
        </button>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>