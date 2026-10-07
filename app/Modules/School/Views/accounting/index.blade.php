@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Payements',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'accounting.index',
    'activeModule' => 'Payement',
])

@section('content')
<div class="container-fluid py-4">
    <!-- Tableau de bord KPI Décideurs & Comptables -->
    <div class="row mb-4">
        <!-- Total Attendu / Net -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Global (Brut)</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_gross'], 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        <!-- Total Remises -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Remises</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">- {{ number_format($stats['total_discount'], 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        <!-- Total Encaissé -->
        <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Encaissé</div>
                    <div class="h5 mb-0 font-weight-bold text-success">{{ number_format($stats['total_paid'], 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        <!-- Reste à Recouvrer -->
        <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Reste à Recouvrer</div>
                    <div class="h5 mb-0 font-weight-bold text-danger">{{ number_format($stats['total_balance'], 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        <!-- Taux de Recouvrement -->
        <div class="col-xl-2 col-md-6 col-sm-12 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Taux de Recouvrement</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stats['recovery_rate'] }}%</div>
                        </div>
                        <div class="col">
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" 
                                    style="width: {{ min($stats['recovery_rate'], 100) }}%" 
                                    aria-valuenow="{{ $stats['recovery_rate'] }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres & Recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Gestion du Recouvrement</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('school.accounting.index') }}" class="form-inline mb-4">
                <div class="form-group mr-3 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Nom, prénom ou matricule..." value="{{ request('search') }}">
                </div>
                <div class="form-group mr-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Non payé</option>
                        <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partiellement payé</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-filter mr-1"></i> Filtrer</button>
            </form>

            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Matricule</th>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <!--<th>Plan Tarifaire</th>-->
                            <th>Total Dû</th>
                            <th>Remise</th>
                            <th>Payé</th>
                            <th>Solde</th>
                            <th>Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $acc)
                            <tr>
                                <td><strong>{{ $acc->student->matricule ?? 'N/A' }}</strong></td>
                                <td>{{ $acc->student->personne->nom ?? '' }} {{ $acc->student->personne->prenoms ?? '' }}</td>
                                <td>{{ $acc->registration->schoolClass->name ?? 'N/A' }}</td>
                                <!-- Affichage du Plan Tarifaire (ou des plans cumulés) -->
                                <!--<td>
                                    @if($acc->feePlan)
                                        <span class="badge badge-info">{{ $acc->feePlan->name }}</span>
                                    @else
                                        @php
                                            // Récupérer les noms uniques des plans issus des tranches
                                            $planNames = $acc->schedules->pluck('feePlan.name')->filter()->unique();
                                        @endphp
                                        
                                        @forelse($planNames as $name)
                                            <span class="badge badge-soft-primary d-block mb-1">{{ $name }}</span>
                                        @empty
                                            <span class="badge badge-secondary">Aucun plan</span>
                                        @endforelse
                                    @endif
                                </td>-->
                                <td>{{ number_format($acc->total_due, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($acc->discount_amount, 0, ',', ' ') }} FCFA</td>
                                <td class="text-success font-weight-bold">{{ number_format($acc->total_paid, 0, ',', ' ') }} FCFA</td>
                                <td class="text-danger font-weight-bold">{{ number_format($acc->balance, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($acc->status === 'paid')
                                        <span class="badge badge-success">Sdé</span>
                                    @elseif($acc->status === 'partially_paid')
                                        <span class="badge badge-warning">Partiel</span>
                                    @else
                                        <span class="badge badge-danger">Impayé</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('school.accounting.show', $acc->id) }}" class="btn btn-sm btn-info" title="Accéder au guichet">
                                        <i class="fa fa-cash-register-o mr-1"></i> Guichet
                                    </a>
                                    <!-- Télécharger Reçu PDF -->
                                    <a href="{{ route('school.accounting.account.statement.pdf', $acc->id) }}" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Télécharger le Reçu PDF" target="_blank">
                                        <i class="fa fa-file-o"></i> Reçu global
                                    </a>
                                    <!-- Bouton d'action dans le récapitulatif ou l'entête -->
                                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#addExtraFeeModal{{ $acc->id }}">
                                        <i class="fa fa-plus mr-1"></i> Ajouter des frais
                                    </button>
                                    <!-- Bouton déclencheur du modal -->
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAccountModal{{ $acc->id }}" title="Modifier la scolarité">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Édition Compte Financier (Placé à l'intérieur de la boucle) -->
                            <div class="modal fade" id="editAccountModal{{ $acc->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('school.accounting.update-account', $acc->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Scolarité : {{ $acc->student->personne->nom ?? '' }} {{ $acc->student->personne->prenoms ?? '' }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group text-left">
                                                    <label>Total Scolarité Due (FCFA)</label>
                                                    <input type="number" name="total_due" class="form-control" value="{{ $acc->total_due }}" required>
                                                </div>

                                                @php
                                                    // Calculer la remise accumulée par type
                                                    $discountInscription = $acc->schedules->filter(fn($s) => str_contains(strtolower($s->label), 'inscription'))->sum('discount_amount');
                                                    $discountScolarite   = $acc->schedules->filter(fn($s) => !str_contains(strtolower($s->label), 'inscription'))->sum('discount_amount');
                                                @endphp

                                                <div class="form-group text-left">
                                                    <label>Appliquer la remise sur :</label>
                                                    <select name="target_fee_type" id="target_fee_type_{{ $acc->id }}" class="form-control" onchange="updateDiscountInput{{ $acc->id }}()">
                                                        <option value="scolarite" data-discount="{{ $discountScolarite }}">Frais de scolarité</option>
                                                        <option value="inscription" data-discount="{{ $discountInscription }}">Droits d'inscription</option>
                                                    </select>
                                                </div>

                                                <div class="form-group text-left">
                                                    <label>Remise / Réduction du plan sélectionné (FCFA)</label>
                                                    <input type="number" name="discount_amount" id="discount_amount_input_{{ $acc->id }}" class="form-control" value="{{ $discountScolarite }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Ajout de Frais Spécifiques -->
                            <div class="modal fade" id="addExtraFeeModal{{ $acc->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('school.accounting.add-extra-fee', $acc->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ajouter des frais spécifiques</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group text-left">
                                                    <label>Type / Libellé des frais <span class="text-danger">*</span></label>
                                                    <select name="fee_type_preset" id="fee_type_preset_{{ $acc->id }}" class="form-control mb-2" onchange="toggleCustomLabel{{ $acc->id }}()">
                                                        <option value="Rattrapage - Session exceptionnelle">Rattrapage - Session exceptionnelle</option>
                                                        <option value="Cours de renforcement / Soutien">Cours de renforcement / Soutien</option>
                                                        <option value="Report d'arriérés de scolarité">Report d'arriérés de scolarité</option>
                                                        <option value="Pénalité de retard">Pénalité de retard</option>
                                                        <option value="Frais de soutenance / Diplôme">Frais de soutenance / Diplôme</option>
                                                        <option value="custom">Autre (Saisie libre)</option>
                                                    </select>
                                                    <input type="text" name="custom_label" id="custom_label_{{ $acc->id }}" class="form-control d-none" placeholder="Ex: Rattrapage - Algo PHP">
                                                </div>

                                                <div class="form-group text-left">
                                                    <label>Montant des frais (FCFA) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" class="form-control" placeholder="Ex: 25000" min="500" required>
                                                </div>

                                                <div class="form-group text-left">
                                                    <label>Date limite d'exigibilité <span class="text-danger">*</span></label>
                                                    <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <div class="form-group text-left">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="is_blocking_{{ $acc->id }}" name="is_blocking" value="1" checked>
                                                        <label class="form-check-label" for="is_blocking_{{ $acc->id }}">
                                                            Bloquant pour les évaluations / documents
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-success">Valider et Ajouter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                            function toggleCustomLabel{{ $acc->id }}() {
                                var preset = document.getElementById('fee_type_preset_{{ $acc->id }}').value;
                                var customInput = document.getElementById('custom_label_{{ $acc->id }}');
                                if (preset === 'custom') {
                                    customInput.classList.remove('d-none');
                                    customInput.required = true;
                                } else {
                                    customInput.classList.add('d-none');
                                    customInput.required = false;
                                }
                            }
                            </script>

                            <script>
                            function updateDiscountInput{{ $acc->id }}() {
                                var select = document.getElementById('target_fee_type_{{ $acc->id }}');
                                var selectedOption = select.options[select.selectedIndex];
                                var discountValue = selectedOption.getAttribute('data-discount');
                                document.getElementById('discount_amount_input_{{ $acc->id }}').value = discountValue;
                            }
                            </script>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Aucun compte financier trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection