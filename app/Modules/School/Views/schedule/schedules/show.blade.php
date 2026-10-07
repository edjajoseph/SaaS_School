<div class="modal-header bg-warning text-white">
    <h5 class="modal-title"><i class="fa fa-info-circle mr-2"></i>Détails du Crayon d'Emploi du Temps</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body p-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Matière</small>
            <strong class="h5">{{ $schedule->subject->custom_name ?? 'N/A' }}</strong>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Classe</small>
            <span class="badge bg-secondary">{{ $schedule->schoolClass->name ?? 'N/A' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Enseignant</small>
            <span>{{ $schedule->teacher ? $schedule->teacher->personne->nom . ' ' . $schedule->teacher->personne->prenoms : 'N/A' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Salle</small>
            <span>{{ $schedule->room->name ?? 'Non assignée' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Jour & Horaires</small>
            @php
                $days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
            @endphp
            <span><strong>{{ $schedule->day_of_week ?? '-' }}</strong> de {{ $schedule->start_time }} à {{ $schedule->end_time }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Type de session</small>
            <span class="badge bg-info">{{ $schedule->session_type }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Période académique</small>
            <span>{{ $schedule->academicPeriod->periodTypeItem->name ?? 'N/A' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Établissement</small>
            <span>{{ $schedule->school->name ?? 'N/A' }}</span>
        </div>
        <div class="col-md-6 mb-3">
            <small class="text-muted d-block">Moodle Event ID</small>
            <span>{{ $schedule->moodle_event_id ?? 'Non lié' }}</span>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary" onclick="$(this).closest('.modal').modal('hide');">
        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
    </button>
</div>