<div class="row">
    <div class="col-sm-12 b-r">
        <h3 class="m-t-none m-b"><i class="fa fa-edit text-success"></i> {{ __("MODIFICATION DU COMPTE UTILISATEUR") }}</h3>
        <hr>

        {{-- Formulaire de mise à jour --}}
        <form role="form" method="POST" action="{{ route('users.update', $user->id) }}" autocomplete="off" id="form-edit-user">
            @csrf
            @method('PUT')

            <fieldset>
                {{-- Informatique de la personne rattachée (Lecture Seule) --}}
                <div class="card bg-light mb-4 border">
                    <div class="card-body py-2">
                        <small class="text-muted d-block font-weight-bold">{{ __("TITULAIRE DU COMPTE (PERSONNE)") }}</small>
                        <span class="h5 font-bold text-dark">
                            <i class="fa fa-user text-primary"></i> 
                            {{ $user->personne->nom ?? '' }} {{ $user->personne->prenoms ?? $user->name }}
                        </span>
                        @if(optional($user->personne)->telephone)
                            <small class="text-muted ml-2"><i class="fa fa-phone"></i> {{ $user->personne->telephone }}</small>
                        @endif
                    </div>
                </div>

                {{-- Section : Informations du Compte --}}
                <h5 class="text-success mb-3"><i class="fa fa-envelope"></i> {{ __("Identifiants & Statut") }}</h5>
                
                <div class="row">
                    {{-- Email de connexion --}}
                    <div class="form-group col-md-8 mb-3">
                        <label class="form-label"><b>{{ __("Email de connexion") }} <span class="text-danger">*</span></b></label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Commutateur Statut (Actif/Inactif) --}}
                    <div class="form-group col-md-4 mb-3">
                        <label class="form-label d-block"><b>{{ __("Statut du compte") }}</b></label>
                        <div class="custom-control custom-switch mt-2">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="isactive_edit" 
                                   name="isactive" 
                                   value="1" 
                                   {{ old('isactive', $user->isactive) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="isactive_edit">
                                {{ __("Compte Actif") }}
                            </label>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Section : Rôles du Compte --}}
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
                                           id="edit_role_{{ $role->id }}"
                                           {{ (is_array(old('roles')) && in_array($role->id, old('roles'))) || (!old('roles') && in_array($role->id, $userRoleIds)) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="edit_role_{{ $role->id }}">
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

                {{-- Informations sur le changement de MDP --}}
                <div class="alert alert-secondary py-2 mb-4">
                    <small>
                        <i class="fa fa-lock"></i> 
                        <strong>Changement de mot de passe :</strong> 
                        @if($user->pwd_change)
                            <span class="text-success">{{ __("L'utilisateur a déjà configuré son mot de passe personnel.") }}</span>
                        @else
                            <span class="text-warning">{{ __("L'utilisateur n'a pas encore modifié son mot de passe par défaut.") }}</span>
                        @endif
                    </small>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('Fermer') }}
                    </button>
                    <button class="btn btn-success btn-rounded" type="submit">
                        <strong><i class="fa fa-check"></i>&nbsp; {{ __('Enregistrer les modifications') }}</strong>
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>