<div class="row">
    <div class="col-sm-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="m-0"><i class="fa fa-id-card text-info"></i> {{ __("DÉTAILS DU COMPTE UTILISATEUR") }}</h3>
            {{-- Badge de Statut Global --}}
            @if($user->isactive)
                <span class="badge badge-primary px-3 py-2"><i class="fa fa-check-circle"></i> {{ __("Compte Actif") }}</span>
            @else
                <span class="badge badge-danger px-3 py-2"><i class="fa fa-ban"></i> {{ __("Compte Inactif") }}</span>
            @endif
        </div>
        <hr>

        {{-- Section 1: Informations Personnelles --}}
        <div class="card mb-3 border">
            <div class="card-header bg-light py-2">
                <h5 class="mb-0 text-dark"><i class="fa fa-user text-primary"></i> {{ __("Informations Personnelles") }}</h5>
            </div>
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">{{ __("Nom & Prénoms") }}</small>
                        <strong class="text-dark">{{ $user->personne->nom ?? '' }} {{ $user->personne->prenoms ?? $user->name }}</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">{{ __("Sexe") }}</small>
                        <strong class="text-dark">
                            @if(optional($user->personne)->sexe == 'M')
                                <i class="fa fa-mars text-info"></i> Masculin
                            @elseif(optional($user->personne)->sexe == 'F')
                                <i class="fa fa-venus text-danger"></i> Féminin
                            @else
                                Non spécifié
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted d-block">{{ __("Téléphone") }}</small>
                        <strong class="text-dark"><i class="fa fa-phone"></i> {{ $user->personne->telephone ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Identifiants & Sécurité du Compte --}}
        <div class="card mb-3 border">
            <div class="card-header bg-light py-2">
                <h5 class="mb-0 text-dark"><i class="fa fa-lock text-warning"></i> {{ __("Identifiants & Sécurité") }}</h5>
            </div>
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">{{ __("Adresse Email (Identifiant)") }}</small>
                        <code>{{ $user->email }}</code>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">{{ __("État du Mot de Passe") }}</small>
                        @if($user->pwd_change)
                            <span class="badge badge-success"><i class="fa fa-lock"></i> {{ __("Mot de passe personnalisé défini") }}</span>
                        @else
                            <span class="badge badge-warning"><i class="fa fa-exclamation-triangle"></i> {{ __("Mot de passe temporaire (Non modifié)") }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">{{ __("Date de Création") }}</small>
                        <span class="text-dark">{{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">{{ __("Dernière Modification") }}</small>
                        <span class="text-dark">{{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Rôles & Permissions --}}
        <div class="card mb-4 border">
            <div class="card-header bg-light py-2">
                <h5 class="mb-0 text-dark"><i class="fa fa-shield text-info"></i> {{ __("Rôles Attribués") }}</h5>
            </div>
            <div class="card-body py-3">
                @forelse($user->roles as $role)
                    <span class="badge badge-info p-2 mr-1 mb-1" style="font-size: 13px;">
                        <i class="fa fa-user-shield"></i> {{ $role->display_name ?? $role->name }}
                    </span>
                @empty
                    <span class="text-muted"><i>{{ __("Aucun rôle attribué à ce compte.") }}</i></span>
                @endforelse
            </div>
        </div>

        {{-- Pied de page / Actions --}}
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">
                <i class="fa fa-times"></i> {{ __('Fermer') }}
            </button>
        </div>
    </div>
</div>