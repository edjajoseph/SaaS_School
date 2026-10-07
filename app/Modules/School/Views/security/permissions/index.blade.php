@extends('School::layouts.app3', [
    'namePage' => 'Gestion des permissions',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'acces',
    'activeModule' => 'permission',
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
                        <i class="feather icon-key mr-2"></i>LISTE DES PERMISSIONS
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('access.permissions.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouvelle permission">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouvelle Permission
                    </a>
                </div>
                <hr class="hr-gradient">                   
                <div class="card-block">
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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Identifiant (Nom)</th>
                                    <th>Module</th>
                                    <th>Fonctionnalité</th>                                        
                                    <th>Libellé d'affichage</th>
                                    <th>Description</th>
                                    <th width="10%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr>
                                        <td class="align-middle">
                                            <code class="text-dark font-weight-bold">{{ $permission->name }}</code>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-primary px-2 py-1">{{ $permission->module ?: 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-info px-2 py-1">{{ $permission->feature ?: 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle text-dark font-weight-bold">{{ $permission->display_name }}</td>
                                        <td class="align-middle text-muted text-wrap" style="max-width: 250px;">{{ Str::limit($permission->description, 40) }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('access.permissions.show', $permission->id) }}" class="btn btn-info btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a class="btn btn-success btn-mini mr-1 text-white" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('access.permissions.edit', $permission->id) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('access.permissions.destroy', $permission->id) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette permission !',
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
                                            Aucune permission enregistrée pour le moment.
                                        </td>
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
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title"><i class="fa fa-key"></i> CRÉATION D'UNE PERMISSION</h5>
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

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-info">
                <h5 class="modal-title"><i class="fa fa-key"></i> DÉTAILS DE LA PERMISSION</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-success">
                <h5 class="modal-title"><i class="fa fa-edit"></i> MODIFICATION DE LA PERMISSION</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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