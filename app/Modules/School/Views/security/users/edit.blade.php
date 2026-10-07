<form method="POST" action="{{ route('access.users.update', $user->id) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Information personne rattachée -->
            <div class="col-md-12 mb-3">
                <div class="card bg-light border shadow-none mb-0">
                    <div class="card-body py-2">
                        <small class="text-muted d-block font-weight-bold">{{ __("TITULAIRE DU COMPTE") }}</small>
                        <span class="h6 font-weight-bold text-dark mb-0">
                            <i class="fa fa-user text-success mr-1"></i> 
                            {{ $user->personne->nom ?? '' }} {{ $user->personne->prenoms ?? $user->name }}
                        </span>
                        @if(optional($user->personne)->telephone)
                            <small class="text-muted ml-2"><i class="fa fa-phone mr-1"></i>{{ $user->personne->telephone }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Identifiants & Statut -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-envelope text-success mr-1"></i> {{ __("Email de connexion") }} <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-control form-control-alternative @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark d-block">
                        <i class="fa fa-toggle-on text-success mr-1"></i> {{ __("Statut du compte") }}
                    </label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="isactive_edit" 
                               name="isactive" 
                               value="1" 
                               {{ old('isactive', $user->isactive) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-dark" for="isactive_edit">
                            {{ __("Compte Actif") }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Attribution des Rôles -->
            <div class="col-md-12">
                <h6 class="font-weight-bold text-success mb-3">
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
                                        id="edit_role_{{ $role->id }}"
                                        {{ (is_array(old('roles')) && in_array($role->id, old('roles'))) || (!old('roles') && in_array($role->id, $userRoleIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold text-dark ml-1" for="edit_role_{{ $role->id }}">
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
                <div class="alert alert-secondary py-2 mb-0 border-0 shadow-sm">
                    <small>
                        <i class="fa fa-lock mr-1"></i> 
                        <strong>Changement de mot de passe :</strong> 
                        @if($user->pwd_change)
                            <span class="text-success font-weight-bold">{{ __("L'utilisateur a déjà configuré son mot de passe personnel.") }}</span>
                        @else
                            <span class="text-warning font-weight-bold">{{ __("L'utilisateur n'a pas encore modifié son mot de passe par défaut.") }}</span>
                        @endif
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
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>