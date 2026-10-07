<div class="page-body p-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-none mb-0">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-bookmark text-primary mr-1"></i> Libellé :</strong> 
                            <span class="badge badge-primary ml-1">{{ $term->name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-barcode text-primary mr-1"></i> Code :</strong> 
                            <span class="badge badge-info ml-1">{{ $term->code ?: 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-calendar-o text-primary mr-1"></i> Année académique :</strong> 
                            {{ $term->academicYear->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-sort-numeric-asc text-primary mr-1"></i> Ordre de séquence :</strong> 
                            {{ $term->sequence_order }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-calendar-plus-o text-primary mr-1"></i> Date de début :</strong> 
                            {{ \Carbon\Carbon::parse($term->start_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-calendar-minus-o text-primary mr-1"></i> Date de fin :</strong> 
                            {{ \Carbon\Carbon::parse($term->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-star text-primary mr-1"></i> Période courante :</strong> 
                            @if($term->is_current)
                                <span class="badge badge-success"><i class="fa fa-check mr-1"></i> Active</span>
                            @else
                                <span class="badge badge-secondary">Non</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-lock text-primary mr-1"></i> État de la période :</strong> 
                            @if($term->is_closed)
                                <span class="badge badge-danger"><i class="fa fa-lock mr-1"></i> Clôturée</span>
                            @else
                                <span class="badge badge-success"><i class="fa fa-unlock mr-1"></i> Ouverte</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('academic.terms.index') }}" class="btn btn-warning btn-sm btn-round waves-effect">
                            <i class="fa fa-undo mr-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>