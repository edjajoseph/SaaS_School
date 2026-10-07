@extends('School::layouts.app3', [
    'namePage' => 'Rapprochement Bancaire',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'bank_reconciliation',
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
                            <i class="fa fa-exchange text-purple mr-2"></i>Rapprochement Bancaire
                        </h3>
                        <p class="text-muted small mb-0">Contrôle de concordance entre le relevé bancaire et le compte 52</p>
                    </div>

                    @if($bankAccountId)
                        <div class="d-flex align-items-center">
                            <a href="{{ route('school.accounting.bank-reconciliation.export-pdf', request()->all()) }}" 
                            class="btn btn-danger btn-round waves-effect shadow-sm text-white" 
                            target="_blank" 
                            title="Imprimer / Exporter en PDF">
                                <i class="fa fa-file-pdf-o mr-1"></i> Imprimer PDF
                            </a>
                        </div>
                    @endif
                </div>
                <hr class="hr-gradient">
                <div class="card-block">
                    
                    {{-- Alertes de succès ou d'erreur --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('school.accounting.bank-reconciliation.index') }}" class="mb-4">
                        <div class="form-row align-items-end">
                            <div class="col-md-5">
                                <label class="font-weight-bold text-dark mb-1">Compte Bancaire (52)</label>
                                <select name="chart_of_account_id" class="form-control form-control-sm border shadow-none" required>
                                    <option value="">-- Sélectionner un compte --</option>
                                    @foreach($bankAccounts as $bAcc)
                                        <option value="{{ $bAcc->id }}" {{ $bankAccountId == $bAcc->id ? 'selected' : '' }}>
                                            {{ $bAcc->code }} - {{ $bAcc->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold text-dark mb-1">Solde selon Relevé Bancaire</label>
                                <input type="number" step="any" name="bank_statement_balance" class="form-control form-control-sm border shadow-none" value="{{ $bankStatementBal }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info text-white btn-sm btn-block shadow-sm">
                                    <i class="fa fa-calculator mr-1"></i> Comparer
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($bankAccountId)
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light p-3 border text-center">
                                    <small class="text-muted uppercase font-weight-bold">Solde Comptable</small>
                                    <h4 class="font-weight-bold text-dark mb-0">{{ number_format($bookBalance, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light p-3 border text-center">
                                    <small class="text-muted uppercase font-weight-bold">Solde Relevé Bancaire</small>
                                    <h4 class="font-weight-bold text-info mb-0">{{ number_format($bankStatementBal, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light p-3 border text-center">
                                    <small class="text-muted uppercase font-weight-bold">Écart à Justifier</small>
                                    <h4 class="font-weight-bold {{ $gap == 0 ? 'text-success' : 'text-danger' }} mb-0">
                                        {{ number_format($gap, 0, ',', ' ') }} FCFA
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('school.accounting.bank-reconciliation.reconcile') }}">
                            @csrf
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="font-weight-bold mb-0">
                                    <i class="fa fa-list text-muted mr-1"></i> Écritures Non Rapprochées
                                </h5>
                                @if(count($unreconciledEntries) > 0)
                                    <button type="submit" class="btn btn-success btn-sm shadow-sm font-weight-bold">
                                        <i class="fa fa-check-circle mr-1"></i> Valider le rapprochement
                                    </button>
                                @endif
                            </div>

                            <div class="dt-responsive table-responsive">
                                <table class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="40">
                                                <input type="checkbox" id="selectAll">
                                            </th>
                                            <th>Date</th>
                                            <th>Libellé</th>
                                            <th class="text-right">Débit</th>
                                            <th class="text-right">Crédit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($unreconciledEntries as $uEntry)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="item_ids[]" value="{{ $uEntry->id }}" class="reconcile-checkbox">
                                                </td>
                                                <td>{{ date('d/m/Y', strtotime($uEntry->entry_date)) }}</td>
                                                <td>{{ $uEntry->label }}</td>
                                                <td class="text-right">{{ $uEntry->debit > 0 ? number_format($uEntry->debit, 0, ',', ' ') : '-' }}</td>
                                                <td class="text-right">{{ $uEntry->credit > 0 ? number_format($uEntry->credit, 0, ',', ' ') : '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">Toutes les écritures sont rapprochées.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.reconcile-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }
});
</script>
@endpush