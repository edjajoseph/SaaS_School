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
                    <h5 class="m-b-10">Éditer le Plan Tarifaire</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Plans & Tarifs</a></li>
                    <li class="breadcrumb-item">Modifier {{ $plan->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Édition du plan : {{ $plan->name }}</h5>
                <a href="{{ route('plans.index') }}" class="btn btn-secondary btn-sm">
                    <i class="feather icon-arrow-left"></i> Annuler
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('plans.update', $plan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Solution rattachée -->
                    <div class="form-group">
                        <label for="solution_id">Solution SaaS associée <span class="text-danger">*</span></label>
                        <select name="solution_id" id="solution_id" class="form-control   select2-gradient @error('solution_id') is-invalid @enderror" required>
                            @foreach($solutions as $solution)
                                <option value="{{ $solution->id }}" {{ old('solution_id', $plan->solution_id) == $solution->id ? 'selected' : '' }}>
                                    {{ $solution->nom }} ({{ $solution->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('solution_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Nom du Plan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nom du Plan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $plan->name) }}" 
                                       required>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Slug du Plan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">Slug <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       class="form-control @error('slug') is-invalid @enderror" 
                                       value="{{ old('slug', $plan->slug) }}" 
                                       required>
                                @error('slug')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Prix -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Prix HT <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01" 
                                       name="price" 
                                       id="price" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $plan->price) }}" 
                                       required>
                                @error('price')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Devise -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="currency">Devise <span class="text-danger">*</span></label>
                                <select name="currency" id="currency" class="form-control   select2-gradient @error('currency') is-invalid @enderror" required>
                                    <option value="XOF" {{ old('currency', $plan->currency) == 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                    <option value="EUR" {{ old('currency', $plan->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="USD" {{ old('currency', $plan->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Fréquence de facturation -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="invoice_period">Périodicité <span class="text-danger">*</span></label>
                                <select name="invoice_period" id="invoice_period" class="form-control   select2-gradient @error('invoice_period') is-invalid @enderror" required>
                                    <option value="monthly" {{ old('invoice_period', $plan->invoice_period) == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                                    <option value="yearly" {{ old('invoice_period', $plan->invoice_period) == 'yearly' ? 'selected' : '' }}>Annuel</option>
                                </select>
                                @error('invoice_period')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $plan->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                   name="is_active" 
                                   class="custom-control-input" 
                                   id="is_active" 
                                   value="1" 
                                   {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Plan actif</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Créé le {{ $plan->created_at?->format('d/m/Y') }}</span>
                        <div>
                            <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary mr-2">Annuler</a>
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