<style>
/* Alignement strict de la hauteur et du style sur les input standard */
.select2-container--default .select2-selection--single {
    height: calc(1em + 1.25rem + 2px) !important; /* Hauteur identique à .form-control */
    padding: 0.625rem 0.75rem !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    border-radius: 6px !important;
    border: 1px solid #ced4da !important;
    display: flex !important;
    align-items: center !important;
}

/* Réglage du texte affiché et du placeholder */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 0 !important;
    padding-right: 20px !important;
    line-height: normal !important;
    color: #495057 !important;
}

/* Repositionnement centré de la flèche déroulante */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
    right: 8px !important;
    display: flex !important;
    align-items: center !important;
}

/* Nettoyage du bouton de suppression si présent */
.select2-container--default .select2-selection--single .select2-selection__clear {
    margin-right: 10px !important;
}
/* Forcer le menu déroulant Select2 à passer au-dessus de tout le reste */
.select2-dropdown {
    z-index: 99999 !important;
}

/* Si Select2 est utilisé dans une Modale Bootstrap */
.select2-container--open {
    z-index: 999999 !important;
}

</style>
@php
    // Extraction du sous-domaine seul (ex: "asalai" à partir de "asalai.votre-domaine.com")
    $fullDomain = optional($tenant->domains->first())->domain ?? '';
    $subdomain = explode('.', $fullDomain)[0] ?? '';
    
    // Récupération sécurisée du nom d'entreprise
    $companyName = $tenant->company_name ?? ($tenant->data['company_name'] ?? '');
@endphp

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Éditer le Client : {{ $companyName ?: $tenant->id }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Clients</a></li>
                    <li class="breadcrumb-item">Modifier</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Mettre à jour les données du Client</h5>
                <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-secondary btn-sm">
                    <i class="feather icon-arrow-left"></i> Annuler
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('tenants.update', $tenant) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nom de l'entreprise -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nom de l'entreprise <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="name" 
                                    id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $tenant->name) }}" 
                                    required>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Adresse Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                    name="email" 
                                    id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $tenant->email) }}" 
                                    required>
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- ID Tenant -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id">ID Tenant</label>
                                <input type="text" 
                                    id="id" 
                                    class="form-control" 
                                    value="{{ $tenant->id }}" 
                                    disabled>
                                <small class="form-text text-muted">L'ID interne du tenant ne peut pas être modifié.</small>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Statut du Compte <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select2-gradient @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                    <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Sous-domaine principal -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="domain">Sous-domaine principal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                        name="domain" 
                                        id="domain" 
                                        class="form-control @error('domain') is-invalid @enderror" 
                                        value="{{ old('domain', $subdomain ?? $tenant->id) }}" 
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.{{ config('app.domain', 'saas-hotel.test') }}</span>
                                    </div>
                                    @error('domain')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Solution d'application -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="solution_id">Solution Initiale</label>
                                <select name="solution_id" id="solution_id" class="form-control select2-gradient @error('solution_id') is-invalid @enderror">
                                    <option value="">-- Aucune solution --</option>
                                    @foreach($solutions ?? [] as $solution)
                                        <option value="{{ $solution->id }}" 
                                            {{ old('solution_id', $tenant->solution_id ?? '') == $solution->id ? 'selected' : '' }}>
                                            {{ $solution->nom ?? $solution->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('solution_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Offre / Plan d'abonnement -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plan_id">Offre / Plan d'abonnement</label>
                                <select name="plan_id" id="plan_id" class="form-control select2-gradient @error('plan_id') is-invalid @enderror">
                                    <option value="">-- Sans abonnement --</option>
                                    @foreach($plans ?? [] as $plan)
                                        <option value="{{ $plan->id }}" 
                                            {{ old('plan_id', $currentPlanId ?? '') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->nom ?? $plan->name }} {{ isset($plan->price) ? '('.number_format($plan->price, 0, ',', ' ').' FCFA)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Inscrit le {{ $tenant->created_at ? $tenant->created_at->format('d/m/Y') : 'N/A' }}</span>
                        <div>
                            <a href="#" class="btn btn-outline-secondary mr-2" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('tenants.show', $tenant) }}" title="Afficher">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="feather icon-check-circle mr-1"></i> Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="mediumModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-warning">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion des accès</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody2">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

<!-- CDN Select2 (si pas encore inclus) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialisation de Select2 avec recherche dynamique
    $('.select2-gradient').select2({
        placeholder: "Sélectionner une option",
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            }
        }
    });

    $('.select2-gradient').select2({
        placeholder: "Sélectionner une option",
        allowClear: true,
        width: '100%',
        dropdownParent: $('body') // <-- Force l'attachement au body pour éviter d'être masqué
    });
});
</script>