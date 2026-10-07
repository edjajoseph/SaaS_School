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
                    <h5 class="m-b-10">Création d'une Facture Client</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Factures</a></li>
                    <li class="breadcrumb-item">Nouvelle Facture</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Informations de la Facture</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Client / Tenant -->
                        <!-- Exemple : Sélection du Client avec Select2 -->
                        <div class="form-group mb-3">
                            <label for="tenant_id" class="form-label font-weight-bold">Client / Entreprise <span class="text-danger">*</span></label>
                            <select name="tenant_id" id="tenant_id" class="form-control select2-gradient" required>
                                <option value="">-- Parcourir ou chercher un client --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" data-icon="feather icon-user">
                                        {{ $tenant->name ?? $tenant->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date d'échéance -->
                        <div class="col-md-3 form-group">
                            <label for="due_date">Date d'échéance <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="due_date" 
                                   id="due_date" 
                                   class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" 
                                   required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sélection de la Devise -->
                        <div class="col-md-3 form-group">
                            <label for="currency">Devise <span class="text-danger">*</span></label>
                            <select name="currency" id="currency" class="form-control  select2-gradient @error('currency') is-invalid @enderror" required>
                                <option value="XOF" {{ old('currency', 'XOF') === 'XOF' ? 'selected' : '' }}>FCFA (XOF)</option>
                                <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Section Financière -->
                    <div class="row">
                        <!-- Montant HT -->
                        <div class="col-md-6 form-group">
                            <label for="amount_ht">Montant HT <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       name="amount_ht" 
                                       id="amount_ht" 
                                       class="form-control @error('amount_ht') is-invalid @enderror" 
                                       value="{{ old('amount_ht', '0') }}" 
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text currency-label">XOF</span>
                                </div>
                            </div>
                            @error('amount_ht')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Taux TVA (Saisie libre en %) -->
                        <div class="col-md-6 form-group">
                            <label for="tax_rate">Taux TVA (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       name="tax_rate" 
                                       id="tax_rate" 
                                       class="form-control @error('tax_rate') is-invalid @enderror" 
                                       value="{{ old('tax_rate', '18') }}" 
                                       placeholder="ex: 18" 
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Montant TVA calculé -->
                        <div class="col-md-6 form-group">
                            <label for="tax_amount_display">Montant TVA (Calculé)</label>
                            <div class="input-group">
                                <input type="text" id="tax_amount_display" class="form-control bg-light" readonly value="0 XOF">
                            </div>
                        </div>

                        <!-- Total TTC calculé -->
                        <div class="col-md-6 form-group">
                            <label for="amount_ttc_display">Total TTC (Calculé)</label>
                            <div class="input-group">
                                <input type="text" id="amount_ttc_display" class="form-control bg-light font-weight-bold text-primary" readonly value="0 XOF">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="status" value="pending">

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save mr-1"></i> Générer la Facture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script JS mis à jour pour recalculer les totaux et ajuster la devise -->
<script>
    function calculateInvoiceTotals() {
        const htField = document.getElementById('amount_ht');
        const taxRateField = document.getElementById('tax_rate');
        const currencySelect = document.getElementById('currency');
        const taxDisplay = document.getElementById('tax_amount_display');
        const ttcDisplay = document.getElementById('amount_ttc_display');
        const currencyLabels = document.querySelectorAll('.currency-label');

        if (!htField || !taxRateField || !currencySelect) return;

        const ht = parseFloat(htField.value) || 0;
        const rate = parseFloat(taxRateField.value) || 0;
        const selectedCurrency = currencySelect.value || 'XOF';

        // Mise à jour de l'étiquette de devise dans l'input HT
        currencyLabels.forEach(label => label.textContent = selectedCurrency);

        // Calculs
        const taxAmount = ht * (rate / 100);
        const ttcAmount = ht + taxAmount;

        // Formatage de l'affichage
        const formattedTax = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(taxAmount);
        const formattedTtc = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(ttcAmount);

        taxDisplay.value = `${formattedTax} ${selectedCurrency}`;
        ttcDisplay.value = `${formattedTtc} ${selectedCurrency}`;
    }

    // Écoute des événements sur tous les champs modifiables
    ['DOMContentLoaded', 'input', 'keyup', 'change'].forEach(function(eventName) {
        document.addEventListener(eventName, function(e) {
            if (e.target && ['amount_ht', 'tax_rate', 'currency'].includes(e.target.id) || eventName === 'DOMContentLoaded') {
                calculateInvoiceTotals();
            }
        });
    });
</script>

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