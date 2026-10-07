@extends('School::layouts.app3', [
    'namePage' => 'Gestion des types d\'évaluations',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'academic.evaluation-types',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-tags mr-2"></i>TYPES D'ÉVALUATIONS (CC, TP, EXAMEN)
                    </h4>
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('academic.evaluation-types.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau Type">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Type
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
                                    <th>Libellé</th>
                                    <th width="15%">Poids par défaut</th>
                                    <th width="15%">Rattrapage</th>
                                    <th width="10%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($types as $type)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $type->code }}</span></td>
                                        <td class="font-weight-bold">{{ $type->name }}</td>
                                        <td>{{ number_format($type->default_weight, 2) }}</td>
                                        <td>
                                            @if($type->is_catch_up)
                                                <span class="badge badge-warning px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i>Oui</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($type->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <form action="{{ route('academic.evaluation-types.toggle-status', $type->id) }}" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Changer le statut ?',
                                                        text: 'Voulez-vous modifier le statut de ce type ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#1ab394',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, modifier !'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-mini mr-1"><i class="ti-reload"></i></button>
                                                </form>

                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('academic.evaluation-types.edit', $type) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="{{ route('academic.evaluation-types.destroy', $type) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action est irréversible !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i> Aucun type d'évaluation trouvé.
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

<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success"><h5 class="modal-title text-primary"><i class="fa fa-cog"></i> AJOUTER UN TYPE D'ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary"><h5 class="modal-title text-info"><i class="fa fa-cog"></i> MODIFIER LE TYPE D'ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody2"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
@endsection