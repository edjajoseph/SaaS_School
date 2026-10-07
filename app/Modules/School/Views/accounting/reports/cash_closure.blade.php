@extends('School::layouts.app3', [
    'namePage' => 'Arrêté de Caisse',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'cash-closure',
    'activeModule' => 'accounting',
])

@section('content')
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">                
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1 font-weight-bold text-dark">
                            <i class="fa fa-calculator text-success mr-2"></i>Arrêté de Caisse Journalier
                        </h3>
                        <p class="text-muted small mb-0">Ventilation des encaissements par mode de paiement et fermeture de caisse</p>
                    </div>
                </div>               
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Formulaire de sélection de date & Année Scolaire -->
                    <form action="{{ route('school.accounting.reports.cash-closure') }}" method="GET" class="row align-items-end mb-4">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Date d'arrêté</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold">Année Scolaire / Budgétaire</label>
                            <select name="academic_year_id" class="form-control select2">
                                <option value="">-- Toutes les années --</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->name ?? $year->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm text-white">
                                <i class="fa fa-filter mr-1"></i> Filtrer
                            </button>
                            <a href="{{ route('school.accounting.reports.cash-closure-pdf', ['date' => $date, 'academic_year_id' => $academicYearId]) }}" target="_blank" class="btn btn-danger btn-round waves-effect shadow-sm text-white" title="Imprimer le PV">
                                <i class="fa fa-file-pdf-o mr-1"></i> PDF
                            </a>
                        </div>
                    </form>

                    <!-- Ventilation par Mode -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="p-3 bg-success text-white rounded shadow-sm">
                                <small class="text-uppercase font-weight-bold"><i class="fas fa-money-bill-wave mr-1"></i> Espèces (CASH)</small>
                                <h4 class="mb-0 mt-1 font-weight-bold">{{ number_format($byMode['CASH'], 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-info text-white rounded shadow-sm">
                                <small class="text-uppercase font-weight-bold"><i class="fas fa-mobile-alt mr-1"></i> Mobile Money / TPE</small>
                                <h4 class="mb-0 mt-1 font-weight-bold">{{ number_format($byMode['MOBILE_MONEY'], 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-warning text-white rounded shadow-sm">
                                <small class="text-uppercase font-weight-bold"><i class="fas fa-money-check mr-1"></i> Chèques</small>
                                <h4 class="mb-0 mt-1 font-weight-bold">{{ number_format($byMode['CHECK'], 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-primary text-white rounded shadow-sm">
                                <small class="text-uppercase font-weight-bold"><i class="fas fa-university mr-1"></i> Virements</small>
                                <h4 class="mb-0 mt-1 font-weight-bold">{{ number_format($byMode['TRANSFER'], 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 font-weight-bold text-dark">Détail des Encaisses (Total : {{ number_format($totalCollected, 0, ',', ' ') }} FCFA)</h5>
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="10%">Heure</th>
                                    <th width="15%">Reçu</th>
                                    <th width="30%">Étudiant</th>
                                    <th width="15%">Classe</th>
                                    <th width="15%">Mode</th>
                                    <th width="15%" class="text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $p)
                                    <tr>
                                        <td>{{ $p->created_at->format('H:i') }}</td>
                                        <td class="font-weight-bold text-success">{{ $p->receipt_number ?? $p->reference }}</td>
                                        <td>{{ $p->account->registration->student->personne->nom ?? '' }} {{ $p->account->registration->student->personne->prenoms ?? '' }}</td>
                                        <td>{{ $p->account->registration->schoolClass->name ?? '' }}</td>
                                        <td><span class="badge badge-secondary">{{ $p->payment_method ?? $p->payment_mode }}</span></td>
                                        <td class="text-right font-weight-bold text-dark">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucun encaissement à cette date.</td>
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
@endsection