<div class="page-body p-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-none mb-0">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <strong><i class="fa fa-calendar text-primary mr-1"></i> Libellé :</strong> 
                            <span class="badge badge-primary ml-1">{{ $academicYear->name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-calendar-plus-o text-primary mr-1"></i> Date de début :</strong> 
                            {{ \Carbon\Carbon::parse($academicYear->start_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong><i class="fa fa-calendar-minus-o text-primary mr-1"></i> Date de fin :</strong> 
                            {{ \Carbon\Carbon::parse($academicYear->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-12 mb-2">
                            <strong><i class="fa fa-star text-primary mr-1"></i> Statut courant :</strong> 
                            @if($academicYear->is_current)
                                <span class="badge badge-success"><i class="fa fa-check mr-1"></i> Année Académique Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('academic.academic-years.index') }}" class="btn btn-warning btn-sm btn-round waves-effect">
                            <i class="fa fa-undo mr-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>