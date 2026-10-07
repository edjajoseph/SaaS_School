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
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Créer un Nouveau Client</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Clients</a></li>
                    <li class="breadcrumb-item">Nouveau</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Informations du Client & Sous-domaine</h5>
                <a href="{{ route('tenants.index') }}" class="btn btn-secondary btn-sm">
                    <i class="feather icon-arrow-left"></i> Retour
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('tenants.store') }}" method="POST">
                    @csrf

                    <!-- Identifiant / Nom d'entreprise -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Nom de l'entreprise / Client <span class="text-danger">*</span></label>
                                <input type="text" 
                                    name="name" 
                                    id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" 
                                    placeholder="ex: ASALAI HOTEL SA" 
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Adresse Email Contact <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="contact@entreprise.com" 
                                       required>
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configuration du Domaine -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id">Identifiant Tenant (ID unique) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="id" 
                                       id="id" 
                                       class="form-control @error('id') is-invalid @enderror" 
                                       value="{{ old('id') }}" 
                                       placeholder="ex: ivotel" 
                                       required>
                                <small class="form-text text-muted">Utilisé en interne (minuscules, sans espaces ni caractères spéciaux).</small>
                                @error('id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="domain">Sous-domaine d'accès <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           name="domain" 
                                           id="domain" 
                                           class="form-control @error('domain') is-invalid @enderror" 
                                           value="{{ old('domain') }}" 
                                           placeholder="ivotel" 
                                           required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.{{ config('app.domain', 'votre-domaine.com') }}</span>
                                    </div>
                                    @error('domain')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Attribution initiale d'un Plan (Optionnel) -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="solution_id">Solution Initiale</label>
                                <select name="solution_id" id="solution_id" class="form-control  select2-gradient @error('solution_id') is-invalid @enderror">
                                    <option value="">-- Aucune solution --</option>
                                    @foreach($solutions ?? [] as $solution)
                                        <option value="{{ $solution->id }}" {{ old('solution_id') == $solution->id ? 'selected' : '' }}>
                                            {{ $solution->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="plan_id">Plan Tarifaire Initial</label>
                                <select name="plan_id" id="plan_id" class="form-control  select2-gradient @error('plan_id') is-invalid @enderror">
                                    <option value="">-- Aucun plan (Essai / Gratuit) --</option>
                                    @foreach($plans ?? [] as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency }}/{{ $plan->invoice_period }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Statut Initial <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control   select2-gradient @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save mr-1"></i> Créer le Client
                        </button>
                    </div>
                </form>
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