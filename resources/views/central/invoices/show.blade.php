<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Détails de la Facture</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Factures</a></li>
                    <li class="breadcrumb-item">{{ $invoice->number ?? '#INV-'.$invoice->id }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Détails de la facture -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Facture : {{ $invoice->number ?? '#INV-'.$invoice->id }}</h5>
                <div>
                    @if($invoice->status === 'paid')
                        <span class="badge badge-success p-2">PAYÉE</span>
                    @elseif($invoice->status === 'pending')
                        <span class="badge badge-warning p-2">EN ATTENTE</span>
                    @else
                        <span class="badge badge-danger p-2">{{ strtoupper($invoice->status) }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted">Informations Client :</h6>
                        <h5 class="mb-1">{{ $invoice->tenant->name ?? $invoice->tenant_id }}</h5>
                        <p class="text-muted mb-0">{{ $invoice->tenant->email ?? '' }}</p>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <h6 class="text-muted">Dates :</h6>
                        <p class="mb-1"><strong>Créée le :</strong> {{ $invoice->created_at->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Échéance :</strong> {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</p>
                        @if($invoice->paid_at)
                            <p class="mb-0 text-success"><strong>Réglée le :</strong> {{ $invoice->paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @php
                    // Calcul du pourcentage de TVA effectif pour l'affichage du libellé (ex: 18%)
                    $effectiveTaxRate = ($invoice->amount_ht > 0) ? round(($invoice->tax_amount / $invoice->amount_ht) * 100, 2) : 0;
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>DÉSIGNATION</th>
                                <th class="text-right" style="width: 250px;">MONTANT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Abonnement / Services SaaS</td>
                                <td class="text-right">
                                    {{ number_format($invoice->amount_ht, $invoice->amount_ht == floor($invoice->amount_ht) ? 0 : 2, ',', ' ') }} {{ $invoice->currency }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">Total Hors Taxe (HT)</th>
                                <th class="text-right">
                                    {{ number_format($invoice->amount_ht, $invoice->amount_ht == floor($invoice->amount_ht) ? 0 : 2, ',', ' ') }} {{ $invoice->currency }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right">
                                    Montant TVA 
                                    <small class="text-muted">({{ number_format($invoice->tax_rate, $invoice->tax_rate == floor($invoice->tax_rate) ? 0 : 2) }}%)</small>
                                </th>
                                <th class="text-right">
                                    {{ number_format($invoice->tax_amount, $invoice->tax_amount == floor($invoice->tax_amount) ? 0 : 2, ',', ' ') }} {{ $invoice->currency }}
                                </th>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-right text-primary">Total TTC</th>
                                <th class="text-right text-primary">
                                    <h5 class="mb-0 text-primary">
                                        {{ number_format($invoice->amount_ttc, $invoice->amount_ttc == floor($invoice->amount_ttc) ? 0 : 2, ',', ' ') }} {{ $invoice->currency }}
                                    </h5>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panneau Enregistrement d'un Paiement -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Règlement de la Facture</h5>
            </div>
            <div class="card-body">
                @if($invoice->status !== 'paid')
                    <form action="{{ route('invoices.payments.store', $invoice) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Montant à encaisser (TTC)</label>
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01"
                                       name="amount" 
                                       id="amount" 
                                       class="form-control" 
                                       value="{{ old('amount', $invoice->amount_ttc) }}" 
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $invoice->currency }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Moyen de Paiement <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="bank_transfer">Virement Bancaire</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="card">Carte Bancaire</option>
                                <option value="cash">Espèces</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="transaction_id">N° Transaction / Référence</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="ex: TRX-99882">
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="feather icon-check-circle mr-1"></i> Valider le Paiement
                        </button>
                    </form>
                @else
                    <div class="text-center py-4 text-success">
                        <i class="feather icon-check-circle" style="font-size: 3.5rem;"></i>
                        <h5 class="mt-3 text-success">Facture Réglée</h5>
                        <p class="text-muted small mb-0">Le règlement a été intégralement enregistré.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>