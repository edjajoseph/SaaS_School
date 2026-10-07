@extends('School::layouts.app3', [
    'namePage' => 'Édition des Bulletins & PV',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'evaluation.reports.index',
    'activeModule' => 'enseignement',
])

@section('content')
<style>
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
.select2-container { width: 100% !important; }
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <!-- Formulaire de Filtres Dynamiques avec Sélection d'Établissement -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-printer mr-2"></i>ÉDITION DES BULLETINS & PV DE DÉLIBÉRATION
                    </h4>
                </div>
                <hr class="hr-gradient my-0">
                <div class="card-body">
                <form method="GET" action="{{ route('evaluation.reports.index') }}" autocomplete="off">
                        <!-- align-items-end permet d'aligner le bouton en bas par rapport aux labels -->
                        <div class="row align-items-end">
                            
                            <!-- 1. Sélection de l'Établissement -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    <i class="fa fa-university text-primary mr-1"></i> {{ __('Établissement') }} <span class="text-danger">*</span>
                                </label>
                                <select name="school_id" id="school_id" class="form-control select2" required>
                                    <option value="">-- {{ __('Sélectionner un établissement') }} --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ (request('school_id', $schoolId) == $school->id) ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 2. Sélection de l'Année Académique -->
                            <div class="col-xl-2 col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    <i class="fa fa-calendar text-primary mr-1"></i> {{ __('Année Académique') }} <span class="text-danger">*</span>
                                </label>
                                <select name="academic_year_id" id="academic_year_id" class="form-control select2" required>
                                    <option value="">-- {{ __('Sélectionner une année') }} --</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 3. Sélection de la Classe -->
                            <div class="col-xl-3 col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    <i class="fa fa-users text-primary mr-1"></i> {{ __('Classe / Promotion') }} <span class="text-danger">*</span>
                                </label>
                                <select name="school_class_id" id="school_class_id" class="form-control select2" required>
                                    <option value="">-- {{ __('Sélectionner une classe') }} --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 4. Sélection de la Période -->
                            <div class="col-xl-2 col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    <i class="fa fa-clock-o text-primary mr-1"></i> {{ __('Période Académique') }} <span class="text-danger">*</span>
                                </label>
                                <select name="academic_period_id" id="academic_period_id" class="form-control select2" required>
                                    <option value="">-- {{ __('Sélectionner une période') }} --</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->periodTypeItem->name ?? $period->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 5. Bouton Filtrer Aligné -->
                            <div class="col-xl-2 col-md-12 mb-3">
                                <button type="submit" class="btn btn-warning waves-effect shadow-sm w-100" style="height: 38px;">
                                    <i class="fa fa-filter mr-1"></i> {{ __('Afficher les étudiants') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des Étudiants et Actions -->
            @if(request()->filled('school_class_id') && request()->filled('academic_year_id'))
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            <i class="fa fa-list mr-2"></i>{{ __('Liste des Étudiants') }} ({{ $students->count() }})
                        </h5>

                        <div>
                            @if(request()->filled('academic_period_id'))
                                <a href="{{ route('evaluation.reports.pv-deliberation.pdf', ['schoolClass' => request('school_class_id'), 'academicPeriod' => request('academic_period_id')]) }}" 
                                    target="_blank"
                                   class="btn btn-inverse btn-round waves-effect shadow-sm">
                                    <i class="fa fa-file-pdf-o mr-1"></i> {{ __('Imprimer PV de Délibération') }}
                                </a>
                            @else
                                <button type="button" class="btn btn-inverse btn-round disabled" title="{{ __('Sélectionnez une période académique') }}">
                                    <i class="fa fa-file-pdf-o mr-1"></i> {{ __('Imprimer PV de Délibération') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-block">
                        @include('School::alerts.success')
                        @include('School::alerts.errors')

                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-bordered table-hover nowrap align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="15%">Matricule</th>
                                        <th>Nom & Prénoms</th>
                                        <th width="15%" class="text-center">Statut</th>
                                        <th width="25%" class="text-center">Actions / Documents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $registration)
                                        @php
                                            $student = $registration->student;
                                            $personne = $student->personne ?? null;
                                            $periodId = request('academic_period_id');
                                        @endphp
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $student->matricule ?? 'N/A' }}</span>
                                            </td>
                                            <td class="font-weight-bold text-uppercase">
                                                {{ $personne ? $personne->nom . ' ' . $personne->prenoms : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i>Inscrit</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($periodId)
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('evaluation.reports.bulletin.pdf', ['registration' => $registration->id, 'academicPeriod' => $periodId]) }}" 
                                                           target="_blank" 
                                                           class="btn btn-primary btn-mini" 
                                                           title="Bulletin Trimestriel / Semestriel">
                                                            <i class="fa fa-file-text-o mr-1"></i> Bulletin
                                                        </a>

                                                        <a href="{{ route('evaluation.reports.releve-lmd.pdf', ['registration' => $registration->id, 'academicPeriod' => $periodId]) }}" 
                                                           target="_blank" 
                                                           class="btn btn-info btn-mini" 
                                                           title="Relevé de Notes LMD">
                                                            <i class="fa fa-graduation-cap mr-1"></i> Relevé LMD
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-muted font-italic small">
                                                        <i class="fa fa-info-circle mr-1"></i>Sélectionnez une période
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Aucun étudiant inscrit trouvé.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info border-info shadow-sm">
                    <i class="fa fa-info-circle mr-2"></i> {{ __('Veuillez sélectionner les critères ci-dessus pour afficher la liste des étudiants.') }}
                </div>
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success"><h5 class="modal-title text-primary"><i class="fa fa-file-text-o"></i> ÉVALUATION</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="mediumBody"><div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x"></i> Chargement...</div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }

    // Écoute sur document pour intercepter le changement même après modification DOM
    $(document).on('change', '#school_id', function () {
        let schoolId = $(this).val();
        let $classSelect = $('#school_class_id');

        console.log("Changement d'établissement détecté ID:", schoolId);

        $classSelect.empty().append('<option value="">-- Sélectionner une classe --</option>').trigger('change');

        if (schoolId) {
            $.ajax({
                url: "{{ route('evaluation.reports.ajax.school-options') }}",
                type: "GET",
                dataType: "json",
                data: { school_id: schoolId },
                success: function (response) {
                    console.log("Réponse reçue:", response);
                    if (response.classes && response.classes.length > 0) {
                        $.each(response.classes, function (key, item) {
                            $classSelect.append(new Option(item.name, item.id, false, false));
                        });
                    }
                    $classSelect.trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error("Erreur AJAX:", xhr.responseText);
                }
            });
        }
    });

    $(document).on('change', '#academic_year_id', function () {
        let yearId = $(this).val();
        let schoolId = $('#school_id').val();
        let $periodSelect = $('#academic_period_id');

        console.log("Changement d'année détecté ID:", yearId);

        $periodSelect.empty().append('<option value="">-- Sélectionner une période --</option>').trigger('change');

        if (yearId) {
            $.ajax({
                url: "{{ route('evaluation.reports.ajax.year-periods') }}",
                type: "GET",
                dataType: "json",
                data: { 
                    academic_year_id: yearId,
                    school_id: schoolId 
                },
                success: function (response) {
                    console.log("Réponse périodes reçue:", response);
                    if (response && response.length > 0) {
                        $.each(response, function (key, item) {
                            $periodSelect.append(new Option(item.name, item.id, false, false));
                        });
                    }
                    $periodSelect.trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error("Erreur AJAX:", xhr.responseText);
                }
            });
        }
    });
});
</script>
@endpush