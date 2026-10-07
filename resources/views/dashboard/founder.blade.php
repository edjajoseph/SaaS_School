@extends('layouts.central.app_back')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="m-b-10">Vue d'ensemble de la Plateforme SaaS</h5>
                        <p class="text-muted mb-0">Suivi des souscriptions, factures et performances financières.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="#" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{ route('subscriptions.create') }}" class="btn btn-primary">
                            <i class="feather icon-plus mr-1"></i> Nouvelle Souscription
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. CARTS KPI (Indicateurs clés) -->
        <div class="row">
            <!-- MRR -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-c-blue update-card text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-white m-b-5">MRR (Revenu Mensuel)</h6>
                                <h3 class="text-white font-weight-bold m-b-0">{{ number_format($mrr, 0, ',', ' ') }} <small style="font-size: 14px;">XOF</small></h3>
                            </div>
                            <div class="col-auto text-right">
                                <i class="feather icon-trending-up f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Encaissé -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-c-green update-card text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-white m-b-5">Chiffre d'Affaires Encaissé</h6>
                                <h3 class="text-white font-weight-bold m-b-0">{{ number_format($totalRevenue, 0, ',', ' ') }} <small style="font-size: 14px;">XOF</small></h3>
                            </div>
                            <div class="col-auto text-right">
                                <i class="feather icon-dollar-sign f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abonnements Actifs -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-c-yellow update-card text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-white m-b-5">Abonnements Actifs</h6>
                                <h3 class="text-white font-weight-bold m-b-0">{{ $activeSubscriptions }}</h3>
                            </div>
                            <div class="col-auto text-right">
                                <i class="feather icon-check-circle f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients / Tenants -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-c-red update-card text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-white m-b-5">Total Clients (Tenants)</h6>
                                <h3 class="text-white font-weight-bold m-b-0">{{ $totalTenants }}</h3>
                            </div>
                            <div class="col-auto text-right">
                                <i class="feather icon-users f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. SECTION ALERTES & PAIEMENTS EN ATTENTE -->
        @if($expiringSubscriptions->count() > 0 || $pendingInvoicesCount > 0)
        <div class="row mb-3">
            @if($expiringSubscriptions->count() > 0)
            <div class="col-md-6">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="feather icon-alert-triangle mr-2"></i>
                    <strong>{{ $expiringSubscriptions->count() }} abonnement(s)</strong> arrivent à expiration dans les 7 prochains jours.
                </div>
            </div>
            @endif

            @if($pendingInvoicesCount > 0)
            <div class="col-md-6">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="feather icon-clock mr-2"></i>
                    <strong>{{ $pendingInvoicesCount }} facture(s)</strong> sont actuellement en attente de paiement.
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- 3. TABLEAUX D'ACTIVITÉ RÉCENTE -->
        <div class="row">
            <!-- Dernières souscriptions -->
            <div class="col-xl-7 col-md-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="feather icon-user-check mr-2"></i>Dernières Souscriptions</h5>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-sm btn-link">Tout voir</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>CLIENT</th>
                                        <th>SOLUTION / PLAN</th>
                                        <th>ÉCHÉANCE</th>
                                        <th>STATUT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSubscriptions as $sub)
                                        <tr>
                                            <td><strong>{{ $sub->tenant->name ?? $sub->tenant_id }}</strong></td>
                                            <td>
                                                <small class="badge badge-light-primary">{{ $sub->solution->name ?? '-' }}</small><br>
                                                <strong>{{ $sub->plan->name ?? '-' }}</strong>
                                            </td>
                                            <td>{{ $sub->current_period_ends_at ? $sub->current_period_ends_at->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($sub->isActive())
                                                    <span class="badge badge-success">ACTIF</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ strtoupper($sub->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">Aucune activité récente.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Derniers règlements reçus -->
            <div class="col-xl-5 col-md-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="feather icon-credit-card mr-2"></i>Derniers Encaissements</h5>
                        <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-link">Toutes les factures</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>MODE</th>
                                        <th>MONTANT</th>
                                        <th>DATE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                        <tr>
                                            <td>
                                                <span class="badge badge-light-info">{{ strtoupper($payment->gateway) }}</span>
                                            </td>
                                            <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</strong></td>
                                            <td><small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3 text-muted">Aucun règlement récent.</td>
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
</div>
<div id="mediumModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header text-white bg-primary">
            <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="mediumBody">
            
        </div>
        <div class="modal-footer">
            
        </div>
    </div>
</div>
        </div>
@endsection