<form method="POST" action="{{ route('access.users.store') }}" autocomplete="off">
    @csrf

    <div class="modal-body p-4">
        <div class="row">
            <!-- Choix : Personne existante vs Nouvelle Personne -->
            <div class="col-md-12 mb-3">
                <div class="form-group mb-0">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> {{ __("Ce compte appartient-il à une personne existante ?") }}
                    </label>
                    <select name="personne_id" id="personne_id_select" class="form-control form-control-alternative @error('personne_id') is-invalid @enderror">
                        <option value="">-- Non, créer une NOUVELLE personne --</option>
                        @foreach($personnes as $personne)
                            <option value="{{ $personne->id }}" {{ old('personne_id') == $personne->id ? 'selected' : '' }}>
                                {{ $personne->nom }} {{ $personne->prenoms }} ({{ $personne->email ?? 'Sans email' }})
                            </option>
                        @endforeach
                    </select>
                    @error('personne_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12"><hr class="my-3"></div>

            <!-- BLOC : Champs pour NOUVELLE PERSONNE -->
            <div class="col-md-12" id="new_personne_block">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-user-plus mr-1"></i> {{ __("Informations de la Personne") }}
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                <i class="fa fa-user text-primary mr-1"></i> {{ __("Nom") }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nom" 
                                   class="form-control form-control-alternative @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom') }}" 
                                   placeholder="Ex: KOUASSI">
                            @error('nom')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                <i class="fa fa-user text-primary mr-1"></i> {{ __("Prénoms") }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="prenoms" 
                                   class="form-control form-control-alternative @error('prenoms') is-invalid @enderror" 
                                   value="{{ old('prenoms') }}" 
                                   placeholder="Ex: Jean Emmanuel">
                            @error('prenoms')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                <i class="fa fa-venus-mars text-primary mr-1"></i> {{ __("Sexe") }}
                            </label>
                            <select name="sexe" class="form-control form-control-alternative">
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                <i class="fa fa-phone text-primary mr-1"></i> {{ __("Téléphone") }}
                            </label>
                            <input type="text" 
                                   name="telephone" 
                                   class="form-control form-control-alternative" 
                                   value="{{ old('telephone') }}" 
                                   placeholder="Ex: +225 0700000000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BLOC : Compte Utilisateur -->
            <div class="col-md-12 mt-2">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-key mr-1"></i> {{ __("Informations du Compte Utilisateur") }}
                </h6>
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-envelope text-primary mr-1"></i> {{ __("Email de connexion (Identifiant)") }} <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-control form-control-alternative @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="Ex: j.kouassi@ecole.com" 
                           required>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Selection des Rôles -->
            <div class="col-md-12">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fa fa-shield mr-1"></i> {{ __("Attribution des Rôles") }} <span class="text-danger">*</span>
                </h6>
                <div class="form-group mb-3">
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-control form-check form-check-inline">
                                    <input type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->id }}" 
                                        class="form-check-input" 
                                        id="role_{{ $role->id }}"
                                        {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold text-dark ml-1" for="role_{{ $role->id }}">
                                        {{ $role->display_name ?? $role->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <span class="text-danger d-block mt-1"><small><strong>{{ $message }}</strong></small></span>
                    @enderror
                </div>
            </div>

            <!-- Info Mot de Passe -->
            <div class="col-md-12">
                <div class="alert alert-info py-2 mb-0 border-0 shadow-sm">
                    <small>
                        <i class="fa fa-info-circle mr-1"></i> Le mot de passe par défaut sera <code>Password123!</code>. Le compte sera créé <strong>inactif</strong> avec obligation de modifier le mot de passe.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>
        <div>
            <button type="reset" class="btn btn-warning btn-round waves-effect mr-1">
                <i class="fa fa-refresh mr-1"></i> {{ __('Réinitialiser') }}
            </button>
            <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> {{ __('Enregistrer') }}
            </button>
        </div>
    </div>
</form>

<script>
    (function() {
        const selectPersonne = document.getElementById('personne_id_select');
        const blockNewPersonne = document.getElementById('new_personne_block');

        if (selectPersonne && blockNewPersonne) {
            function togglePersonneBlock() {
                if (selectPersonne.value !== "") {
                    blockNewPersonne.style.display = 'none';
                } else {
                    blockNewPersonne.style.display = 'block';
                }
            }
            selectPersonne.addEventListener('change', togglePersonneBlock);
            togglePersonneBlock();
        }
    })();
</script>