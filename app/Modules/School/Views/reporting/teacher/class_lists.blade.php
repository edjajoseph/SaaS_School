@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Présences',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'attendance.index',
    'activeModule' => 'classe',
])

@section('content')
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            
            <!-- Filtre de recherche -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fa fa-filter mr-2"></i>Sélectionner une école et une classe
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reporting.teacher.documents.class-list') }}" id="filter-form">
                        <div class="row align-items-end">
                            
                            <!-- 1. Sélection de l'École -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Établissement / École</label>
                                <select name="school_id" id="school_id" class="form-control" onchange="document.getElementById('school_class_id').value=''; this.form.submit();" required>
                                    <option value="">-- Choisir une école --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 2. Sélection de la Classe (filtrée selon l'école) -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Classe / Promotion</label>
                                <select name="school_class_id" id="school_class_id" class="form-control" {{ !$selectedSchoolId ? 'disabled' : '' }} required>
                                    <option value="">-- Choisir une classe --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bouton Filtrer -->
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary w-100" {{ !$selectedSchoolId ? 'disabled' : '' }}>
                                    <i class="fa fa-search mr-1"></i> Afficher les élèves
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des élèves trouvés & Bouton d'impression -->
            @if($selectedClass)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            Liste des élèves - <span class="text-primary">{{ $selectedClass->name }}</span>
                            <span class="badge badge-pill badge-info ml-2">{{ $students->count() }} Élève(s)</span>
                        </h5>
                        @if($students->isNotEmpty())
                            <a href="{{ route('reporting.teacher.documents.class-list.print', ['classId' => $selectedClass->id, 'school_id' => $selectedSchoolId]) }}" target="_blank" class="btn btn-danger">
                                <i class="fa fa-print mr-1"></i> Imprimer la liste (PDF)
                            </a>
                        @endif
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Matricule</th>
                                        <th>Nom & Prénoms</th>
                                        <th>Genre</th>
                                        <th>Date de naissance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><code>{{ $student->matricule ?? 'N/A' }}</code></td>
                                            <td><strong>{{ $student->nom }}</strong> {{ $student->prenoms }}</td>
                                            <td>
                                                @if(strtoupper($student->sexe) == 'M')
                                                    <span class="badge badge-primary">Masculin</span>
                                                @else
                                                    <span class="badge badge-danger">Féminin</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun élève inscrit dans cette classe pour cet établissement.
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