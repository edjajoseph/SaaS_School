<div class="page-body p-2">
    <div class="row">
        <!-- Informations générales de la Série École -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>DÉTAILS DE L'ASSOCIATION
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-bookmark fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">
                            {{ $school_series->serie->name ?? $school_series->serie->code ?? 'Série' }}
                        </h4>
                        @if(isset($school_series->serie->code))
                            <span class="badge badge-primary px-3 py-1">{{ $school_series->serie->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-university text-secondary mr-1"></i> Établissement :</strong> 
                            <span class="text-dark">{{ $school_series->school->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-tag text-secondary mr-1"></i> Série :</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $school_series->serie->name ?? $school_series->serie->code ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($school_series->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calendar-check-o text-secondary mr-1"></i> Date d'association :</strong> 
                            <span class="text-dark">{{ $school_series->created_at ? $school_series->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-history text-secondary mr-1"></i> Dernière mise à jour :</strong> 
                            <span class="text-dark">{{ $school_series->updated_at ? $school_series->updated_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <a href="{{ route('academic.school-series.index') }}" class="btn btn-secondary btn-round waves-effect" >
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </a>
</div>