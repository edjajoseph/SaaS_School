@extends('School::layouts.app3', [
    'namePage' => 'Relevé de Compte Étudiant',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'student-statement',
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
                            <i class="fas fa-file-invoice-dollar text-success mr-2"></i>Relevé de Compte Étudiant
                        </h3>
                        <p class="text-muted small mb-0">Consultation de l'historique des paiements et solde individuel</p>
                    </div>
                </div>               
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Formulaire de filtre avec Année Scolaire -->
                    <form action="{{ route('school.accounting.reports.student-statement') }}" method="GET" class="row align-items-end mb-4">
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold">Sélectionner un Étudiant</label>
                            <select name="student_id" class="form-control select2" required>
                                <option value="">-- Choisir un étudiant --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}" {{ $studentId == $s->id ? 'selected' : '' }}>
                                        {{ $s->matricule }} - {{ $s->personne->nom ?? '' }} {{ $s->personne->prenoms ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
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
                                <i class="fa fa-search mr-1"></i> Afficher
                            </button>
                            @if($account)
                                <a href="{{ route('school.accounting.reports.student-statement-pdf', ['student_id' => $studentId, 'academic_year_id' => $academicYearId]) }}" target="_blank" class="btn btn-danger btn-round waves-effect shadow-sm text-white" title="Imprimer le relevé">
                                    <i class="fa fa-file-pdf-o mr-1"></i> PDF
                                </a>
                            @endif
                        </div>
                    </form>

                    @if($account)
                        <!-- KPIs de synthèse -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <small class="text-muted font-weight-bold">Total Attendu</small>
                                    <h4 class="mb-0 text-dark font-weight-bold">{{ number_format($account->total_due, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <small class="text-muted font-weight-bold">Remises & Exonérations</small>
                                    <h4 class="mb-0 text-warning font-weight-bold">{{ number_format($account->discount_amount, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <small class="text-muted font-weight-bold">Total Réglé</small>
                                    <h4 class="mb-0 text-success font-weight-bold">{{ number_format($account->total_paid, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <small class="text-muted font-weight-bold">Solde Restant</small>
                                    <h4 class="mb-0 text-danger font-weight-bold">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 font-weight-bold text-dark"><i class="fa fa-history text-info mr-2"></i>Historique des Versements</h5>
                        <div class="dt-responsive table-responsive">
                            <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="15%">Date & Heure</th>
                                        <th width="25%">Référence Reçu</th>
                                        <th width="20%">Mode de Règlement</th>
                                        <th width="20%" class="text-right">Montant Versement</th>
                                        <th width="20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($account->payments as $p)
                                        <tr>
                                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="font-weight-bold text-dark">{{ $p->receipt_number ?? $p->reference }}</td>
                                            <td><span class="badge badge-info"><i class="fas fa-credit-card mr-1"></i> {{ $p->payment_method ?? $p->payment_mode }}</span></td>
                                            <td class="text-right font-weight-bold text-success">{{ number_format($p->amount, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-center">
                                                <a href="{{ route('school.accounting.receipt.pdf', ['id' => $p->id]) }}" target="_blank" class="btn btn-warning btn-sm" title="Imprimer le reçu">
                                                    <i class="fa fa-print mr-1"></i> Reçu
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Aucun versement enregistré pour cet étudiant sur cette période.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>                                                
        </div>
    </div>
</div>
@endsection