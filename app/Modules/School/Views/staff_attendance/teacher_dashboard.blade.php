@extends('School::layouts.app3', [
    'namePage' => 'Gestion & Pointage des Cours',
    'class' => 'sidebar-mini',
    'activePage' => 'teacher.attendance',
    'activeModule' => 'enseignement',
])

@section('content')
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- En-tête -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i class="fa fa-clock-o mr-2"></i>
                        {{ $isAdmin ? 'SUPERVISION ET RATTRAPAGE DES POINTAGES' : 'MES POINTAGES DU JOUR' }}
                    </h5>
                    <span class="badge badge-light text-primary font-weight-bold">
                        {{ \Carbon\Carbon::parse($selectedDate)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                </div>

                <!-- ZONE DE FILTRES (RÉSERVÉE AUX ADMINISTRATEURS) -->
                @if($isAdmin)
                <div class="card-body bg-light border-bottom py-3">
                    <form method="GET" action="{{ route('attendance.teacher.attendance.dashboard') }}" class="row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label class="font-weight-bold text-dark mb-1">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                        </div>
                        <div class="col-md-5 mb-2">
                            <label class="font-weight-bold text-dark mb-1">Enseignant</label>
                            <select name="staff_id" class="form-control">
                                <option value="">-- Tous les enseignants --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $selectedStaffId == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->personne->nom ?? '' }} {{ $teacher->personne->prenoms ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-filter mr-1"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            @include('School::alerts.success')
            @include('School::alerts.errors')

            <!-- Tableau des Cours & Pointages -->
            <div class="card shadow-sm border-0">
                <div class="card-block p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    @if($isAdmin) <th>Enseignant</th> @endif
                                    <th>Horaire / Fenêtre</th>
                                    <th>Matière & Classe</th>
                                    <th class="text-center">Heure Arrivée</th>
                                    <th class="text-center">Heure Départ</th>
                                    <th class="text-center">Méthode</th>
                                    <th class="text-center" width="22%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    @php
                                        $att = $schedule->attendance;
                                    @endphp
                                    <tr>
                                        @if($isAdmin)
                                            <td class="font-weight-bold text-dark">
                                                {{ $schedule->staff->personne->nom ?? '' }} {{ $schedule->staff->personne->prenoms ?? '' }}
                                            </td>
                                        @endif

                                        <td>
                                            <span class="badge badge-info fs-13">
                                                <i class="fa fa-clock-o mr-1"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                            </span>
                                            <small class="d-block text-muted mt-1">Fenêtre : {{ $schedule->check_in_start }} à {{ $schedule->check_out_end }}</small>
                                        </td>

                                        <td>
                                            <div class="font-weight-bold text-primary">{{ $schedule->subject->name ?? 'Matière' }}</div>
                                            <small class="text-muted">{{ $schedule->schoolClass->name ?? 'Classe' }}</small>
                                        </td>

                                        <!-- Arrivée -->
                                        <td class="text-center">
                                            @if($att && $att->check_in)
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fa fa-check mr-1"></i>{{ \Carbon\Carbon::parse($att->check_in)->format('H:i:s') }}
                                                </span>
                                            @else
                                                <span class="badge badge-light text-muted">Non pointé</span>
                                            @endif
                                        </td>

                                        <!-- Départ -->
                                        <td class="text-center">
                                            @if($att && $att->check_out)
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fa fa-check mr-1"></i>{{ \Carbon\Carbon::parse($att->check_out)->format('H:i:s') }}
                                                </span>
                                            @else
                                                <span class="badge badge-light text-muted">Non pointé</span>
                                            @endif
                                        </td>

                                        <!-- Méthode -->
                                        <td class="text-center">
                                            @if($att)
                                                <span class="badge badge-outline-secondary">
                                                    {{ strtoupper($att->verification_method) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!-- Bouton Arrivée rapide -->
                                                <form action="{{ route('teacher.attendance.submit', $schedule->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="check_in">
                                                    <button type="submit" class="btn btn-sm btn-success mr-1" {{ $schedule->can_check_in ? '' : 'disabled' }} title="Valider Arrivée">
                                                        <i class="fa fa-sign-in"></i>
                                                    </button>
                                                </form>

                                                <!-- Bouton Départ rapide -->
                                                <form action="{{ route('teacher.attendance.submit', $schedule->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="check_out">
                                                    <button type="submit" class="btn btn-sm btn-danger mr-1" {{ $schedule->can_check_out ? '' : 'disabled' }} title="Valider Départ">
                                                        <i class="fa fa-sign-out"></i>
                                                    </button>
                                                </form>

                                                <!-- Bouton Rattrapage Manuel (Admin Uniquement) -->
                                                @if($isAdmin)
                                                    <button type="button" class="btn btn-sm btn-warning btn-admin-edit" 
                                                            data-schedule="{{ json_encode($schedule) }}"
                                                            data-attendance="{{ json_encode($att) }}"
                                                            title="Rattrapage Manuel Admin (ex: Coupure électricité)">
                                                        <i class="fa fa-wrench"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center text-muted py-5">
                                            <i class="fa fa-calendar-times-o fa-3x d-block mb-2 text-muted"></i>
                                            Aucun cours trouvé pour ces critères.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODALE ADMIN : RATTRAPAGE DE POINTAGE -->
@if($isAdmin)
<div class="modal fade" id="adminAttendanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="adminAttendanceForm" method="POST">
            @csrf
            <input type="hidden" name="action" value="admin_update">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-wrench mr-2"></i>Rattrapage Manuel du Pointage
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Utilisez ce formulaire pour ajuster les heures de pointage en cas de problème technique (coupure d'électricité, panne internet, etc.).
                    </p>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Heure d'Arrivée (HH:MM)</label>
                        <input type="time" name="manual_check_in" id="modal_check_in" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Heure de Départ (HH:MM)</label>
                        <input type="time" name="manual_check_out" id="modal_check_out" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Motif / Note d'ajustement</label>
                        <textarea name="notes" id="modal_notes" class="form-control" rows="2" placeholder="Ex: Coupure d'électricité de 10h à 12h."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning font-weight-bold">
                        <i class="fa fa-save mr-1"></i> Appliquer la Mise à Jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-admin-edit').click(function() {
        let schedule = $(this).data('schedule');
        let att = $(this).data('attendance');

        // Mettre à jour l'URL du formulaire vers la route du cours sélectionné
        let actionUrl = "{{ route('attendance.teacher.attendance.submit', ':id') }}".replace(':id', schedule.id);
        $('#adminAttendanceForm').attr('action', actionUrl);

        // Pré-remplir les heures
        if (att) {
            if (att.check_in) {
                let checkInTime = new Date(att.check_in).toTimeString().substring(0, 5);
                $('#modal_check_in').val(checkInTime);
            } else {
                $('#modal_check_in').val('');
            }

            if (att.check_out) {
                let checkOutTime = new Date(att.check_out).toTimeString().substring(0, 5);
                $('#modal_check_out').val(checkOutTime);
            } else {
                $('#modal_check_out').val('');
            }
            $('#modal_notes').val(att.notes || '');
        } else {
            $('#modal_check_in').val('');
            $('#modal_check_out').val('');
            $('#modal_notes').val('');
        }

        $('#adminAttendanceModal').modal('show');
    });
});
</script>
@endpush