<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Fiche Client : {{ $tenant->company_name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenants.index') }}">Clients</a></li>
                    <li class="breadcrumb-item">{{ $tenant->id }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="feather icon-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row">
    <!-- Profil & Informations Générales -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Profil Entreprise</h5>
                <a href="#" class="btn btn-icon btn-outline-warning btn-sm" data-toggle="modal" id="mediumButton1" data-target="#mediumModal1"  data-attr="{{ route('tenants.edit', $tenant) }}" title="Modifier le client">
                    <i class="feather icon-edit"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-light-primary rounded-circle mx-auto p-3 mb-2 d-inline-block">
                        <i class="feather icon-briefcase text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5>{{ $tenant->company_name }}</h5>
                    @if($tenant->status === 'active')
                        <span class="badge badge-light-success">Actif</span>
                    @elseif($tenant->status === 'suspended')
                        <span class="badge badge-light-warning">Suspendu</span>
                    @else
                        <span class="badge badge-light-danger">{{ ucfirst($tenant->status ?? 'Inactif') }}</span>
                    @endif
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-hash mr-2"></i> ID Tenant :</span>
                        <code>{{ $tenant->id }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-mail mr-2"></i> Email Contact :</span>
                        <strong>{{ $tenant->email }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-calendar mr-2"></i> Date d'inscription :</span>
                        <span>{{ $tenant->created_at ? $tenant->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-block btn-sm">
                    <i class="feather icon-arrow-left mr-1"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Sous-domaines associés -->
        <div class="card">
            <div class="card-header">
                <h5>Accès & Sous-domaines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @forelse($tenant->domains as $domain)
                        <li class="mb-2 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <i class="feather icon-globe text-primary mr-2"></i>
                                <span class="font-weight-bold">{{ $domain->domain }}</span>
                            </div>
                            <a href="http://{{ $domain->domain }}" target="_blank" class="btn btn-icon btn-sm btn-outline-primary" title="Ouvrir le domaine">
                                <i class="feather icon-external-link"></i>
                            </a>
                        </li>
                    @empty
                        <li class="text-muted text-center py-2">
                            <i class="feather icon-alert-circle mr-1"></i> Aucun sous-domaine rattaché.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Abonnements & Factures -->
    <div class="col-md-8">
        <!-- Historique des Abonnements -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Historique des Abonnements</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Plan Tarifaire</th>
                                <th>Statut</th>
                                <th>Début de période</th>
                                <th>Échéance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenant->subscriptions as $subscription)
                                <tr>
                                    <td>
                                        <strong>{{ $subscription->plan->name ?? 'Plan inconnu' }}</strong>
                                        <small class="d-block text-muted">
                                            {{ number_format($subscription->plan->price ?? 0, 0, ',', ' ') }} {{ $subscription->plan->currency ?? 'XOF' }} / {{ $subscription->plan->invoice_period ?? 'mois' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($subscription->status === 'active')
                                            <span class="badge badge-light-success">Actif</span>
                                        @elseif($subscription->status === 'canceled')
                                            <span class="badge badge-light-danger">Annulé</span>
                                        @else
                                            <span class="badge badge-light-secondary">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscription->current_period_starts_at ? $subscription->current_period_starts_at->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $subscription->current_period_ends_at ? $subscription->current_period_ends_at->format('d/m/Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="feather icon-info mr-1"></i> Aucun abonnement trouvé pour ce client.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Historique des Factures -->
        <div class="card">
            <div class="card-header">
                <h5>Factures émises</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Montant</th>
                                <th>Échéance</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenant->invoices ?? [] as $invoice)
                                <tr>
                                    <td><code>{{ $invoice->number ?? '#INV-'.$invoice->id }}</code></td>
                                    <td><strong>{{ number_format($invoice->total ?? 0, 0, ',', ' ') }} {{ $invoice->currency ?? 'XOF' }}</strong></td>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if(($invoice->status ?? '') === 'paid')
                                            <span class="badge badge-light-success">Payée</span>
                                        @else
                                            <span class="badge badge-light-warning">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="feather icon-file-text mr-1"></i> Aucune facture enregistrée pour ce client.
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