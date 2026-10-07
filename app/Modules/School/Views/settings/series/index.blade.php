@extends('School::layouts.app3', [
    'namePage' => 'Gestion des séries',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'settings.academic.series',
    'activeModule' => 'enseignement',
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
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-bookmark mr-2"></i>LISTE DES SÉRIES / FILIERES
                    </h4>
                    
                    <a data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('settings.academic.series.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Créer une nouvelle série">
                        <i class="fa fa-plus mr-1"></i> Nouvelle Série
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
                    <!-- Barres de Recherche et Filtres -->
                    <form method="GET" action="{{ route('settings.academic.series.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher par code ou nom..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="is_active" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Tous les statuts --</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            @if(request('search') || request()->has('is_active'))
                                <div class="col-md-2">
                                    <a href="{{ route('settings.academic.series.index') }}" class="btn btn-secondary btn-block">
                                        <i class="fa fa-undo mr-1"></i> Réinitialiser
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>

                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th width="12%">Code</th>
                                    <th>Nom de la série</th>
                                    <th>Description</th>
                                    <th width="15%" class="text-center">Nombre de classes</th>
                                    <th width="10%" class="text-center">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($series as $serie)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $serie->code }}</span></td>
                                        <td class="font-weight-bold">{{ $serie->name }}</td>
                                        <td>{{ Str::limit($serie->description, 50, '...') ?? '-' }}</td>
                                        <td class="text-center font-weight-bold">
                                            <span class="badge badge-info px-2 py-1">
                                                <i class="fa fa-building mr-1"></i>{{ $serie->classes_count ?? 0 }} classe(s)
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($serie->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Activer / Désactiver -->
                                                <form action="{{ route('settings.academic.series.toggle-active', $serie) }}" method="POST" class="d-inline mr-1"
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous modifier le statut de cette série ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '{{ $serie->is_active ? '#f8ac59' : '#1ab394' }}',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, continuer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($serie->is_active)
                                                        <button type="submit" class="btn btn-warning btn-mini" title="Désactiver">
                                                            <i class="ti-close"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-success btn-mini" title="Activer">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Action Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('settings.academic.series.show', $serie) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Modifier -->
                                                <a data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('settings.academic.series.edit', $serie) }}" class="btn btn-success btn-mini mr-1 text-white" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('settings.academic.series.destroy', $serie) }}" method="POST" class="d-inline"
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette série !',
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
                                            Aucune série enregistrée pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $series->links() }}
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modales d'injection AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-primary"><i class="fa fa-bookmark"></i> GESTION DES SÉRIES</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
                <h5 class="modal-title text-info"><i class="fa fa-bookmark"></i> DÉTAILS DE LA SÉRIE</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFIER LA SÉRIE</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="mediumBody2">
                <div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div>
            </div>
        </div>
    </div>
</div>
@endsection