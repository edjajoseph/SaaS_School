@extends('School::layouts.app3', [
    'namePage' => 'Journal de Caisse Quotidien',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'daily_cash',
    'activeModule' => 'daily_cash',
])

@section('content')
<div class="container-fluid py-3">

    <!-- En-tête de la page -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Journal de Caisse Quotidien
                </h3>
                <p class="text-muted small mb-0">Contrôle quotidien des encaissements et suivi des modes de règlement</p>
            </div>
            <div class="col-md-6 text-right">
                <!-- Télécharger le PV de Clôture PDF -->
                <a href="{{ route('school.accounting.reports.export.pdf.daily-cash-pv', ['date' => request('date', date('Y-m-d'))]) }}" 
                class="btn btn-danger btn-sm shadow-sm mr-2" target="_blank">
                    <i class="fa fa-file-pdf-o mr-1"></i> Télécharger PV de Clôture (PDF)
                </a>
                <a href="{{ route('school.accounting.reports.daily-cash') }}" class="btn btn-sm btn-warning shadow-sm ml-1">
                    <i class="fa fa-sync-alt-o mr-1"></i> Actualiser
                </a>
            </div>
        </div>
    </div>

    <!-- Barre de Filtre par Date -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 bg-white rounded">
            <form method="GET" action="{{ route('school.accounting.reports.daily-cash') }}" class="form-inline">
                <label for="date" class="mr-3 font-weight-bold text-dark">
                    <i class="far fa-calendar-alt mr-1 text-muted"></i> Date du journal :
                </label>
                <input type="date" id="date" name="date" value="{{ $date }}" class="form-control form-control-sm mr-3 shadow-none border" onchange="this.form.submit()">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-filter mr-1"></i> Filtrer
                </button>
            </form>
        </div>
    </div>

    <!-- Cartes KPI à Dégradés Style Able Pro -->
    <div class="row mb-4">
        <!-- Espèces -->
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-gradient-cyan border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Total Espèces (Caisse)</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1">{{ number_format($totalCash, 0, ',', ' ') }} <small class="h6 text-white-50">FCFA</small></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-money fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Autres Modes (Banque / Mobile Money) -->
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card card-gradient-purple border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Banque & Mobile Money</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1">{{ number_format($totalOther, 0, ',', ' ') }} <small class="h6 text-white-50">FCFA</small></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-mobile fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Général -->
        <div class="col-md-4">
            <div class="card card-gradient-green border-0 shadow-sm text-white">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 font-weight-bold text-uppercase small">Total Général Encaissé</span>
                            <h2 class="mb-0 font-weight-bold text-white mt-1">{{ number_format($grandTotal, 0, ',', ' ') }} <small class="h6 text-white-50">FCFA</small></h2>
                        </div>
                        <div class="icon-circle bg-white-20">
                            <i class="fa fa-wallet fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau de l'Échéancier / Encaissements -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="card-title mb-0 font-weight-bold text-dark">
                <i class="fas fa-list-alt text-muted mr-2"></i>Détails des Encaissements du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="dt-responsive table-responsive">
                <table id="basic-btn" class="table table-striped table-bordered nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-top-0">N° Reçu</th>
                            <th class="border-top-0">Heure</th>
                            <th class="border-top-0">Matricule & Étudiant</th>
                            <th class="border-top-0">Classe</th>
                            <th class="border-top-0">Mode</th>
                            <th class="border-top-0">Référence / Chèque</th>
                            <th class="border-top-0 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                            @php
                                $student = $receipt->account->registration->student ?? null;
                                $fullName = trim(
                                    ($student->personne->nom ?? $student->last_name ?? '') . ' ' . 
                                    ($student->personne->prenom ?? $student->first_name ?? '')
                                );
                            @endphp
                            <tr>
                                <td class="font-weight-bold text-primary">
                                    {{ $receipt->reference ?? 'REC-' . sprintf('%05d', $receipt->id) }}
                                </td>
                                <td class="text-muted small">
                                    <i class="far fa-clock mr-1"></i>{{ $receipt->created_at->format('H:i') }}
                                </td>
                                <td>
                                    <strong class="text-dark d-block">
                                        {{ !empty($fullName) ? $fullName : ($student->name ?? 'N/A') }}
                                    </strong>
                                    <small class="text-muted">{{ $student->matricule ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark">
                                        {{ $receipt->account->registration->schoolClass->name ?? $receipt->account->registration->schoolClass->libelle ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @php $mode = strtoupper($receipt->payment_mode ?? 'CASH'); @endphp
                                    @if(in_array($mode, ['CASH', 'ESPÈCES', 'ESPECES']))
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-money-bill mr-1"></i>Espèces</span>
                                    @elseif(in_array($mode, ['WAVE', 'OM', 'MTN', 'MOBILE_MONEY']))
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-mobile-alt mr-1"></i>{{ $mode }}</span>
                                    @else
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-university mr-1"></i>{{ $mode }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $receipt->reference ?? '-' }}
                                </td>
                                <td class="text-right font-weight-bold text-success">
                                    {{ number_format($receipt->amount, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-50"></i>
                                    Aucun encaissement enregistré pour cette date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Styles Gradients Able Pro + Support Impression Bootstrap 4 -->
<style>
.card-gradient-cyan { background: linear-gradient(45deg, #1de9b6, #1dc4e9) !important; }
.card-gradient-purple { background: linear-gradient(45deg, #899FD4, #A389D4) !important; }
.card-gradient-green { background: linear-gradient(45deg, #2ed8b6, #59e0c5) !important; }
.card-gradient-orange { background: linear-gradient(45deg, #FFB64D, #ffCB80) !important; }
.card-gradient-red { background: linear-gradient(45deg, #FF5370, #ff869a) !important; }

.bg-white-20 {
    background-color: rgba(255, 255, 255, 0.2);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media print {
    body { background: #fff !important; color: #000 !important; }
    .btn, form, nav, .sidebar, .page-header button { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
    .table-responsive { overflow: visible !important; }
}
</style>
@endsection