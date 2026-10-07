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
                    <h5 class="m-b-10">Nouveau Plan Tarifaire</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Plans & Tarifs</a></li>
                    <li class="breadcrumb-item">Nouveau</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Créer une Souscription Client</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('subscriptions.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Client -->
                        <div class="col-md-4 form-group">
                            <label for="tenant_id">Client / Entreprise <span class="text-danger">*</span></label>
                            <select name="tenant_id" id="tenant_id" class="form-control  select2-gradient" required>
                                <option value="">-- Choisir un client --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name ?? $tenant->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Solution -->
                        <div class="col-md-4 form-group">
                            <label for="solution_id">Solution SaaS <span class="text-danger">*</span></label>
                            <select name="solution_id" id="solution_id" class="form-control  select2-gradient" required>
                                <option value="">-- Choisir la solution --</option>
                                @foreach($solutions as $solution)
                                    <option value="{{ $solution->id }}">{{ $solution->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Plan -->
                        <div class="col-md-4 form-group">
                            <label for="plan_id">Plan / Offre <span class="text-danger">*</span></label>
                            <select name="plan_id" id="plan_id" class="form-control  select2-gradient" required>
                                <option value="">-- Choisir un plan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ number_format($plan->price, 0, ',', ' ') }} XOF / mois)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cycle de facturation -->
                        <div class="col-md-4 form-group">
                            <label for="billing_cycle">Cycle de Facturation <span class="text-danger">*</span></label>
                            <select name="billing_cycle" id="billing_cycle" class="form-control  select2-gradient" required>
                                <option value="monthly">Mensuel</option>
                                <option value="yearly">Annuel</option>
                            </select>
                        </div>

                        <!-- Date de Début -->
                        <div class="col-md-3 form-group">
                            <label for="starts_at">Date de Début <span class="text-danger">*</span></label>
                            <input type="date" name="starts_at" id="starts_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <!-- Taux TVA (%) -->
                        <div class="col-md-2 form-group">
                            <label for="tax_rate">TVA (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" value="18" required>
                        </div>

                        <!-- Devise -->
                        <div class="col-md-3 form-group">
                            <label for="currency">Devise <span class="text-danger">*</span></label>
                            <select name="currency" id="currency" class="form-control  select2-gradient" required>
                                <option value="XOF">FCFA (XOF)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="USD">Dollar ($)</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Valider la Souscription</button>
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