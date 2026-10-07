<div class="row">
    <div class="col-sm-12 b-r">
        <h3 class="m-t-none m-b">{{ __("CRÉATION D'UN COMPTE UTILISATEUR") }}</h3>
        <hr>

        <form role="form" method="POST" action="{{ route('users.store') }}" autocomplete="off">
            @csrf

            <fieldset>
                {{-- Choix : Personne existante vs Nouvelle Personne --}}
                <div class="form-group mb-4">
                    <label class="form-label"><b>{{ __("Ce compte appartient-il à une personne existante ?") }}</b></label>
                    <select name="personne_id" id="personne_id_select" class="form-control @error('personne_id') is-invalid @enderror">
                        <option value="">-- Non, créer une NOUVELLE personne --</option>
                        @foreach($personnes as $personne)
                            <option value="{{ $personne->id }}" {{ old('personne_id') == $personne->id ? 'selected' : '' }}>
                                {{ $personne->nom }} {{ $personne->prenoms }} ({{ $personne->email ?? 'Sans email' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                {{-- BLOC 1 : Champs pour NOUVELLE PERSONNE --}}
                <div id="new_personne_block">
                    <h5 class="text-primary mb-3"><i class="fa fa-user-plus"></i> {{ __("Informations de la Personne") }}</h5>
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label"><b>{{ __("Nom") }} <span class="text-danger">*</span></b></label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="ex: KOUASSI">
                            @error('nom') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label"><b>{{ __("Prénoms") }} <span class="text-danger">*</span></b></label>
                            <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror" value="{{ old('prenoms') }}" placeholder="ex: Jean Emmanuel">
                            @error('prenoms') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label"><b>{{ __("Sexe") }}</b></label>
                            <select name="sexe" class="form-control">
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label"><b>{{ __("Téléphone") }}</b></label>
                            <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="ex: +225 0700000000">
                        </div>
                    </div>
                </div>

                {{-- BLOC 2 : Champ EMAIL du Compte Utilisateur --}}
                <h5 class="text-success mb-3"><i class="fa fa-key"></i> {{ __("Informations du Compte Utilisateur") }}</h5>
                <div class="row">
                    <div class="form-group col-md-12 mb-3">
                        <label class="form-label"><b>{{ __("Email de connexion (Identifiant du Compte)") }} <span class="text-danger">*</span></b></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="ex: j.kouassi@hotel.com" required>
                        @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>
                </div>

                {{-- Sélection des Rôles --}}
                <h5 class="text-warning mb-3"><i class="fa fa-shield"></i> {{ __("Attribution des Rôles") }} <span class="text-danger">*</span></h5>
                <div class="form-group mb-4">
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->id }}" 
                                        class="custom-control-input" 
                                        id="role_{{ $role->id }}"
                                        {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="role_{{ $role->id }}">
                                        {{ $role->display_name ?? $role->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <small class="text-danger"><strong>{{ $message }}</strong></small>
                    @enderror
                </div>

                {{-- Info Mot de passe par défaut --}}
                <div class="alert alert-info py-2 mb-4">
                    <small>
                        <i class="fa fa-info-circle"></i> Le mot de passe par défaut sera <code>Password123!</code>. Le compte sera créé <strong>inactif</strong> avec obligation de modifier le mot de passe.
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-rounded">
                        <i class="fa fa-arrow-left"></i> {{ __('Annuler') }}
                    </a>
                    <button class="btn btn-primary btn-rounded" type="submit">
                        <strong><i class="fa fa-save"></i>&nbsp; {{ __('Créer le compte') }}</strong>
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

{{-- Script pour masquer/afficher la section Nouvelle Personne --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPersonne = document.getElementById('personne_id_select');
        const blockNewPersonne = document.getElementById('new_personne_block');

        function togglePersonneBlock() {
            if (selectPersonne.value !== "") {
                blockNewPersonne.style.display = 'none';
            } else {
                blockNewPersonne.style.display = 'block';
            }
        }

        selectPersonne.addEventListener('change', togglePersonneBlock);
        togglePersonneBlock(); // état initial
    });
</script>