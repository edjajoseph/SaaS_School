<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Fiche Solution : {{ $solution->nom }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('solutions.index') }}">Solutions SaaS</a></li>
                    <li class="breadcrumb-item">Détails</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- En-tête d'actions -->
<div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <a href="{{ route('solutions.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="feather icon-arrow-left mr-1"></i> Retour à la liste
        </a>
        <div>
            <a href="{{ route('solutions.edit', $solution) }}" class="btn btn-warning btn-sm">
                <i class="feather icon-edit mr-1"></i> Modifier la solution
            </a>
            <form action="{{ route('solutions.destroy', $solution) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette solution ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="feather icon-trash-2 mr-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Colonne de Gauche : Infos Générales & Cartes Statistiques -->
    <div class="col-xl-4 col-md-12">
        <!-- Carte Principale Solution -->
        <div class="card">
            <div class="card-body text-center">
                <div class="badge badge-light-primary mb-2 font-size-lg p-2">
                    <code>{{ $solution->code }}</code>
                </div>
                <h4 class="mt-2">{{ $solution->nom }}</h4>
                <p class="text-muted">
                    @if($solution->is_active)
                        <span class="badge badge-success"><i class="feather icon-check-circle mr-1"></i> Solution Active</span>
                    @else
                        <span class="badge badge-danger"><i class="feather icon-x-circle mr-1"></i> Solution Inactive</span>
                    @endif
                </p>
                <p class="text-muted text-left mt-3">
                    <strong>Description :</strong><br>
                    {{ $solution->description ?? 'Aucune description renseignée pour cette solution.' }}
                </p>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="m-b-0">{{ $solution->plans->count() }}</h5>
                        <span class="text-muted small">Plans Tarifaires</span>
                    </div>
                    <div class="col-6">
                        <h5 class="m-b-0">{{ $solution->tenants->count() }}</h5>
                        <span class="text-muted small">Clients Actifs</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light text-muted small text-center">
                Créée le {{ $solution->created_at?->format('d/m/Y à H:i') }}
            </div>
        </div>

        <!-- Widget Statut / Action Rapide -->
        <div class="card bg-c-blue text-white widget-visitor-card">
            <div class="card-body text-center">
                <h2 class="text-white">{{ $solution->tenants->count() }}</h2>
                <h6 class="text-white">Tenants Rattachés</h6>
                <i class="feather icon-briefcase"></i>
            </div>
        </div>
    </div>

    <!-- Colonne de Droite : Plans Tarifaires & Derniers Tenants -->
    <div class="col-xl-8 col-md-12">
        
        <!-- Section 1 : Plans Tarifaires Associés -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Plans Tarifaires Rattachés</h5>
                <a href="{{ route('plans.create', ['solution_id' => $solution->id]) }}" class="btn btn-primary btn-sm rounded-pill">
                    <i class="feather icon-plus"></i> Ajouter un Plan
                </a>
            </div>
            <div class="card-body table-border-style p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nom du Plan</th>
                                <th>Prix (XOF)</th>
                                <th>Facturation</th>
                                <th>Statut</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($solution->plans as $plan)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $plan->nom }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency ?? 'FCFA' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info">
                                            {{ ucfirst($plan->invoice_period ?? 'Mensuel') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($plan->is_active ?? true)
                                            <span class="badge badge-light-success">Actif</span>
                                        @else
                                            <span class="badge badge-light-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('plans.edit', $plan) }}" class="btn btn-icon btn-outline-warning btn-sm" title="Modifier le plan">
                                            <i class="feather icon-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="feather icon-alert-circle display-4 d-block mb-2"></i>
                                        Aucun plan tarifaire n'est encore configuré pour cette solution.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 2 : Derniers Clients / Tenants Ayant Souscrit -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Derniers Clients (Tenants)</h5>
                <a href="{{ route('tenants.index', ['solution_id' => $solution->id]) }}" class="btn btn-link btn-sm">
                    Voir tous les clients
                </a>
            </div>
            <div class="card-body table-border-style p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Sous-Domaine / ID</th>
                                <th>Nom Entreprise</th>
                                <th>Statut Compte</th>
                                <th>Inscrit le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($solution->tenants as $tenant)
                                <tr>
                                    <td>
                                        <code>{{ $tenant->id }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $tenant->company_name ?? $tenant->id }}</strong>
                                    </td>
                                    <td>
                                        @if(($tenant->status ?? 'active') === 'active')
                                            <span class="badge badge-light-success">Actif</span>
                                        @elseif($tenant->status === 'suspended')
                                            <span class="badge badge-light-danger">Suspendu</span>
                                        @else
                                            <span class="badge badge-light-warning">{{ ucfirst($tenant->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $tenant->created_at?->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Aucun client n'est encore inscrit sous cette solution.
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