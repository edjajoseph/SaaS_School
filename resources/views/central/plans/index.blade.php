@extends('layouts.central.app_back', [
    'namePage' => 'Rôle',
    'activePage' => 'acces',
    'activePageSb' => 'role',
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
                            <h5 class="m-b-10">Catalogue des Plans & Tarifs</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Offres & Catalogue</a></li>
                            <li class="breadcrumb-item"><a href="#!">Plans & Tarifs</a></li>
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
                                <a href="#" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{route('plans.create')}}"><i class="fa fa-plus"></i>&nbsp;{{__(' Nouveau')}}</a>
                            </div>
                            <hr>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="dom-table" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Solution</th>
                                        <th>Prix</th>
                                        <th>Période</th>
                                        <th>Abonnés</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plans as $plan)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0">{{ $plan->name }}</h6>
                                                <small class="text-muted">Slug: <code>{{ $plan->slug }}</code></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-primary">{{ $plan->solution->nom ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency ?? 'XOF' }}</strong>
                                            </td>
                                            <td>
                                                @if($plan->invoice_period === 'monthly')
                                                    <span class="badge badge-light-info">Mensuel</span>
                                                @else
                                                    <span class="badge badge-light-warning">Annuel</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-secondary">{{ $plan->subscriptions_count }} abonné(s)</span>
                                            </td>
                                            <td>
                                                @if($plan->is_active)
                                                    <span class="badge badge-light-success">Actif</span>
                                                @else
                                                    <span class="badge badge-light-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form id="form-id" method="post" action="{{ route('plans.destroy', $plan) }}" style="display: inline-block;" 
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

                                                    <a href="#" class="btn btn-warning btn-rounded btn-sm" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2"  data-attr="{{ route('plans.show', $plan) }}" title="Afficher"><i class="fa fa-eye"></i></a> | 
                                                    <a href="#" class="btn btn-success btn-rounded btn-sm" data-toggle="modal" id="mediumButton1" data-target="#mediumModal1"  data-attr="{{ route('plans.edit', $plan) }}" title="Afficher" title="Modifier"><i class="fa fa-edit"></i></a> | 
                                                    <button class="btn btn-danger btn-rounded btn-sm" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Aucun plan enregistré.</td>
                                            </tr>
                                        @endforelse                                    
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
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
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
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
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
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
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
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white bg-success">
                        <h5 class="modal-title" id="exampleModalLiveLabel"><i class="fa fa-users"></i> <b>Gestion SaaS & Clients</b></h5>
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
