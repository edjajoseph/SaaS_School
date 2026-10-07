@extends('School::layouts.app3', [
    'namePage' => 'Gestion des paramètres sociaux',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'payroll.setting.index',
    'activeModule' => 'setting',
])

@section('content')
<style>
hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}

/* Style universel pour garantir l'affichage et le clic sur les checkboxes */
.form-check-custom {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
    margin-bottom: 0;
}

.form-check-custom input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 10px;
    cursor: pointer;
    accent-color: #4099ff; /* Couleur primaire Gradient Able */
}

.form-check-custom label {
    margin-bottom: 0;
    cursor: pointer;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- 1. CADRE RÉGLEMENTAIRE & PARAMÈTRES GÉNÉRAUX -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-settings mr-2"></i>{{ __("CONFIGURATION DE LA PAIE & COTISATIONS") }}
                    </h4>
                    
                    <!-- Bouton Création d'une règle -->
                    <button class="btn btn-primary btn-round waves-effect shadow-sm text-white" data-toggle="modal" data-target="#modalCreateRule">
                        <i class="ace-icon fa fa-plus mr-1"></i> {{ __("Ajouter une Rubrique / Taxe") }}
                    </button>
                </div>
                <hr class="hr-gradient">                   
                
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Notifications SweetAlert Flash Session -->
                    @if($message = session('success'))
                        <script>
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Félicitations !', 
                                text: {!! json_encode($message) !!}, 
                                confirmButtonColor: '#1ab394' 
                            });
                        </script>
                    @elseif($message = session('error'))
                        <script>
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Désolé !', 
                                text: {!! json_encode($message) !!}, 
                                confirmButtonColor: '#ed5565' 
                            });
                        </script>
                    @endif

                    <form action="{{ route('school.accounting.payroll-settings.update', $setting->id ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-globe text-primary mr-1"></i> {{ __("Pays (Zone Fiscale)") }}
                                    </label>
                                    <select name="country_id" class="form-control form-control-alternative">
                                        <option value="">-- Sélectionner un pays --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ ($setting->country_id ?? null) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3 pt-2">
                                    <div class="form-check-custom">
                                        <input type="checkbox" id="apply_taxes_to_vacants" name="apply_taxes_to_vacants" value="1" {{ !empty($setting->apply_taxes_to_vacants) ? 'checked' : '' }}>
                                        <label for="apply_taxes_to_vacants" class="text-dark font-weight-bold">
                                            {{ __("Appliquer les taxes par défaut aux vacataires") }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 text-right">
                                <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm w-100">
                                    <i class="fa fa-save mr-1"></i> {{ __("Enregistrer les paramètres") }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. GRILLE DES PRÉLÈVEMENTS & TAXES -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-list mr-2"></i>{{ __("GRILLE DES PRÉLÈVEMENTS & TAXES") }}
                    </h5>
                </div>
                <hr class="hr-gradient">

                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Désignation / Libellé</th>
                                    <th>Catégorie</th>
                                    <th>Calcul</th>
                                    <th class="text-right">Taux / Montant</th>
                                    <th class="text-right">Plafond</th>
                                    <th class="text-center">Vacataires</th>
                                    <th class="text-center">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxRules as $rule)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $rule->code }}</span></td>
                                        <td class="font-weight-bold text-dark">{{ $rule->name }}</td>
                                        <td>
                                            <span class="badge badge-info px-2 py-1">
                                                {{ str_replace('_', ' ', strtoupper($rule->category)) }}
                                            </span>
                                        </td>
                                        <td>{{ $rule->calculation_type === 'percentage' ? 'Pourcentage' : 'Forfait Fixe' }}</td>
                                        <td class="text-right font-weight-bold">
                                            @if($rule->calculation_type === 'percentage')
                                                {{ number_format($rule->rate * 100, 2) }} %
                                            @else
                                                {{ number_format($rule->fixed_amount, 0, ',', ' ') }} FCFA
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $rule->ceiling > 0 ? number_format($rule->ceiling, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                                        <td class="text-center">
                                            @if($rule->applies_to_vacants)
                                                <span class="badge badge-success px-2 py-1">Oui</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Non</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($rule->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times mr-1"></i> Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Modifier -->
                                                <button type="button" class="btn btn-warning btn-mini mr-1 text-white" data-toggle="modal" data-target="#modalEditRule{{ $rule->id }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Action Supprimer avec SweetAlert -->
                                                <form action="{{ route('school.accounting.payroll-tax-rules.destroy', $rule->id) }}" method="POST" class="d-inline"
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera cette rubrique de taxe !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL DE MODIFICATION -->
                                    <div class="modal fade" id="modalEditRule{{ $rule->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('school.accounting.payroll-tax-rules.update', $rule->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white"><i class="fa fa-edit mr-1"></i> MODIFIER LA RUBRIQUE : {{ $rule->name }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label class="font-weight-bold text-dark">Code Identifiant</label>
                                                                <input type="text" name="code" class="form-control form-control-alternative" value="{{ $rule->code }}" required>
                                                            </div>
                                                            <div class="col-md-8 mb-3">
                                                                <label class="font-weight-bold text-dark">Libellé / Nom de la taxe</label>
                                                                <input type="text" name="name" class="form-control form-control-alternative" value="{{ $rule->name }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Catégorie</label>
                                                                <select name="category" class="form-control form-control-alternative">
                                                                    <option value="social_salarial" {{ $rule->category === 'social_salarial' ? 'selected' : '' }}>Cotisation Sociale Salariale</option>
                                                                    <option value="tax_salarial" {{ $rule->category === 'tax_salarial' ? 'selected' : '' }}>Impôt Salarial (ITS/IGR)</option>
                                                                    <option value="mutual_salarial" {{ $rule->category === 'mutual_salarial' ? 'selected' : '' }}>Mutuelle / Assurance Salariale</option>
                                                                    <option value="social_patronal" {{ $rule->category === 'social_patronal' ? 'selected' : '' }}>Charges Patronales Sociales</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Mode de calcul</label>
                                                                <select name="calculation_type" class="form-control form-control-alternative">
                                                                    <option value="percentage" {{ $rule->calculation_type === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                                                    <option value="fixed_amount" {{ $rule->calculation_type === 'fixed_amount' ? 'selected' : '' }}>Montant Fixe (FCFA)</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Taux (%)</label>
                                                                <input type="number" step="0.01" name="rate" class="form-control form-control-alternative" value="{{ $rule->rate * 100 }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Montant Fixe (FCFA)</label>
                                                                <input type="number" step="0.01" name="fixed_amount" class="form-control form-control-alternative" value="{{ $rule->fixed_amount }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Plafond Mensuel (FCFA)</label>
                                                                <input type="number" step="0.01" name="ceiling" class="form-control form-control-alternative" value="{{ $rule->ceiling }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="font-weight-bold text-dark">Plancher / Base minimale (FCFA)</label>
                                                                <input type="number" step="0.01" name="min_base" class="form-control form-control-alternative" value="{{ $rule->min_base }}">
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <div class="form-check-custom">
                                                                    <input type="checkbox" id="edit_active_{{ $rule->id }}" name="is_active" value="1" {{ $rule->is_active ? 'checked' : '' }}>
                                                                    <label for="edit_active_{{ $rule->id }}" class="text-dark font-weight-bold">Règle active</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <div class="form-check-custom">
                                                                    <input type="checkbox" id="edit_vacant_{{ $rule->id }}" name="applies_to_vacants" value="1" {{ $rule->applies_to_vacants ? 'checked' : '' }}>
                                                                    <label for="edit_vacant_{{ $rule->id }}" class="text-dark font-weight-bold">Appliquer aux vacataires</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm">Mettre à jour</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucune règle de cotisation ou taxe enregistrée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL CRÉATION RUBRIQUE -->
<div class="modal fade" id="modalCreateRule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('school.accounting.payroll-tax-rules.store') }}" method="POST">
                @csrf
                <input type="hidden" name="school_id" value="{{ $schoolId ?? '' }}">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"><i class="fa fa-plus-circle mr-1"></i> NOUVELLE RUBRIQUE / PRÉLÈVEMENT</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold text-dark">Code (ex: CNPS, ITS, CNAM)</label>
                            <input type="text" name="code" class="form-control form-control-alternative" placeholder="CNPS" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="font-weight-bold text-dark">Libellé complet</label>
                            <input type="text" name="name" class="form-control form-control-alternative" placeholder="Cotisation Retraite CNPS" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Catégorie</label>
                            <select name="category" class="form-control form-control-alternative">
                                <option value="social_salarial">Cotisation Sociale Salariale</option>
                                <option value="tax_salarial">Impôt Salarial (ITS/IGR/CN)</option>
                                <option value="mutual_salarial">Mutuelle / Assurance (CNAM/MUGEFCI)</option>
                                <option value="social_patronal">Charges Patronales Sociales</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Type de calcul</label>
                            <select name="calculation_type" class="form-control form-control-alternative">
                                <option value="percentage">Pourcentage (%)</option>
                                <option value="fixed_amount">Montant Fixe (FCFA)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Taux en % (ex: 6.3 pour 6,3%)</label>
                            <input type="number" step="0.01" name="rate" class="form-control form-control-alternative" placeholder="6.3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Montant Fixe (FCFA)</label>
                            <input type="number" step="0.01" name="fixed_amount" class="form-control form-control-alternative" placeholder="1000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Plafond Mensuel (FCFA)</label>
                            <input type="number" step="0.01" name="ceiling" class="form-control form-control-alternative" placeholder="3000000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Plancher Imposable (FCFA)</label>
                            <input type="number" step="0.01" name="min_base" class="form-control form-control-alternative" placeholder="0">
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-check-custom">
                                <input type="checkbox" id="create_is_active" name="is_active" value="1" checked>
                                <label for="create_is_active" class="text-dark font-weight-bold">Activer immédiatement</label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-check-custom">
                                <input type="checkbox" id="create_applies_vacants" name="applies_to_vacants" value="1">
                                <label for="create_applies_vacants" class="text-dark font-weight-bold">Appliquer aux vacataires</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm"><i class="fa fa-save mr-1"></i> Créer la rubrique</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection