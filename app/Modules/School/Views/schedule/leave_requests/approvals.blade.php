@extends('School::layouts.app2', ['namePage' => 'Validation des Absences', 'activePage' => 'leave_approvals'])

@section('content')
<div class="page-body">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary font-weight-bold"><i class="ti-check-box mr-2"></i>Demandes d'Autorisation en Attente</h5>
            
            <!-- Filtre Établissement -->
            <form method="GET" action="{{ route('leave-requests.approvals') }}" class="form-inline">
                <label class="mr-2 font-weight-bold">École :</label>
                <select name="school_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Sélectionner une école --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $currentSchoolId == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-block table-border-style">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Agent / Enseignant</th>
                            <th>Type</th>
                            <th>Période</th>
                            <th>Motif</th>
                            <th>Pièce</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRequests as $req)
                            <tr>
                                <td>
                                    <strong>{{ $req->user->name ?? 'N/A' }}</strong>
                                    <small class="d-block text-muted">{{ $req->user->email ?? '' }}</small>
                                </td>
                                <td><span class="badge badge-info text-capitalize">{{ str_replace('_', ' ', $req->type) }}</span></td>
                                <td>
                                    <small class="d-block font-weight-bold">{{ $req->start_date->format('d/m/Y H:i') }}</small>
                                    <small class="text-muted">au {{ $req->end_date->format('d/m/Y H:i') }}</small>
                                </td>
                                <td style="max-width: 250px;">{{ $req->reason }}</td>
                                <td>
                                    @if($req->document_path)
                                        <a href="{{ asset('storage/' . $req->document_path) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                            <i class="ti-file"></i> Voir
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Approuver -->
                                        <form action="{{ route('leave-requests.process', $req->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-xs btn-success font-weight-bold mr-1">
                                                <i class="ti-check"></i> Valider
                                            </button>
                                        </form>

                                        <!-- Refuser -->
                                        <button type="button" class="btn btn-xs btn-danger font-weight-bold" data-toggle="modal" data-target="#rejectModal{{ $req->id }}">
                                            <i class="ti-close"></i> Refuser
                                        </button>
                                    </div>

                                    <!-- Modal de Motif de Refus -->
                                    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content text-left">
                                                <form action="{{ route('leave-requests.process', $req->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold">Refuser la demande</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Motif du refus :</label>
                                                            <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-danger font-weight-bold">Confirmer le refus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="ti-check-box d-block mb-2" style="font-size: 1.5rem;"></i>
                                    Aucune demande d'autorisation en attente de traitement.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection