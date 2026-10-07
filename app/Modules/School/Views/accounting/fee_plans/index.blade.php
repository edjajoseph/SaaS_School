@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Plans Tarifaires',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'fee_plans',
    'activeModule' => 'comptabilite',
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
                        <i class="feather icon-credit-card mr-2"></i>LISTE DES PLANS TARIFAIRES
                    </h4>
                    
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('school.fee-plans.create') }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Nouveau Plan Tarifaire">
                        <i class="ace-icon fa fa-plus mr-1"></i> Nouveau Plan Tarifaire
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
                    <form method="GET" action="{{ route('school.fee-plans.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom de plan..." value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3">
                            <select name="academic_year_id" class="form-control">
                                <option value="">-- Toutes les années --</option>
                                @foreach($academicYears ?? [] as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
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
                                    <th>Intitulé du Plan</th>
                                    <th>Année Académique</th>
                                    <th>Cible (Niveau / Classe)</th>
                                    <th>Montant Total</th>
                                    <th>Tranches</th>
                                    <th width="6%">Statut</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feePlans as $plan)
                                    <tr>
                                        <td class="font-weight-bold text-primary">{{ $plan->name }}</td>
                                        <td>
                                            <span class="badge badge-inverse px-2 py-1">
                                                <i class="fa fa-calendar mr-1"></i>{{ $plan->academicYear->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($plan->schoolClass)
                                                <span class="badge badge-info px-2 py-1"><i class="fa fa-graduation-cap mr-1"></i>{{ $plan->schoolClass->name }}</span>
                                            @elseif($plan->level)
                                                <span class="badge badge-secondary px-2 py-1"><i class="fa fa-layer-group mr-1"></i>{{ $plan->level->name }}</span>
                                            @else
                                                <span class="text-muted"><em>Global (Toutes classes)</em></span>
                                            @endif
                                        </td>
                                        <td class="font-weight-bold text-success">
                                            {{ number_format($plan->total_amount, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td>
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="fa fa-list-ol mr-1"></i>{{ $plan->items->count() }} tranche(s)
                                            </span>
                                        </td>
                                        <td>
                                            @if($plan->is_active)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Actif</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fa fa-times-circle mr-1"></i>Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Activer / Désactiver -->
                                                <form action="{{ route('school.fee-plans.toggle-active', $plan) }}" method="POST" class="d-inline mr-1" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Voulez-vous vraiment {{ $plan->is_active ? 'désactiver' : 'activer' }} ce plan tarifaire ?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '{{ $plan->is_active ? '#f8ac59' : '#1ab394' }}',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Oui, {{ $plan->is_active ? 'désactiver' : 'activer' }} !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });"> 
                                                    
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    @if($plan->is_active)
                                                        <button type="submit" class="btn btn-warning btn-mini mr-1" title="Désactiver">
                                                            <i class="ti-close"></i> 
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-success btn-mini mr-1" title="Activer">
                                                            <i class="ti-check"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <form action="{{ route('school.fee-plans.sync', $plan->id) }}" method="POST" onsubmit="return confirm('Appliquer ce plan tarifaire à tous les étudiants inscrits dans cette classe ?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-mini mr-1 btn-outline-primary">
                                                        <i class="fas fa-sync"></i> Synchroniser les inscrits
                                                    </button>
                                                </form>

                                                <!-- Détails -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('school.fee-plans.show', $plan) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Détails du plan">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Modifier -->
                                                <a class="btn btn-success btn-mini mr-1" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('school.fee-plans.edit', $plan) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <!-- Supprimer -->
                                                <form action="{{ route('school.fee-plans.destroy', $plan) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera ce plan tarifaire et son échéancier !',
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
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun plan tarifaire configuré pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $feePlans->links() }}
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
                <h5 class="modal-title text-primary"><i class="fa fa-credit-card"></i> CRÉATION D'UN PLAN TARIFAIRE</h5>
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
                <h5 class="modal-title text-info"><i class="fa fa-eye"></i> DÉTAILS DU PLAN TARIFAIRE</h5>
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
                <h5 class="modal-title text-info"><i class="fa fa-edit"></i> MODIFICATION DU PLAN TARIFAIRE</h5>
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
{{-- Dans index.blade.php (à la fin du fichier dans la section scripts) --}}
@section('scripts')
<script>
    $(document).ready(function() {
        // 1. Écouteur global ultra-robuste pour tous les boutons de fermeture (croix ET boutons "Fermer")
        $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .btn-close, .close', function (e) {
            e.preventDefault();
            
            // Retrouve la modale parente active et force la fermeture Bootstrap
            var $parentModal = $(this).closest('.modal');
            if ($parentModal.length) {
                $parentModal.modal('hide');
            }
        });

        // 2. Nettoyage garanti du fond sombre (backdrop) et du scroll lors de la fermeture
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
        });
    });

    // Fonction d'ouverture AJAX universelle
    function loadAjaxModal(buttonSelector, modalId, bodyId) {
        $(document).on('click', buttonSelector, function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr') || $(this).attr('href');
            $.ajax({
                url: href,
                type: 'GET',
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {
                    $(modalId).modal("show");
                    $(bodyId).html(result).show();
                },
                error: function(jqXHR, textStatus, error) {
                    console.error("Erreur AJAX : ", error);
                    alert("Impossible d'ouvrir la page. Erreur : " + error);
                },
                complete: function() {
                    $('#loader').hide();
                }
            });
        });
    }

    // Bindings de vos modales
    loadAjaxModal('#mediumButton', '#mediumModal', '#mediumBody');
    loadAjaxModal('#mediumButton1', '#mediumModal1', '#mediumBody1');
    loadAjaxModal('#mediumButton2', '#mediumModal2', '#mediumBody2');
    loadAjaxModal('#largeButton', '#largeModal', '#largeBody');
    loadAjaxModal('#largeButton1', '#largeModal1', '#largeBody1');
</script>
@endsection