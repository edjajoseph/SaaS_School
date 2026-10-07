@extends('School::layouts.app3', [
    'namePage' => 'Procès-Verbal des Notes',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'evaluations.grades',
    'activeModule' => 'classe',
])

@section('content')
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre Établissement / Classe / Évaluation -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-filter mr-2"></i>Sélectionner une évaluation
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reporting.teacher.documents.evaluation-grades') }}" id="filter-form">
                        <div class="row align-items-end">
                            
                            <!-- 1. École -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Établissement</label>
                                <select name="school_id" id="school_id" class="form-control" onchange="document.getElementById('school_class_id').value=''; document.getElementById('evaluation_id').value=''; this.form.submit();" required>
                                    <option value="">-- Choisir une école --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 2. Classe -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold">Classe / Promotion</label>
                                <select name="school_class_id" id="school_class_id" class="form-control" onchange="document.getElementById('evaluation_id').value=''; this.form.submit();" {{ !$selectedSchoolId ? 'disabled' : '' }} required>
                                    <option value="">-- Choisir une classe --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 3. Évaluation -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Évaluation / Devoir</label>
                                <select name="evaluation_id" id="evaluation_id" class="form-control" {{ !$selectedClassId ? 'disabled' : '' }} required>
                                    <option value="">-- Choisir une évaluation --</option>
                                    @foreach($evaluations as $eval)
                                        <option value="{{ $eval->id }}" {{ $selectedEvaluationId == $eval->id ? 'selected' : '' }}>
                                            [{{ $eval->type_name }}] {{ $eval->title }} - {{ $eval->subject_name }} ({{ \Carbon\Carbon::parse($eval->evaluated_at)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bouton Filtrer -->
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-primary w-100" {{ !$selectedClassId ? 'disabled' : '' }}>
                                    <i class="fa fa-search mr-1"></i> Afficher
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Résultat : Fiche de Notes et Bouton de Print -->
            @if($selectedEvaluation)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-dark font-weight-bold">
                                {{ $selectedEvaluation->title }} — <span class="text-primary">{{ $selectedEvaluation->class_name }}</span>
                            </h5>
                            <small class="text-muted">
                                Matière : <strong>{{ $selectedEvaluation->subject_name }}</strong> | 
                                Type : <strong>{{ $selectedEvaluation->type_name }}</strong> | 
                                Bareme : <strong>/{{ $selectedEvaluation->max_score }}</strong> | 
                                Coeff : <strong>{{ $selectedEvaluation->coefficient }}</strong>
                            </small>
                        </div>
                        <a href="{{ route('reporting.teacher.documents.evaluation-grades.print', $selectedEvaluation->id) }}" target="_blank" class="btn btn-danger">
                            <i class="fa fa-print mr-1"></i> Imprimer la liste des notes
                        </a>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Matricule</th>
                                        <th style="width: 45%;">Nom & Prénoms</th>
                                        <th style="width: 15%;" class="text-center">Note / {{ $selectedEvaluation->max_score }}</th>
                                        <th style="width: 20%;">Observation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($grades as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><code>{{ $item->matricule ?? 'N/A' }}</code></td>
                                            <td><strong>{{ $item->nom }}</strong> {{ $item->prenoms }}</td>
                                            <td class="text-center font-weight-bold">
                                                @if($item->is_absent)
                                                    <span class="badge badge-warning">ABSENT</span>
                                                @elseif(!is_null($item->score))
                                                    <span class="font-bold {{ $item->score < ($selectedEvaluation->max_score / 2) ? 'text-danger' : 'text-success' }}">
                                                        {{ number_format($item->score, 2, ',', ' ') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->observation ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun élève/note enregistré pour cette évaluation.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection