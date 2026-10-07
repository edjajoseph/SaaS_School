<div class="row">
    <div class="col-sm-12 b-r">
        <h6 class="m-t-none m-b">FORMULAIRE D'ENREGISTREMENT DES PERMISSIONS</h6>
        <hr>

        {{-- 1. Alerts globales pour les exceptions / succès --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <form role="form" method="POST" action="{{ route('permissions.store') }}" autocomplete="off">
            @csrf

            <fieldset>
                <div class="row">
                    {{-- Champ Module --}}
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label"><b>{{ __("Module") }} <span class="text-danger">*</span></b></label> 
                        <input type="text" name="module" class="form-control @error('module') is-invalid @enderror" value="{{ old('module') }}" placeholder="ex: Gestion des Accès, RH, Ventes..." required>
                        @error('module')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Champ Fonctionnalité --}}
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label"><b>{{ __("Fonctionnalité") }} <span class="text-danger">*</span></b></label> 
                        <input type="text" name="feature" class="form-control @error('feature') is-invalid @enderror" value="{{ old('feature') }}" placeholder="ex: Rôles, Utilisateurs, Employés..." required>
                        @error('feature')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Champ Action --}}
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label"><b>{{ __("Action") }} <span class="text-danger">*</span></b></label> 
                        <select name="action" class="form-control @error('action') is-invalid @enderror" required>
                            <option value="">-- Sélectionnez l'action --</option>
                            <option value="access" {{ old('action') == 'access' ? 'selected' : '' }}>Access (Accès global au module)</option>
                            <option value="show" {{ old('action') == 'show' ? 'selected' : '' }}>Show (Consulter / Lister)</option>
                            <option value="create" {{ old('action') == 'create' ? 'selected' : '' }}>Create (Créer / Ajouter)</option>
                            <option value="update" {{ old('action') == 'update' ? 'selected' : '' }}>Update (Modifier / Éditer)</option>
                            <option value="delete" {{ old('action') == 'delete' ? 'selected' : '' }}>Delete (Supprimer)</option>
                        </select>
                        @error('action')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- Champ Libellé personnalisé --}}
                <div class="form-group mb-3">
                    <label class="form-label"><b>{{ __("Libellé d'affichage (Display Name)") }}</b></label> 
                    <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name') }}" placeholder="ex: Créer un rôle (laisser vide pour générer automatiquement)">
                    <small class="form-text text-muted">{{ __("Si laissé vide, le libellé sera généré automatiquement (ex: Rôles - Create).") }}</small>
                    @error('display_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Champ Description --}}
                <div class="form-group mb-4">
                    <label class="form-label"><b>{{ __("Description") }}</b></label> 
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description courte de la permission">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Boutons d'action --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-rounded">
                        <i class="fa fa-arrow-left"></i> {{ __('Annuler') }}
                    </a>
                    <button class="btn btn-primary btn-rounded" type="submit">
                        <strong><i class="fa fa-save"></i>&nbsp; {{ __('Enregistrer') }}</strong>
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>