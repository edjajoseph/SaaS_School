@extends('School::layouts.app3', [
    'namePage' => 'Journal des Saisies',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'accounting_entries',
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
                            <i class="fas fa-receipt text-primary mr-2"></i>Journal des Saisies & Écritures
                        </h3>
                        <p class="text-muted small mb-0">Historique chronologique des pièces comptables enregistrées</p>
                    </div>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('school.accounting.entries.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Saisir une Pièce">
                        <i class="ace-icon fa fa-pen-nib mr-1"></i> Saisir une Pièce
                    </a>
                </div>               
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Notification SweetAlert Flash Session -->
                    <script class="alert alert-success" role="alert">
                        @if($message = session('success'))
                            Swal.fire({ icon: 'success', title: 'Félicitations !', text: '{{ $message }}', confirmButtonColor: '#1ab394' });
                        @elseif($message = session('warning'))
                            Swal.fire({ icon: 'warning', title: 'Attention !', text: '{{ $message }}', confirmButtonColor: '#f8ac59' });
                        @elseif($message = session('error'))
                            Swal.fire({ icon: 'error', title: 'Désolé !', text: '{{ $message }}', confirmButtonColor: '#ed5565' });
                        @endif
                    </script>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>N° Pièce</th>
                                    <th>Journal</th>
                                    <th>Libellé Général</th>
                                    <th class="text-right">Total Débit</th>
                                    <th class="text-right">Total Crédit</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entries as $entry)
                                    @php
                                        $totalDebit = $entry->items->sum('debit');
                                        $totalCredit = $entry->items->sum('credit');
                                    @endphp
                                    <tr>
                                        <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                                        <td class="font-weight-bold text-primary">{{ $entry->entry_number }}</td>
                                        <td><span class="badge badge-light border text-dark">{{ $entry->journal->code ?? 'N/A' }}</span></td>
                                        <td class="font-weight-bold">{{ $entry->label }}</td>
                                        <td class="text-right font-weight-bold text-success">{{ number_format($totalDebit, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right font-weight-bold text-danger">{{ number_format($totalCredit, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Consulter le détail -->
                                                <a class="btn btn-warning btn-mini mr-1" data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('school.accounting.entries.show', $entry->id) }}" title="Détail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <!-- Bouton Édition en Modal AJAX -->
                                                <a class="btn btn-info btn-mini mr-1 text-white" 
                                                data-toggle="modal" 
                                                id="mediumButton2" 
                                                data-target="#mediumModal2" 
                                                data-attr="{{ route('school.accounting.entries.edit', $entry->id) }}" 
                                                title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('school.accounting.entries.destroy', $entry->id) }}" method="POST" class="d-inline" 
                                                        onsubmit="event.preventDefault(); Swal.fire({ title: 'Voulez-vous supprimer cette écriture ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ed5565', confirmButtonText: 'Oui' }).then((r) => { if (r.isConfirmed) this.submit(); });">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-mini" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Aucune pièce comptable enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-primary"><i class="fa fa-pen-nib mr-2"></i> SAISIE D'UNE PIÈCE COMPTABLE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-warning"><i class="fa fa-receipt mr-2"></i> DÉTAIL DE LA PIÈCE COMPTABLE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-success"><i class="fa fa-receipt mr-2"></i> MODIFIER UNE PIÈCE COMPTABLE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
@endsection