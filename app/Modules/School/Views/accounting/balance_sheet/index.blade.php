@extends('School::layouts.app3', [
    'namePage' => 'Bilan Comptable',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'balance_sheet',
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
                            <i class="fas fa-university text-warning mr-2"></i>Bilan Comptable Patrimonial
                        </h3>
                        <p class="text-muted small mb-0">Situation patrimoniale arrêtée à une date précise</p>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('school.accounting.balance-sheet.export-pdf', request()->all()) }}" 
                        class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                        target="_blank" 
                        title="Imprimer / Exporter en PDF">
                            <i class="fas fa-file-pdf mr-1"></i> Imprimer PDF
                        </a>
                    </div>
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    <form method="GET" action="{{ route('school.accounting.balance-sheet.index') }}" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-9">
                                <label class="font-weight-bold text-dark mb-1">Arrêté au :</label>
                                <input type="date" name="as_of_date" class="form-control form-control-sm border shadow-none" value="{{ $asOfDate }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-warning text-white btn-sm btn-block shadow-sm">
                                    <i class="fas fa-search mr-1"></i> Générer le Bilan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <!-- ACTIF -->
                        <div class="col-md-6">
                            <div class="card border border-primary mb-3">
                                <div class="card-header bg-primary text-white py-2">
                                    <h5 class="mb-0 font-weight-bold">ACTIF (Emplois)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Rubrique</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($assets as $ast)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $ast->code }}</td>
                                                    <td>{{ $ast->label }}</td>
                                                    <td class="text-right font-weight-bold">{{ number_format($ast->balance, 0, ',', ' ') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted py-3">Aucun compte d'actif mouvementé.</td></tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL ACTIF :</td>
                                                <td class="text-right text-primary h6 mb-0">{{ number_format($totalAssets, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- PASSIF -->
                        <div class="col-md-6">
                            <div class="card border border-dark mb-3">
                                <div class="card-header bg-dark text-white py-2">
                                    <h5 class="mb-0 font-weight-bold">PASSIF (Ressources)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Rubrique</th>
                                                <th class="text-right">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($liabilities as $liab)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $liab->code }}</td>
                                                    <td>{{ $liab->label }}</td>
                                                    <td class="text-right font-weight-bold">{{ number_format($liab->balance, 0, ',', ' ') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted py-3">Aucun compte de passif mouvementé.</td></tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">TOTAL PASSIF :</td>
                                                <td class="text-right text-dark h6 mb-0">{{ number_format($totalLiabilities, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection