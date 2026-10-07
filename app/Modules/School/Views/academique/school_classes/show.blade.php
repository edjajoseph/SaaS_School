<div class="page-body p-2">
    <div class="row">
        <!-- Informations générales de la Classe -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>INFORMATIONS DE LA CLASSE
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">{{ $class->name }}</h4>
                        @if($class->code)
                            <span class="badge badge-primary px-3 py-1">{{ $class->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-building text-secondary mr-1"></i> Établissement :</strong> 
                            <span class="text-dark">{{ $class->school->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-layer-group text-secondary mr-1"></i> Niveau d'étude :</strong> 
                            <span class="badge badge-secondary px-2 py-1">{{ $class->level->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-layer-group text-secondary mr-1"></i> Filière / Série :</strong> 
                            <span class="badge badge-secondary px-2 py-1">{{ $class->serie->name ?? 'Non renseignée' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-money text-secondary mr-1"></i> Frais de scolarité :</strong> 
                            <span class="font-weight-bold text-success">{{ number_format($class->tuition_fee, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-users text-secondary mr-1"></i> Capacité d'accueil :</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $class->capacity ?: 'Non spécifiée' }} places</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($class->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calendar-check-o text-secondary mr-1"></i> Date création :</strong> 
                            <span class="text-dark">{{ $class->created_at ? $class->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
<a href="{{ route('organisation.classes.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
</div>