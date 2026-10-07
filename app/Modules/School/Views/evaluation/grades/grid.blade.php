<div class="page-body">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">
                <i class="fa fa-table mr-2"></i>SAISIE DES NOTES : {{ $evaluation->title }} ({{ $evaluation->schoolClass->name }})
            </h4>
            <span class="badge badge-light text-primary font-weight-bold px-3 py-2">Barème: /{{ $evaluation->max_score }} | Coeff: {{ $evaluation->coefficient }}</span>
        </div>
        <div class="card-body">
            <form id="batch-grade-form" action="{{ route('evaluation.evaluations.grades.batch-store', $evaluation->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Nom & Prénoms</th>
                                <th width="15%" class="text-center">Note (/{{ $evaluation->max_score }})</th>
                                <th width="10%" class="text-center">Absent ?</th>
                                <th width="10%" class="text-center">Justifié ?</th>
                                <th width="25%">Observations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $index => $reg)
                                @php $grade = $existingGrades->get($reg->id); @endphp
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="hidden" name="grades[{{ $index }}][registration_id]" value="{{ $reg->id }}">
                                        <strong>{{ $reg->student->personne->nom ?? 'Nom Étudiant' }} {{ $reg->student->personne->prenoms ?? 'Prenoms Étudiant' }}</strong>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="{{ $evaluation->max_score }}" 
                                               name="grades[{{ $index }}][score]" 
                                               class="form-control text-center font-weight-bold score-input" 
                                               value="{{ old("grades.$index.score", $grade?->score) }}" 
                                               {{ $grade?->is_absent ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="grades[{{ $index }}][is_absent]" value="1" 
                                               class="absent-checkbox" {{ $grade?->is_absent ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center align-middle">
                                        <input type="checkbox" name="grades[{{ $index }}][is_justified]" value="1" 
                                               class="justified-checkbox" {{ $grade?->is_justified ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="grades[{{ $index }}][remarks]" class="form-control" 
                                               value="{{ old("grades.$index.remarks", $grade?->remarks) }}" placeholder="Rien à signaler...">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucun étudiant inscrit dans cette classe.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="{{ route('evaluation.evaluations.index') }}" class="btn btn-secondary btn-round">
                    <i class="fa fa-arrow-left mr-1"></i> Retour aux évaluations
                </a>
                <button type="button" onclick="document.getElementById('batch-grade-form').submit();" class="btn btn-success btn-round shadow-sm">
    <i class="fa fa-save mr-1"></i> Force Submit POST
</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Désactive/active le champ note si "Absent" est coché
    document.querySelectorAll('.absent-checkbox').forEach(function(chk) {
        chk.addEventListener('change', function() {
            let row = this.closest('tr');
            let scoreInput = row.querySelector('.score-input');
            if (this.checked) {
                scoreInput.value = '';
                scoreInput.disabled = true;
            } else {
                scoreInput.disabled = false;
            }
        });
    });
});
</script>