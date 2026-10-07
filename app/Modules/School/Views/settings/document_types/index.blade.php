@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Types de Documents',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'document_types',
    'activeModule' => 'enseignement',
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
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-file-text mr-2"></i>LISTE DES TYPES DE DOCUMENTS
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('settings.rh.document_types.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Créer un type de document">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Type
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

                    <!-- Barre de recherche et filtres -->
                    <form method="GET" action="{{ route('settings.rh.document_types.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou code..." value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3">
                                <select name="is_active" class="form-control">
                                    <option value="">-- Tous les états --</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block waves-effect">
                                    <i class="fa fa-filter mr-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="10%">Code</th>
                                    <th>Nom du document</th>
                                    <th width="15%">Caractère</th>
                                    <th width="10%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentTypes as $documentType)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $documentType->code ?: 'N/A' }}</span></td>
                                        <td class="font-weight-bold">{{ $documentType->name }}</td>
                                        <td>
                                            @if($documentType->is_required)
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-exclamation-circle mr-1"></i>Obligatoire</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Optionnel</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($documentType->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Activer / Désactiver -->
                                                <form action="{{ route('settings.rh.document_types.toggle-status', $documentType->id) }}" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous vraiment {{ $documentType->is_active ? 'désactiver' : 'activer' }} ce type de document ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '{{ $documentType->is_active ? '#f8ac59' : '#1ab394' }}',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, {{ $documentType->is_active ? 'désactiver' : 'activer' }} !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    @if($documentType->is_active)
                                                        <button type="submit" class="btn btn-warning btn-mini mr-1" title="Désactiver">
                                                            <i class="ti-close"></i> 
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-success btn-mini mr-1" title="Activer">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('settings.rh.document_types.show', $documentType->id) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('settings.rh.document_types.edit', $documentType->id) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('settings.rh.document_types.destroy', $documentType->id) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera ce type de document !',
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
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun type de document enregistré pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $documentTypes->links() }}
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
                <h5 class="modal-title text-primary"><i class="fa fa-file-text"></i> GESTION TYPE DE DOCUMENT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                <h5 class="modal-title text-info"><i class="fa fa-file-text"></i> DÉTAILS TYPE DE DOCUMENT</h5>
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
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION TYPE DE DOCUMENT</h5>
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