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
                    <h5 class="m-b-10">Nouvelle Solution SaaS</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('solutions.index') }}">Solutions SaaS</a></li>
                    <li class="breadcrumb-item">Créer</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Ajouter une Solution</h5>
                <a href="{{ route('solutions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="feather icon-arrow-left"></i> Retour
                </a>
            </div>
            <hr>
            <div class="card-body">
                <form action="{{ route('solutions.store') }}" method="POST">
                    @csrf

                    <!-- Nom de la solution -->
                    <div class="form-group">
                        <label for="nom" class="floating-label">Nom de la Solution <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               class="form-control @error('nom') is-invalid @enderror" 
                               value="{{ old('nom') }}" 
                               placeholder="Ex: SaaS Gestion Hôtelière" 
                               required>
                        @error('nom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Code ID / Slug -->
                    <div class="form-group">
                        <label for="code" class="floating-label">Code Identifiant (Slug) <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code') }}" 
                               placeholder="Ex: saas-hotel" 
                               required>
                        <small class="form-text text-muted">Identifiant unique utilisé en interne et dans la configuration des offres (sans espaces ni caractères spéciaux).</small>
                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description de la solution</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Décrivez brièvement les fonctionnalités principales de cette solution...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Statut d'activation -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                   name="is_active" 
                                   class="custom-control-input" 
                                   id="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Activer la solution immédiatement</label>
                        </div>
                        <small class="form-text text-muted">Si désactivée, cette solution ne sera pas disponible lors de l'enregistrement de nouveaux abonnements.</small>
                    </div>

                    <hr>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('solutions.index') }}" class="btn btn-outline-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save mr-1"></i> Enregistrer
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