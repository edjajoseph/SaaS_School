<div class="modal-header bg-info text-white">
    <h5 class="modal-title"><i class="fa fa-warning-circle mr-2"></i>Détails de la Salle</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body p-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Nom de la salle</small>
            <strong class="h5">{{ $room->name }}</strong>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Code</small>
            <span class="badge bg-secondary">{{ $room->code ?? 'Non renseigné' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Établissement</small>
            <span>{{ $room->school->name ?? 'N/A' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Capacité d'accueil</small>
            <span>{{ $room->capacity ? $room->capacity . ' places' : 'Non précisée' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Localisation</small>
            <span>{{ $room->building ?? 'Bâtiment N/A' }} {{ $room->floor !== null ? '- Étage '.$room->floor : '' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Statut</small>
            @if($room->is_active)
                <span class="badge bg-success">Actif</span>
            @else
                <span class="badge bg-danger">Inactif</span>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary" onclick="$(this).closest('.modal').modal('hide');">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>