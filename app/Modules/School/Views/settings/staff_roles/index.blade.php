@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Rôles du Personnel',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'settings.rh.staff_roles',
    'activeModule' => 'parametrage',
])

@section('content')
<style>
hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-briefcase mr-2"></i>LISTE DES RÔLES ET FONCTIONS
                    </h4>
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('settings.rh.staff-roles.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Rôle
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
                                    <th width="10%">Code</th>
                                    <th>Rôle / Fonction</th>
                                    <th>Établissement</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $role->code ?? 'N/A' }}</span></td>
                                        <td class="font-weight-bold">{{ $role->name }}</td>
                                        <td>{{ $role->school->name ?? 'Global' }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('settings.rh.staff-roles.show', $role) }}" class="btn btn-warning btn-mini mr-1 text-white"><i class="fa fa-eye"></i></a>
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('settings.rh.staff-roles.edit', $role) }}"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('settings.rh.staff-roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); Swal.fire({ title: 'Êtes-vous sûr ?', text: 'Supprimer ce rôle ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ed5565', cancelButtonColor: '#1ab394', confirmButtonText: 'Oui', cancelButtonText: 'Non' }).then((r) => { if (r.isConfirmed) this.submit(); });">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-mini"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun rôle enregistré.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header bg-gradient-success"><h5 class="modal-title text-primary"><i class="fa fa-briefcase"></i> CRÉATION RÔLE</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-briefcase"></i> DÉTAILS RÔLE</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody1"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-briefcase"></i> MODIFICATION RÔLE</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body" id="mediumBody2"><i class="fa fa-spinner fa-spin"></i></div></div></div></div>
@endsection