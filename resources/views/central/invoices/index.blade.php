@extends('layouts.central.app_back', [
    'namePage' => 'Facturation',
    'activePage' => 'central',
    'activePageSb' => 'facturation',
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
                            <h5 class="m-b-10">Finance & Facturations</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Facturation Clients</a></li>
                            <li class="breadcrumb-item"><a href="#!">Facture</a></li>
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
                        <h5>Liste des factures émises</h5>
                    </div>
                    <div class="text-center">
                                <a href="#" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" id="mediumButton" data-target="#mediumModal"  data-attr="{{ route('invoices.create') }}"><i class="fa fa-plus"></i>&nbsp;{{__(' Créer une Facture')}}</a>
                            </div>
                            <hr>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="dom-table" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Client</th>
                                        <th>Montant HT</th>
                                        <th>TVA</th>
                                        <th>Montant TVA</th>
                                        <th>Montant TTC</th>
                                        <th>Échéance</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td><code>{{ $invoice->number ?? '#INV-'.$invoice->id }}</code></td>
                                            <td><strong>{{ $invoice->tenant->name ?? $invoice->tenant_id }}</strong></td>
                                            <td>{{ number_format($invoice->amount_ht, 0, ',', ' ') }} {{ $invoice->currency }}</td>
                                            <td>{{ number_format($invoice->tax_rate, 0, ',', ' ') }} %</td>
                                            <td>{{ $invoice->tax_amount == floor($invoice->tax_amount) 
                                                ? number_format($invoice->tax_amount, 0, ',', ' ') 
                                                : number_format($invoice->tax_amount, 2, ',', ' ') }} {{ $invoice->currency }}
                                            </td>
                                            <td><strong>{{ number_format($invoice->amount_ttc, 0, ',', ' ') }} {{ $invoice->currency }}</strong></td>
                                            <td>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($invoice->status === 'paid')
                                                    <span class="badge badge-light-success">Payée</span>
                                                @elseif($invoice->status === 'pending')
                                                    <span class="badge badge-light-warning">En attente</span>
                                                @else
                                                    <span class="badge badge-light-danger">{{ ucfirst($invoice->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-rounded btn-sm" data-toggle="modal" id="mediumButton2" data-target="#mediumModal2"  data-attr="{{ route('invoices.show', $invoice) }}" title="Afficher"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Aucune facture enregistrée.</td>
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
