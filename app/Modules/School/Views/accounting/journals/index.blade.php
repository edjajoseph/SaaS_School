@extends('School::layouts.app3', [
    'namePage' => 'Journaux Comptables',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'journals',
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
                            <i class="fas fa-book text-success mr-2"></i>Journaux Comptables
                        </h3>
                        <p class="text-muted small mb-0">Définition des codes journaux d'encaissement et de trésorerie</p>
                    </div>
                    
                    <a data-header-class="bg-success text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('school.accounting.journals.create') }}" class="btn btn-success btn-round waves-effect shadow-sm text-white" title="Nouveau Journal">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Journal
                    </a>
                </div>               
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="12%">Code</th>
                                    <th width="35%">Intitulé du Journal</th>
                                    <th width="15%">Type</th>
                                    <th width="23%">Compte par Défaut</th>
                                    <th width="15%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($journals as $j)
                                    <tr>
                                        <td class="font-weight-bold text-success">{{ $j->code }}</td>
                                        <td class="font-weight-bold">{{ $j->name }}</td>
                                        <td>
                                            @switch($j->type)
                                                @case('bank')
                                                    <span class="badge badge-info"><i class="fas fa-university mr-1"></i> Banque</span>
                                                    @break
                                                @case('cash')
                                                    <span class="badge badge-warning text-white"><i class="fas fa-money-bill-wave mr-1"></i> Caisse</span>
                                                    @break
                                                @case('sales')
                                                    <span class="badge badge-success"><i class="fas fa-shopping-cart mr-1"></i> Ventes</span>
                                                    @break
                                                @case('purchase')
                                                    <span class="badge badge-primary"><i class="fas fa-truck mr-1"></i> Achats</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary"><i class="fas fa-file-alt mr-1"></i> OD</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($j->defaultAccount)
                                                <span class="font-weight-bold text-dark">{{ $j->defaultAccount->code }}</span> 
                                                <small class="text-muted">({{ $j->defaultAccount->label }})</small>
                                            @else
                                                <span class="text-muted font-italic">- Aucun -</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('school.accounting.journals.edit', $j->id) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="{{ route('school.accounting.journals.destroy', $j->id) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera ce journal comptable !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Aucun journal configuré.</td>
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

<!-- Modales AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-info"><i class="fa fa-book mr-2"></i> CREATION JOURNAL COMPTABLE</h5>
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

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-edit mr-2"></i> MODIFICATION DU JOURNAL</h5>
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