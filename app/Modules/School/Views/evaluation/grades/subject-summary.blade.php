@extends('School::layouts.app3', [
    'namePage' => 'Procès-Verbal & Synthèse par ECUE',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'evaluation.subject-summary',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
hr.hr-gradient { 
    height: 2px; 
    border: none; 
    background: linear-gradient(to right, #4099ff, #2ed8b6); 
    opacity: 1 !important; 
}

/* Neutraliser les balises select natives */
select.select2-custom {
    display: none;
    background: #ffffff !important;
}

/* --- BOÎTE SELECT2 PROPRE & BLANCHE --- */
.select2-container--default .select2-selection--single {
    height: 42px !important;
    background-color: #ffffff !important;
    background: #ffffff !important;
    border: 1px solid #dbe2ea !important;
    border-radius: 6px !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02) !important;
    transition: all 0.2s ease-in-out;
}

/* Focus / Survol */
.select2-container--default:hover .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #4099ff !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 153, 255, 0.15) !important;
}

/* Texte à l'intérieur */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding-left: 14px !important;
    padding-right: 35px !important;
    color: #374151 !important;
    font-weight: 500;
    font-size: 13.5px;
    background-color: transparent !important;
}

/* Placeholder */
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #9ca3af !important;
}

/* Flèche et croix */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 8px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6b7280 transparent transparent transparent !important;
    border-width: 5px 4px 0 4px !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
    color: #9ca3af !important;
    font-size: 16px;
    line-height: 40px !important;
    margin-right: 10px !important;
}

/* --- BOUTON AFFICHER --- */
#filter-form .btn-primary {
    height: 42px !important;
    border-radius: 6px !important;
    background: linear-gradient(135deg, #4099ff 0%, #2ed8b6 100%) !important;
    border: none !important;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 10px rgba(64, 153, 255, 0.25) !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <!-- CARTE PRINCIPALE : RECHERCHE ET ACTIONS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-award mr-2"></i>SYNTHÈSE ET RANGS PAR ECUE / MATIÈRE
                    </h4>

                    {{-- Le bouton ne s'affiche STRICTEMENT QUE SI la synthèse existe --}}
                    @if($classAverage)
                        <form method="POST" action="{{ route('evaluation.teacher.grades.calculate-archive') }}" id="archive-form">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $selectedSchoolId }}">
                            <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">
                            <input type="hidden" name="school_subject_id" value="{{ $selectedSubjectId }}">
                            <input type="hidden" name="academic_period_id" value="{{ $selectedPeriodId }}">
                            
                            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm text-white" id="btn-calculate">
                                <i class="feather icon-cpu mr-1"></i> {{ $classAverage ? 'Recalculer & Archiver' : 'Calculer & Archiver' }}
                            </button>
                        </form>
                    @endif
                </div>
                <hr class="hr-gradient">                    
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Formulaire de sélection aligné -->
                    <form method="GET" action="{{ route('evaluation.teacher.grades.subject-summary') }}" id="filter-form">
                        <div class="row align-items-end">
                            <!-- Filtre Établissement / Ecole -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="school_id" class="font-weight-bold text-dark">Établissement</label>
                                <select name="school_id" id="school_id" class="form-control select2" required>
                                    <option value="">-- Sélectionner une école --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $selectedSchoolId == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre Classe -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="school_class_id" class="font-weight-bold text-dark">Classe</label>
                                <select name="school_class_id" id="school_class_id" class="form-control select2" required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre Matière / ECUE -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label for="school_subject_id" class="font-weight-bold text-dark">Matière / ECUE</label>
                                <select name="school_subject_id" id="school_subject_id" class="form-control select2" required>
                                    <option value="">-- Sélectionner la matière --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject->name ?? $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre Période Académique -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label for="academic_period_id" class="font-weight-bold text-dark">Période Académique</label>
                                <select name="academic_period_id" id="academic_period_id" class="form-control select2" required>
                                    <option value="">-- Sélectionner la période --</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                                            {{ $period->periodTypeItem->name ?? $period->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bouton Afficher -->
                            <div class="col-lg-2 col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary btn-block waves-effect shadow-sm">
                                    <i class="feather icon-search mr-1"></i> Afficher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedClassId && $selectedSubjectId && $selectedPeriodId)
                @if($classAverage)
                    <!-- CARTES DES STATISTIQUES GLOBALES -->
                    <div class="row text-white mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-c-blue text-white update-card shadow-sm border-0">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-white">{{ number_format($classAverage->class_average, 2) }} / 20</h4>
                                            <h6 class="text-white m-b-0">Moyenne de Classe</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="feather icon-bar-chart-2 f-28"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-c-green text-white update-card shadow-sm border-0">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-white">{{ number_format($classAverage->max_average, 2) }} / 20</h4>
                                            <h6 class="text-white m-b-0">Moyenne Forte</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="feather icon-trending-up f-28"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-c-pink text-white update-card shadow-sm border-0">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-white">{{ number_format($classAverage->min_average, 2) }} / 20</h4>
                                            <h6 class="text-white m-b-0">Moyenne Faible</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="feather icon-trending-down f-28"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-c-yellow text-white update-card shadow-sm border-0">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-white">
                                                {{ $classAverage->total_students > 0 ? round(($classAverage->passed_count / $classAverage->total_students) * 100, 1) : 0 }}%
                                            </h4>
                                            <h6 class="text-white m-b-0">Taux Réussite ({{ $classAverage->passed_count }}/{{ $classAverage->total_students }})</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="feather icon-pie-chart f-28"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABLEAU DES ÉTUDIANTS, RANGS ET ARCHIVES -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 font-weight-bold text-dark">
                                <i class="feather icon-users mr-1"></i> PROCES-VERBAL D'ÉVALUATION ET ARCHIVE DES RANGS
                            </h5>
                            <span class="badge badge-info px-3 py-2">
                                <i class="feather icon-clock mr-1"></i> Archivé le : {{ optional($classAverage->calculated_at)->format('d/m/Y à H:i') ?? 'N/A' }}
                            </span>
                        </div>
                        <hr class="hr-gradient">
                        <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="10%">Rang</th>
                                            <th>N° Matricule</th>
                                            <th>Nom & Prénom(s)</th>
                                            <th class="text-center">Moyenne ECUE (/20)</th>
                                            <th class="text-center" width="15%">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($classAverage->studentAverages as $studentAvg)
                                            <tr>
                                                <td class="text-center">
                                                    @if($studentAvg->rank == 1)
                                                        <span class="badge badge-warning px-2 py-1 font-weight-bold text-dark">
                                                            <i class="fa fa-trophy mr-1"></i>{{ $studentAvg->rank_formatted }}
                                                        </span>
                                                    @elseif($studentAvg->rank && $studentAvg->rank <= 3)
                                                        <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                                            {{ $studentAvg->rank_formatted }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-inverse px-2 py-1">
                                                            {{ $studentAvg->rank_formatted ?? 'N/A' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="font-weight-bold">
                                                    {{ $studentAvg->registration->registration_number ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $studentAvg->registration->student->first_name ?? '' }} 
                                                    {{ $studentAvg->registration->student->last_name ?? '' }}
                                                </td>
                                                <td class="text-center font-weight-bold">
                                                    {{ !is_null($studentAvg->average) ? number_format($studentAvg->average, 2) : 'N/A' }}
                                                </td>
                                                <td class="text-center">
                                                    @if(!is_null($studentAvg->average))
                                                        @if($studentAvg->average >= 10)
                                                            <span class="badge badge-success px-2 py-1">
                                                                <i class="fa fa-check mr-1"></i>Validé
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger px-2 py-1">
                                                                <i class="fa fa-times mr-1"></i>Ajourné
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning px-2 py-1">Non Évalué</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Aucun résultat d'étudiant archivé trouvé.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning shadow-sm border-0 my-3">
                        <i class="feather icon-alert-triangle mr-2"></i>
                        Les moyennes pour cet ECUE et cette période n'ont pas encore été archivées. Veuillez cliquer sur le bouton <strong>"Calculer & Archiver"</strong> pour générer la synthèse.
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    // Initialisation de Select2
    if ($.fn.select2) {
        $('.select2').select2({
            width: '100%',
            placeholder: "-- Sélectionner --",
            allowClear: true
        });
    }

    const schoolSelect = $('#school_id');
    const classSelect = $('#school_class_id');
    const subjectSelect = $('#school_subject_id');
    const periodSelect = $('#academic_period_id');
    const archiveForm = document.getElementById('archive-form');

    // 1. Charger dynamiquement les classes et périodes
    schoolSelect.on('change', function () {
        const schoolId = $(this).val();

        classSelect.html('<option value="">-- Chargement... --</option>').trigger('change.select2');
        periodSelect.html('<option value="">-- Chargement... --</option>').trigger('change.select2');
        subjectSelect.html('<option value="">-- Sélectionner la matière --</option>').trigger('change.select2');

        if (!schoolId) {
            classSelect.html('<option value="">-- Sélectionner une classe --</option>').trigger('change.select2');
            periodSelect.html('<option value="">-- Sélectionner la période --</option>').trigger('change.select2');
            return;
        }

        fetch(`{{ route('evaluation.teacher.grades.get-school-details') }}?school_id=${schoolId}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                let classOptions = '<option value="">-- Sélectionner une classe --</option>';
                if (data.classes && data.classes.length > 0) {
                    data.classes.forEach(c => {
                        classOptions += `<option value="${c.id}">${c.name}</option>`;
                    });
                }
                classSelect.html(classOptions).trigger('change.select2');

                let periodOptions = '<option value="">-- Sélectionner la période --</option>';
                if (data.periods && data.periods.length > 0) {
                    data.periods.forEach(p => {
                        const periodName = p.period_type_item ? p.period_type_item.name : p.name;
                        periodOptions += `<option value="${p.id}">${periodName}</option>`;
                    });
                }
                periodSelect.html(periodOptions).trigger('change.select2');
            })
            .catch(error => {
                console.error('Erreur AJAX École :', error);
                classSelect.html('<option value="">-- Erreur de chargement --</option>').trigger('change.select2');
                periodSelect.html('<option value="">-- Erreur de chargement --</option>').trigger('change.select2');
            });
    });

    // 2. Charger dynamiquement les matières (ECUE)
    classSelect.on('change', function () {
        const classId = $(this).val();

        subjectSelect.html('<option value="">-- Chargement... --</option>').trigger('change.select2');

        if (!classId) {
            subjectSelect.html('<option value="">-- Sélectionner la matière --</option>').trigger('change.select2');
            return;
        }

        fetch(`{{ route('evaluation.teacher.grades.get-class-subjects') }}?school_class_id=${classId}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                let subjectOptions = '<option value="">-- Sélectionner la matière --</option>';
                if (data.subjects && data.subjects.length > 0) {
                    data.subjects.forEach(s => {
                        const subjectName = s.subject ? s.subject.name : (s.name || 'Matière sans nom');
                        subjectOptions += `<option value="${s.id}">${subjectName}</option>`;
                    });
                } else {
                    subjectOptions = '<option value="">-- Aucune matière assignée --</option>';
                }
                subjectSelect.html(subjectOptions).trigger('change.select2');
            })
            .catch(error => {
                console.error('Erreur AJAX Matières :', error);
                subjectSelect.html('<option value="">-- Erreur de chargement --</option>').trigger('change.select2');
            });
    });

    // Confirmation d'archivage / recalcul
    if (archiveForm) {
        archiveForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Calculer et Archiver ?',
                    text: "Cette action va régénérer la moyenne de la classe ainsi que les rangs de chaque étudiant pour cet ECUE.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1ab394',
                    cancelButtonColor: '#ed5565',
                    confirmButtonText: 'Oui, Calculer & Archiver !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm('Voulez-vous vraiment calculer et archiver les moyennes de cette classe ?')) {
                    form.submit();
                }
            }
        });
    }
});
</script>
@endpush
@endsection