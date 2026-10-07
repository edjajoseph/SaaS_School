@extends('layouts.central.app_back', [
    'namePage' => 'Tenants',
    'activePage' => 'central',
    'activePageSb' => 'tenants',
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
                            <h5 class="m-b-10">Clients & Sous-domaines (Tenants)</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('founder') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Gestion Clients</a></li>
                            <li class="breadcrumb-item"><a href="#!">Tenants</a></li>
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
                        <h5>Liste des Clients Enregistrés</h5>
                    </div>
                    <div class="text-center">
                                <a href="#" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{ route('tenants.create') }}"><i class="fa fa-plus"></i>&nbsp;{{__(' Nouveau Client')}}</a>
                            </div>
                            <hr>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                        <table id="dom-table" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Client / Entreprise</th>
                                    <th>Domaines</th>
                                    <th>Solution</th>
                                    <th>Offre / Plan</th>
                                    <th>Date de création</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
    @if($tenants->isEmpty())
        <tr>
            <td colspan="7" class="text-center py-4">
                <div class="text-muted">
                    <i class="feather icon-inbox display-4 d-block mb-2"></i>
                    Aucun client enregistré pour le moment.
                </div>
            </td>
        </tr>
    @else
        @foreach($tenants as $tenant)
            <tr>
                <td>
                    <div class="d-inline-block align-middle">
                        <h6 class="mb-0">{{ $tenant->name ?? $tenant->company_name }}</h6>
                        <small class="text-muted"><i class="feather icon-mail mr-1"></i>{{ $tenant->email }}</small>
                    </div>
                </td>

                {{-- Liste des domaines du Tenant --}}
                <td>
                    @forelse($tenant->domains as $domain)
                        <a href="http://{{ $domain->domain }}" target="_blank" class="badge badge-light-primary">
                            {{ $domain->domain }} <i class="feather icon-external-link ml-1"></i>
                        </a>
                    @empty
                        <span class="badge badge-light-warning">Aucun domaine</span>
                    @endforelse
                </td>

                {{-- Affichage de la Solution --}}
                <td>
                    @if($tenant->solution)
                        <span class="badge badge-light-info">{{ $tenant->solution->nom ?? $tenant->solution->name }}</span>
                    @else
                        <span class="badge badge-light-secondary">Aucune solution</span>
                    @endif
                </td>

                {{-- Offre / Plan --}}
                <td>
                    @php
                        $activeSub = $tenant->subscriptions->firstWhere('status', 'active');
                    @endphp
                    @if($activeSub && $activeSub->plan)
                        <span class="badge badge-light-info">{{ $activeSub->plan->name }}</span>
                    @else
                        <span class="badge badge-light-secondary">Sans abonnement</span>
                    @endif
                </td>

                {{-- Date de création --}}
                <td>
                    {{ $tenant->created_at ? $tenant->created_at->format('d/m/Y') : 'N/A' }}
                </td>

                {{-- Statut --}}
                <td>
                    @if($tenant->status === 'active')
                        <span class="badge badge-light-success">Actif</span>
                    @elseif($tenant->status === 'suspended')
                        <span class="badge badge-light-warning">Suspendu</span>
                    @else
                        <span class="badge badge-light-danger">{{ ucfirst($tenant->status ?? 'Inactif') }}</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td class="text-center">
                    <form id="form-{{ $tenant->id }}" method="post" action="{{ route('tenants.destroy', $tenant) }}" style="display: inline-block;" 
                          onsubmit="event.preventDefault(); Swal.fire({
                              title: 'Êtes-vous sûr ?',
                              text: 'Cette action supprimera définitivement ce client !',
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#ed5565',
                              cancelButtonColor: '#1ab394',
                              confirmButtonText: 'Oui, supprimer !',
                              cancelButtonText: 'Annuler'
                          }).then((result) => { if (result.isConfirmed) this.submit(); });">
                        
                        @csrf
                        @method('DELETE')

                        <a href="#" class="btn btn-warning btn-rounded btn-sm" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2" data-attr="{{ route('tenants.show', $tenant) }}" title="Afficher"><i class="fa fa-eye"></i></a> | 
                        <a href="#" class="btn btn-success btn-rounded btn-sm" data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('tenants.edit', $tenant) }}" title="Modifier"><i class="fa fa-edit"></i></a> | 
                        <button type="submit" class="btn btn-danger btn-rounded btn-sm" title="Supprimer"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endif
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
