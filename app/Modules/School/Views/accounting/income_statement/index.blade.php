@extends('School::layouts.app3', [
    'namePage' => 'Compte de Résultat',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'income_statement',
    'activeModule' => 'accounting',
])

@section('content')
<style>
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
                            <i class="fas fa-chart-line text-success mr-2"></i>Compte de Résultat
                        </h3>
                        <p class="text-muted small mb-0">Synthèse des charges (Classe 6) et des produits (Classe 7)</p>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('school.accounting.income-statement.export-pdf', request()->all()) }}" 
                        class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                        target="_blank" 
                        title="Imprimer / Exporter en PDF">
                            <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                        </a>
                    </div>
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    <form method="GET" action="{{ route('school.accounting.income-statement.index') }}" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-5">
                                <label class="font-weight-bold text-dark mb-1">Date Début</label>
                                <input type="date" name="start_date" class="form-control form-control-sm border shadow-none" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-5">
                                <label class="font-weight-bold text-dark mb-1">Date Fin</label>
                                <input type="date" name="end_date" class="form-control form-control-sm border shadow-none" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-sm btn-block shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Calculer
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <!-- Produits (Classe 7) -->
                        <div class="col-md-6">
                            <div class="card border border-success mb-3">
                                <div class="card-header bg-success text-white py-2">
                                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-arrow-down mr-2"></i> PRODUITS (Classe 7)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Libellé</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($revenues as $rev)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $rev->code }}</td>
                                                    <td>{{ $rev->label }}</td>
                                                    <td class="text-right font-weight-bold text-success">{{ number_format($rev->amount, 0, ',', ' ') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted">Aucun produit enregistré.</td></tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL PRODUITS :</td>
                                                <td class="text-right text-success h6 mb-0">{{ number_format($totalRevenues, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Charges (Classe 6) -->
                        <div class="col-md-6">
                            <div class="card border border-danger mb-3">
                                <div class="card-header bg-danger text-white py-2">
                                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-arrow-up mr-2"></i> CHARGES (Classe 6)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Libellé</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($expenses as $exp)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $exp->code }}</td>
                                                    <td>{{ $exp->label }}</td>
                                                    <td class="text-right font-weight-bold text-danger">{{ number_format($exp->amount, 0, ',', ' ') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted">Aucune charge enregistrée.</td></tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL CHARGES :</td>
                                                <td class="text-right text-danger h6 mb-0">{{ number_format($totalExpenses, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résultat Net -->
                    <div class="card bg-light border-0 mt-3">
                        <div class="card-body d-flex justify-content-between align-items-center py-3">
                            <h4 class="mb-0 font-weight-bold">RÉSULTAT NET DE L'EXERCICE :</h4>
                            <h3 class="mb-0 font-weight-bold {{ $netResult >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($netResult, 0, ',', ' ') }} FCFA 
                                <small>({{ $netResult >= 0 ? 'BÉNÉFICE' : 'PERTE' }})</small>
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection