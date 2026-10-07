<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Plan : {{ $plan->name }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Plans & Tarifs</a></li>
                    <li class="breadcrumb-item">{{ $plan->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Métriques & Infos Générales -->
    <div class="col-md-4">
        <!-- Carte de présentation du plan -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Aperçu du Plan</h5>
                @if($plan->is_active)
                    <span class="badge badge-light-success">Actif</span>
                @else
                    <span class="badge badge-light-danger">Inactif</span>
                @endif
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2 class="display-4 font-weight-bold text-primary mb-0">
                        {{ number_format($plan->price, 0, ',', ' ') }}
                        <small class="h6 text-muted">{{ $plan->currency }}</small>
                    </h2>
                    <span class="text-muted text-uppercase font-weight-bold">
                        / {{ $plan->invoice_period === 'monthly' ? 'Mois' : 'An' }}
                    </span>
                </div>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-layers mr-2"></i> Solution SaaS :</span>
                        <strong>{{ $plan->solution->nom ?? 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-tag mr-2"></i> Slug :</span>
                        <code>{{ $plan->slug }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-users mr-2"></i> Abonnés Actifs :</span>
                        <span class="badge badge-primary rounded-pill">
                            {{ $plan->subscriptions->where('status', 'active')->count() }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted"><i class="feather icon-calendar mr-2"></i> Date de création :</span>
                        <span>{{ $plan->created_at ? $plan->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </li>
                </ul>

                @if($plan->description)
                    <div class="border-top pt-3">
                        <h6 class="text-muted">Description & Caractéristiques :</h6>
                        <p class="text-muted mb-0">{!! nl2br(e($plan->description)) !!}</p>
                    </div>
                @endif
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="feather icon-arrow-left mr-1"></i> Retour
                </a>
                <a href="{{ route('plans.edit', $plan) }}" class="btn btn-warning btn-sm">
                    <i class="feather icon-edit mr-1"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des souscripteurs à ce plan -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Entreprises Abonnées ({{ $plan->subscriptions->count() }})</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Client / Entreprise</th>
                                <th>Domaine</th>
                                <th>Statut Abonnement</th>
                                <th>Échéance</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plan->subscriptions as $subscription)
                                <tr>
                                    <td>
                                        @if($subscription->tenant)
                                            <div class="d-inline-block align-middle">
                                                <h6 class="mb-0">{{ $subscription->tenant->company_name }}</h6>
                                                <small class="text-muted">{{ $subscription->tenant->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Tenant introuvable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->tenant && $subscription->tenant->domains->first())
                                            <a href="http://{{ $subscription->tenant->domains->first()->domain }}" target="_blank" class="badge badge-light-primary">
                                                {{ $subscription->tenant->domains->first()->domain }}
                                                <i class="feather icon-external-link ml-1"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">Aucun domaine</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->status === 'active')
                                            <span class="badge badge-light-success">Actif</span>
                                        @elseif($subscription->status === 'canceled')
                                            <span class="badge badge-light-danger">Annulé</span>
                                        @elseif($subscription->status === 'past_due')
                                            <span class="badge badge-light-warning">Impayé</span>
                                        @else
                                            <span class="badge badge-light-secondary">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $subscription->current_period_ends_at ? $subscription->current_period_ends_at->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @if($subscription->tenant)
                                            <a href="{{ route('tenants.show', $subscription->tenant) }}" class="btn btn-icon btn-outline-info btn-sm" title="Voir le client">
                                                <i class="feather icon-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="feather icon-info mr-1"></i> Aucun client n'est actuellement abonné à ce plan.
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