@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'enseignement',
])

@section('content')

<!-- Zone d'affichage des Alertes (Validation & Session) -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-exclamation-triangle mr-1"></i> Erreur :</strong> {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-check-circle mr-1"></i> Succès :</strong> {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                Compte Financier : {{ $account->registration->student->personne->nom ?? '' }} {{ $account->registration->student->personne->prenoms ?? '' }}
            </h1>
            <p class="text-muted mb-0">
                Classe : <strong>{{ $account->registration->schoolClass->label ?? $account->registration->schoolClass->name ?? 'N/A' }}</strong> 
                | Matricule : <strong>{{ $account->registration->student->matricule ?? 'N/A' }}</strong>
            </p>
        </div>
        <a href="{{ route('school.accounting.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fa fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Informations Étudiant & Résumé Caisse -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Récapitulatif Financier</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Dû (Brut) :</span> 
                            <strong>{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-info">
                            <span>Remise / Réduction :</span> 
                            <strong>- {{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between font-weight-bold">
                            <span>Net à Payer :</span> 
                            <strong>{{ number_format(max(0, $account->total_due - $account->discount_amount), 0, ',', ' ') }} FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span>Total Payé :</span> 
                            <strong>{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-danger h6">
                            <span>Solde Restant :</span> 
                            <strong>{{ number_format(max(0, ($account->total_due - $account->discount_amount) - $account->total_paid), 0, ',', ' ') }} FCFA</strong>
                        </li>
                    </ul>

                    <!-- Formulaire Nouveau Versement -->
                    <h6 class="font-weight-bold text-primary mt-4">Nouveau Versement</h6><br>
                    <form method="POST" action="{{ route('school.accounting.store', $account->id) }}">
                        @csrf

                        <!-- Sélecteur du Journal de Trésorerie -->
                        <div class="form-group">
                            <label for="journal_id">Journal de Trésorerie <span class="text-danger">*</span></label>
                            <select name="journal_id" id="journal_id" class="form-control" required>
                                <option value="">-- Sélectionner la caisse / banque --</option>
                                @foreach($treasuryJournals as $j)
                                    <option value="{{ $j->id }}" {{ old('journal_id') == $j->id ? 'selected' : '' }}>
                                        [{{ $j->code }}] {{ $j->name }}
                                        @if($j->defaultAccount)
                                            ({{ $j->defaultAccount->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Choisir l'échéance / le plan à régler -->
                        <div class="form-group">
                            <label for="student_fee_schedule_id">
                                Imputer sur la tranche / plan <span class="text-danger">*</span>
                            </label>
                            <select name="student_fee_schedule_id" id="student_fee_schedule_id" class="form-control" required>
                                <option value="">-- Sélectionner l'échéance à régler --</option>
                                @foreach($account->schedules as $schedule)
                                    @php
                                        $rawRemaining = $schedule->amount - $schedule->paid_amount;
                                        $remaining = min($rawRemaining, $account->balance);
                                    @endphp
                                    @if(!$schedule->is_paid && $rawRemaining > 0 && $account->balance > 0)
                                        <option value="{{ $schedule->id }}" data-amount="{{ $remaining }}">
                                            {{ $schedule->label }} (Reste à payer : {{ number_format($remaining, 0, ',', ' ') }} FCFA)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Montant (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Mode de règlement <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash">Espèces</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Virement bancaire</option>
                                <option value="cheque">Chèque</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Référence Transaction / N° Chèque</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="Ex: Wave / OM / N° Chèque">
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-check-circle mr-1"></i> Enregistrer l'encaissement
                        </button>
                    </form>

                    @if($account->total_due > 0 && $account->balance <= 0)
                        <div class="alert alert-success text-center font-weight-bold mt-3 mb-0">
                            Scolarité entièrement soldée !
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Échéancier et Historique des Paiements -->
        <div class="col-lg-8">
            <!-- Échéancier -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Échéancier de Paiement</h6>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th>Échéance / Libellé</th>
                                    <th>Date limite</th>
                                    <th>Montant Exigible</th>
                                    <th>Payé</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account->schedules as $sched)
                                    <tr>
                                        <td>{{ $sched->label }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sched->due_date)->format('d/m/Y') }}</td>
                                        <td>{{ number_format($sched->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($sched->paid_amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($sched->is_paid)
                                                <span class="badge badge-success">Réglé</span>
                                            @elseif($sched->paid_amount > 0)
                                                <span class="badge badge-warning">Incomplet</span>
                                            @else
                                                <span class="badge badge-danger">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Historique des Reçus -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des Encaissements</h6>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn1" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>N° Reçu</th>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($account->payments as $pay)
                                    <tr>
                                        <td><strong>{{ $pay->receipt_number }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($pay->paid_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ strtoupper($pay->payment_method) }}</td>
                                        <td class="font-weight-bold text-success">{{ number_format($pay->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            @if($pay->status === 'completed')
                                                <span class="badge badge-success">Validé</span>
                                            @else
                                                <span class="badge badge-danger">Annulé</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('school.accounting.receipt.pdf', $pay->id) }}" target="_blank" class="btn btn-sm btn-primary" title="Imprimer reçu">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Aucun versement effectué.</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scheduleSelect = document.getElementById('student_fee_schedule_id');
        const amountInput = document.getElementById('amount');

        if (scheduleSelect && amountInput) {
            scheduleSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const remainingAmount = selectedOption.getAttribute('data-amount');

                if (remainingAmount !== null && remainingAmount !== '') {
                    amountInput.value = remainingAmount;
                } else {
                    amountInput.value = '';
                }
            });
        }
    });
</script>
@endsection