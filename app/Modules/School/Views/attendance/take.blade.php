@extends('School::layouts.app3', [
    'namePage' => 'Feuille de Présence',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.take',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
    .btn-status-group .btn-check { display: none; }
    .btn-status-group label { cursor: pointer; opacity: 0.6; font-weight: 600; font-size: 12px; }
    .btn-status-group input:checked + label { opacity: 1; transform: scale(1.03); box-shadow: 0 2px 5px rgba(0,0,0,0.15); }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- En-tête du créneau -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i class="fa fa-calendar-check-o mr-2"></i>POINTAGE : {{ $schedule->subject->name ?? 'Matière' }}
                    </h5>
                    <span class="badge badge-light text-primary font-weight-bold px-3 py-2">
                        {{ $schedule->schoolClass->name ?? 'Classe' }}
                    </span>
                </div>
                <div class="card-body bg-light">
                    <div class="row text-center text-md-left">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Enseignant</small>
                            <strong>{{ $schedule->teacher->personne->nom ?? '' }} {{ $schedule->teacher->personne->prenoms ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Horaire</small>
                            <strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Date de Séance</small>
                            <span class="badge badge-info fs-14">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-3 text-md-right mt-2 mt-md-0">
                            <!-- Actions rapides d'ensemble -->
                            <button type="button" class="btn btn-outline-success btn-sm mr-1" id="mark-all-present">
                                <i class="fa fa-check-circle mr-1"></i>Tous Présents
                            </button>

                            <!-- Bouton Impression PDF -->
                            <a href="{{ route('attendance.print', ['schedule' => $schedule->id, 'date' => $date]) }}" 
                            target="_blank" 
                            class="btn btn-inverse btn-sm waves-effect shadow-sm">
                                <i class="fa fa-print mr-1"></i> Imprimer la fiche
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('School::alerts.success')
            @include('School::alerts.errors')

            <!-- Formulaire de Pointage -->
            <form action="{{ route('attendance.store', $schedule->id) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="card shadow-sm border-0">
                    <div class="card-block p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="15%">Matricule</th>
                                        <th width="30%">Nom & Prénoms</th>
                                        <th width="35%" class="text-center">Statut du Pointage</th>
                                        <th width="15%">Précision / Motif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrations as $index => $registration)
                                        @php
                                            $student = $registration->student;
                                            $personne = $student->personne ?? null;
                                            $existing = $existingAttendances->get($registration->id);
                                            $currentStatus = $existing->status ?? 'present';
                                        @endphp
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $student->matricule ?? 'N/A' }}</span>
                                                <input type="hidden" name="attendances[{{ $index }}][registration_id]" value="{{ $registration->id }}">
                                            </td>
                                            <td class="font-weight-bold text-uppercase">
                                                {{ $personne ? $personne->nom . ' ' . $personne->prenoms : 'N/A' }}
                                            </td>
                                            
                                            <!-- Boutons Radio de statut -->
                                            <td class="text-center">
                                                <div class="btn-group btn-group-toggle btn-status-group" data-toggle="buttons">
                                                    <!-- Présent -->
                                                    <label class="btn btn-sm btn-outline-success {{ $currentStatus === 'present' ? 'active' : '' }}">
                                                        <input type="radio" name="attendances[{{ $index }}][status]" value="present" 
                                                               class="radio-status" data-target="details-{{ $index }}" 
                                                               {{ $currentStatus === 'present' ? 'checked' : '' }}> 
                                                        <i class="fa fa-check mr-1"></i>Présent
                                                    </label>

                                                    <!-- Retard -->
                                                    <label class="btn btn-sm btn-outline-warning {{ $currentStatus === 'late' ? 'active' : '' }}">
                                                        <input type="radio" name="attendances[{{ $index }}][status]" value="late" 
                                                               class="radio-status" data-target="details-{{ $index }}"
                                                               {{ $currentStatus === 'late' ? 'checked' : '' }}> 
                                                        <i class="fa fa-clock-o mr-1"></i>Retard
                                                    </label>

                                                    <!-- Absent -->
                                                    <label class="btn btn-sm btn-outline-danger {{ $currentStatus === 'absent' ? 'active' : '' }}">
                                                        <input type="radio" name="attendances[{{ $index }}][status]" value="absent" 
                                                               class="radio-status" data-target="details-{{ $index }}"
                                                               {{ $currentStatus === 'absent' ? 'checked' : '' }}> 
                                                        <i class="fa fa-times mr-1"></i>Absent
                                                    </label>

                                                    <!-- Excused -->
                                                    <label class="btn btn-sm btn-outline-info {{ $currentStatus === 'excused' ? 'active' : '' }}">
                                                        <input type="radio" name="attendances[{{ $index }}][status]" value="excused" 
                                                               class="radio-status" data-target="details-{{ $index }}"
                                                               {{ $currentStatus === 'excused' ? 'checked' : '' }}> 
                                                        <i class="fa fa-info-circle mr-1"></i>Excusé
                                                    </label>
                                                </div>
                                            </td>

                                            <!-- Champ complémentaire (Minutes de retard ou motif) -->
                                            <td>
                                                <div id="late-input-{{ $index }}" class="extra-field {{ $currentStatus === 'late' ? '' : 'd-none' }}">
                                                    <input type="number" name="attendances[{{ $index }}][late_minutes]" 
                                                           class="form-control form-control-sm" placeholder="Minutes retard" 
                                                           value="{{ $existing->late_minutes ?? 15 }}" min="1">
                                                </div>
                                                <div id="reason-input-{{ $index }}" class="extra-field {{ in_array($currentStatus, ['absent', 'excused']) ? '' : 'd-none' }}">
                                                    <input type="text" name="attendances[{{ $index }}][reason]" 
                                                           class="form-control form-control-sm" placeholder="Motif (optionnel)" 
                                                           value="{{ $existing->reason ?? '' }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun étudiant inscrit dans cette classe.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    @if($registrations->isNotEmpty())
                        <div class="card-footer bg-white text-right py-3">
                            <button type="submit" class="btn btn-primary waves-effect shadow-sm px-4">
                                <i class="fa fa-save mr-1"></i> Enregistrer le Pointage
                            </button>
                        </div>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // 1. Bouton "Tous Présents"
    $('#mark-all-present').on('click', function () {
        $('.radio-status[value="present"]').prop('checked', true).trigger('change');
        $('.btn-status-group label').removeClass('active');
        $('.radio-status[value="present"]').closest('label').addClass('active');
        $('.extra-field').addClass('d-none');
    });

    // 2. Gestion dynamique de l'affichage du champ retard / motif
    $(document).on('change', '.radio-status', function () {
        let $row = $(this).closest('tr');
        let status = $(this).val();

        // Réinitialisation des classes d'activation visuelle du groupe de boutons
        $row.find('.btn-status-group label').removeClass('active');
        $(this).closest('label').addClass('active');

        // Masquer tous les champs supplémentaires de la ligne
        $row.find('.extra-field').addClass('d-none');

        // Afficher le champ adéquat selon le statut sélectionné
        if (status === 'late') {
            $row.find('[id^="late-input-"]').removeClass('d-none');
        } else if (status === 'absent' || status === 'excused') {
            $row.find('[id^="reason-input-"]').removeClass('d-none');
        }
    });
});
</script>
@endpush