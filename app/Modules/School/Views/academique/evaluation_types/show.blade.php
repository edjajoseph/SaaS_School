<div class="page-body p-2">
    <div class="row">
        <!-- Informations Salle -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white pb-0">
                    <h5 class="text-primary font-weight-bold mb-0">
                        <i class="fa fa-info-circle mr-2"></i>FICHE DÉTAILLÉE DE LA SALLE
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3 py-2 bg-light rounded">
                        <i class="fa fa-university fa-3x text-primary"></i>
                        <h4 class="mt-2 font-weight-bold text-dark mb-1">{{ $room->name }}</h4>
                        @if($room->code)
                            <span class="badge badge-primary px-3 py-1">{{ $room->code }}</span>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-building text-secondary mr-1"></i> Établissement :</strong> 
                            <span class="text-dark">{{ $room->school->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-check-circle text-secondary mr-1"></i> Statut :</strong> 
                            @if($room->is_active)
                                <span class="badge badge-success px-2 py-1">Actif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Inactif</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-building-o text-secondary mr-1"></i> Bâtiment :</strong> 
                            <span class="text-dark">{{ $room->building ?: 'Non défini' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-level-up text-secondary mr-1"></i> Étage :</strong> 
                            <span class="text-dark">{{ $room->floor !== null ? $room->floor.'e étage' : 'Non défini' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-users text-secondary mr-1"></i> Capacité d'accueil :</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $room->capacity ?: 'Non spécifiée' }} places</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fa fa-calendar-check-o text-secondary mr-1"></i> Créé le :</strong> 
                            <span class="text-dark">{{ $room->created_at ? $room->created_at->format('d/m/Y à H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary btn-round waves-effect" data-dismiss="modal">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>