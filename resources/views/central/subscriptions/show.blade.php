<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="m-b-10">Souscription #{{ $subscription->id }}</h5>
                <p class="text-muted mb-0">Client : <strong>{{ $subscription->tenant->name ?? $subscription->tenant_id }}</strong></p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                    <i class="feather icon-arrow-left mr-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations Générales de la Souscription -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="feather icon-info mr-2"></i>Résumé de l'Abonnement</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Statut</span>
                        @if($subscription->isActive())
                            <span class="badge badge-success">ACTIF</span>
                        @elseif($subscription->status === 'trialing')
                            <span class="badge badge-info">ESSAI</span>
                        @elseif($subscription->status === 'canceled')
                            <span class="badge badge-danger">ANNULÉ</span>
                        @else
                            <span class="badge badge-secondary">{{ strtoupper($subscription->status) }}</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Client / Tenant</span>
                        <strong>{{ $subscription->tenant->name ?? $subscription->tenant_id }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Solution SaaS</span>
                        <span class="badge badge-light-primary">{{ $subscription->solution->nom ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Plan / Offre</span>
                        <strong>{{ $subscription->plan->name ?? 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Date de Début</span>
                        <span>{{ $subscription->starts_at ? $subscription->starts_at->format('d/m/Y') : '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Fin de Période</span>
                        <span>{{ $subscription->current_period_ends_at ? $subscription->current_period_ends_at->format('d/m/Y') : '-' }}</span>
                    </li>
                    @if($subscription->trial_ends_at)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-info">
                            <span>Fin d'Essai</span>
                            <span>{{ $subscription->trial_ends_at->format('d/m/Y') }}</span>
                        </li>
                    @endif
                    @if($subscription->canceled_at)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 text-danger">
                            <span>Annulé le</span>
                            <span>{{ $subscription->canceled_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Détails du Plan Souscrit & Factures associées -->
    <div class="col-md-8">
        <!-- Carte Spécifications du Plan -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="feather icon-box mr-2"></i>Détails du Plan Souscrit</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <p class="text-muted mb-1">Tarif Mensuel</p>
                        <h4>{{ number_format($subscription->plan->price_monthly ?? 0, 0, ',', ' ') }} <small>XOF / mois</small></h4>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Tarif Annuel</p>
                        <h4>{{ number_format($subscription->plan->price_yearly ?? 0, 0, ',', ' ') }} <small>XOF / an</small></h4>
                    </div>
                </div>

                @if(!empty($subscription->plan->features))
                    <hr>
                    <h6>Fonctionnalités Incluses :</h6>
                    <div class="row mt-3">
                        @foreach($subscription->plan->features as $key => $value)
                            <div class="col-md-6 mb-2">
                                <i class="feather icon-check-circle text-success mr-2"></i>
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ is_bool($value) ? ($value ? 'Oui' : 'Non') : $value }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Carte Historique des Factures -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="feather icon-file-text mr-2"></i>Factures Liées</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NUMÉRO</th>
                                <th>DATE</th>
                                <th>MONTANT TTC</th>
                                <th>STATUT</th>
                                <th class="text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscription->invoices as $invoice)
                                <tr>
                                    <td><strong>{{ $invoice->number }}</strong></td>
                                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td>{{ number_format($invoice->amount_ttc, 0, ',', ' ') }} {{ $invoice->currency }}</td>
                                    <td>
                                        @if($invoice->status === 'paid')
                                            <span class="badge badge-success">PAYÉE</span>
                                        @elseif($invoice->status === 'pending')
                                            <span class="badge badge-warning">EN ATTENTE</span>
                                        @else
                                            <span class="badge badge-danger">{{ strtoupper($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="#" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2"  data-attr="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                            <i class="feather icon-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Aucune facture enregistrée pour cette souscription.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mediumModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-warning">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion saas & Clients</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody2">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>