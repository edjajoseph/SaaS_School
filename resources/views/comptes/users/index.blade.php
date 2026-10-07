@extends('layouts.central.app_back', [
    'namePage' => 'user',
    'activePage' => 'acces',
    'activePageSb' => 'user',
    'activePageSb1' => '',
    
])

@section('content')

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Gestion des users</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Gestion des accès</a></li>
                            <li class="breadcrumb-item"><a href="#!">users</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
            <script class="alert alert-success" role="alert">
                @if($message = session('success'))
                    Swal.fire(
                    'Félicitation!',
                    '{{ $message }}',
                    'success'
                    )
                @elseif($message = session('warning'))
                    Swal.fire(
                    'Attention!',
                    '{{ $message }}',
                    'warning'
                    )
                @elseif($message = session('error'))
                    Swal.fire(
                    'Désolé!',
                    '{{ $message }}',
                    'error'
                    )
                @endif
            </script>

                {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Message d'erreur technique/exception --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation des champs (ex: doublon de nom) --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- HTML (DOM) Sourced Data table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Liste des rôles</h5>
                    </div>
                    <div class="text-center">
                                <a href="#" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{route('users.create')}}"><i class="fa fa-plus"></i>&nbsp;{{__(' Nouveau')}}</a>
                            </div>
                            <hr>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="dom-table" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ __("Nom & Prénoms") }}</th>
                                        <th>{{ __("Email de Connexion") }}</th>
                                        <th>{{ __("Rôles") }}</th>
                                        <th>{{ __("Statut") }}</th>
                                        <th>{{ __("Changement MDP") }}</th>
                                        <th class="text-center" style="width: 130px;">{{ __("Actions") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->personne)
                                                    <br><small class="text-muted"><i class="fa fa-phone"></i> {{ $user->personne->telephone ?? 'N/A' }}</small>
                                                @endif
                                            </td>
                                            <td class="align-middle"><code>{{ $user->email }}</code></td>
                                            <td class="align-middle">
                                                @forelse($user->roles as $role)
                                                    <span class="badge badge-info">{{ $role->display_name ?? $role->name }}</span>
                                                @empty
                                                    <span class="badge badge-secondary">{{ __("Aucun rôle") }}</span>
                                                @endforelse
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($user->isactive)
                                                    <span class="badge badge-primary">{{ __("Actif") }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __("Inactif") }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($user->pwd_change)
                                                    <span class="badge badge-success">{{ __("Oui") }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __("Requis") }}</span>
                                                @endif
                                            </td>
                                            <td>
                                            {{-- 1. Formulaire d'activation / désactivation --}}
                                                <a href="#" class="btn btn-warning btn-rounded btn-sm" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2"  data-attr="{{ route('users.show', [$user->id]) }}" title="Afficher"><i class="fa fa-eye"></i></a> | 
                                                <form method="POST" action="{{ route('users.toggle-status', $user->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($user->isactive)
                                                        <button type="button" 
                                                                class="btn btn-secondary btn-rounded btn-sm btn-toggle-status" 
                                                                title="Désactiver le compte"
                                                                data-title="Voulez-vous désactiver ce compte ?"
                                                                data-text="Cette action désactivera le compte !"
                                                                data-btn="Oui, désactiver !">
                                                            <i class="fa fa-power-off text-danger"></i>
                                                        </button> | 
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-primary btn-rounded btn-sm btn-toggle-status" 
                                                                title="Activer le compte"
                                                                data-title="Voulez-vous activer ce compte ?"
                                                                data-text="Cette action activera le compte !"
                                                                data-btn="Oui, activer !">
                                                            <i class="fa fa-power-off text-white"></i>
                                                        </button> | 
                                                    @endif
                                                </form>

                                                {{-- 2. Formulaire de réinitialisation MDP --}}
                                                <form method="POST" action="{{ route('users.reset-password', $user->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="button" 
                                                            class="btn btn-info btn-rounded btn-sm btn-reset-password" 
                                                            title="Réinitialiser MDP & Renvoyer Email">
                                                        <i class="fa fa-key"></i>
                                                    </button> | 
                                                </form>
                                                <form id="form-id" method="post" action="{{ route('users.destroy', [$user->id]) }}" style="display: inline-block;" 
                                                      onsubmit="event.preventDefault(); Swal.fire({
                                                          title: 'Êtes-vous sûr ?',
                                                          text: 'Cette action supprimera définitivement cette fiche de prospection !',
                                                          icon: 'warning',
                                                          showCancelButton: true,
                                                          confirmButtonColor: '#ed5565',
                                                          cancelButtonColor: '#1ab394',
                                                          confirmButtonText: 'Oui, supprimer !',
                                                          cancelButtonText: 'Annuler'
                                                      }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <a href="#" class="btn btn-success btn-rounded btn-sm" data-toggle="modal" id="mediumButton1" data-target="#mediumModal1"  data-attr="{{ route('users.edit', [$user->id]) }}" title="Afficher" title="Modifier"><i class="fa fa-edit"></i></a> | 
                                                    <button class="btn btn-danger btn-rounded btn-sm" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach                                    
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- HTML (DOM) Sourced Data table end -->
            
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>
        

        <div id="mediumModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-primary">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion des accès</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div id="mediumModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion des accès</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody1">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="mediumModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-warning">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion des accès</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody2">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="mediumModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" data-background="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-user"></i> <b>Gestion des accès</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="mediumBody3">
                        
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

    // Gestion de l'Activation / Désactivation
    $(document).on('click', '.btn-toggle-status', function (e) {
        e.preventDefault();
        const button = $(this);
        const form = button.closest('form'); // Récupère le formulaire exact

        Swal.fire({
            title: button.data('title'),
            text: button.data('text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ed5565',
            cancelButtonColor: '#1ab394',
            confirmButtonText: button.data('btn'),
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Soumet le bon formulaire
            }
        });
    });

    // Gestion de la Réinitialisation de Mot de Passe
    $(document).on('click', '.btn-reset-password', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Réinitialiser le mot de passe ?',
            text: 'Un nouveau lien d\'activation sera envoyé à l\'utilisateur.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f8ac59',
            cancelButtonColor: '#1ab394',
            confirmButtonText: 'Oui, réinitialiser !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    });
    </script>
@endsection
