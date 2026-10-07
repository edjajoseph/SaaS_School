@extends('School::layouts.app3', [
    'namePage' => 'Gestion des types de découpage',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'settings.academic.period_types',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
/* Style personnalisé pour rendre <hr> bien visible */
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

/* Ligne de séparation avec dégradé aux couleurs du thème */
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
                        <i class="feather icon-layers mr-2"></i>LISTE DES TYPES DE DÉCOUPAGE ACADÉMIQUE
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('settings.academic.period-types.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Créer un nouveau type">
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

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="10%">Code</th>
                                    <th>Intitulé</th>
                                    <th>Éléments inclus</th>
                                    <th>Description</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($periodTypes as $periodType)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $periodType->code }}</span></td>
                                        <td class="font-weight-bold">{{ $periodType->name }}</td>
                                        <td>
                                            <span class="badge badge-info px-2 py-1">
                                                <i class="fa fa-list-ol mr-1"></i>{{ $periodType->items_count ?? $periodType->items()->count() }} élément(s)
                                            </span>
                                            @if($periodType->items && $periodType->items->count() > 0)
                                                <small class="d-block text-muted mt-1">
                                                    ({{ $periodType->items->pluck('name')->implode(', ') }})
                                                </small>
                                            @endif
                                        </td>
                                        <td style="max-width: 100%; word-wrap: break-word; overflow-wrap: break-word; white-space: pre-line;">
                                            {{ $periodType->description ?: 'N/A' }}
                                        </td>
                                        <td>
                                            @if($periodType->is_active ?? true)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Activer / Désactiver -->
                                                @if(Route::has('settings.academic.period-types.toggle-status'))
                                                    <form action="{{ route('settings.academic.period-types.toggle-status', $periodType->id) }}" method="POST" class="d-inline mr-1" 
                                                        onsubmit="event.preventDefault(); Swal.fire({
                                                            title: 'Êtes-vous sûr ?',
                                                            text: 'Voulez-vous vraiment {{ ($periodType->is_active ?? true) ? 'désactiver' : 'activer' }} ce type ?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '{{ ($periodType->is_active ?? true) ? '#f8ac59' : '#1ab394' }}',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Oui, {{ ($periodType->is_active ?? true) ? 'désactiver' : 'activer' }} !',
                                                            cancelButtonText: 'Annuler'
                                                        }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                        
                                                        @csrf
                                                        @method('PATCH')
                                                        
                                                        @if($periodType->is_active ?? true)
                                                            <button type="submit" class="btn btn-warning btn-mini mr-1" title="Désactiver le type">
                                                                <i class="ti-close"></i> 
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-success btn-mini mr-1" title="Activer le type">
                                                                <i class="ti-check"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                @endif

                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('settings.academic.period-types.show', $periodType) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('settings.academic.period-types.edit', $periodType) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('settings.academic.period-types.destroy', $periodType) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement ce type de découpage !',
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun type de découpage académique enregistré pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($periodTypes, 'links'))
                        <div class="d-flex justify-content-end mt-3">
                            {{ $periodTypes->links() }}
                        </div>
                    @endif
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales Medium Ajax -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-cog"></i> GESTION DES TYPES DE DÉCOUPAGE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-cog"></i> GESTION DES TYPES DE DÉCOUPAGE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-cog"></i> GESTION DES TYPES DE DÉCOUPAGE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection