@extends('School::layouts.app3', [
    'namePage' => 'Autorisations aux Examens',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'exam-clearance',
    'activeModule' => 'accounting',
])

@section('content')
<style>
hr {
    border: 0;
    height: 1px;
    background-color: #e9ecef;
    margin: 1.25rem 0;
    opacity: 1 !important;
}

hr.hr-gradient {
    height: 2px;
    border: none;
    background: linear-gradient(to right, #4099ff, #2ed8b6);
    opacity: 1 !important;
}
</style>

<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">                
                <div class="card-header bg-white table-card-header d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1 font-weight-bold text-dark">
                            <i class="fas fa-user-check text-success mr-2"></i>Autorisations aux Examens
                        </h3>
                        <p class="text-muted small mb-0">Contrôle d'éligibilité des étudiants selon le seuil de recouvrement exigé</p>
                    </div>
                </div>               
                <hr class="hr-gradient">                   
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Formulaire de sélection de classe, année académique et seuil -->
                    <form action="{{ route('school.accounting.reports.exam-clearance') }}" method="GET" class="row align-items-end mb-4">
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Année Académique</label>
                            <select name="academic_year_id" class="form-control select2">
                                <option value="">-- Toutes les années --</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                        {{ $year->name ?? $year->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Classe</label>
                            <select name="school_class_id" class="form-control select2" required>
                                <option value="">-- Sélectionner une classe --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name ?? $c->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold">Seuil Exigé (%)</label>
                            <input type="number" name="threshold" class="form-control" value="{{ $threshold }}" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm text-white">
                                <i class="fa fa-filter mr-1"></i> Filtrer
                            </button>
                            @if($classId)
                                <a href="{{ route('school.accounting.reports.exam-clearance-pdf', ['school_class_id' => $classId, 'academic_year_id' => $academicYearId, 'threshold' => $threshold]) }}" target="_blank" class="btn btn-danger btn-round waves-effect shadow-sm text-white" title="Imprimer la liste PDF">
                                    <i class="fa fa-file-pdf-o mr-1"></i> PDF
                                </a>
                            @endif
                        </div>
                    </form>

                    @if($studentsClearance->isNotEmpty())
                        <div class="dt-responsive table-responsive">
                            <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="12%">Matricule</th>
                                        <th width="28%">Étudiant</th>
                                        <th width="15%">Net À Payer</th>
                                        <th width="15%">Montant Payé</th>
                                        <th width="10%">Taux (%)</th>
                                        <th width="20%" class="text-center">Statut Examen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsClearance as $item)
                                        <tr>
                                            <td class="font-weight-bold text-dark">{{ $item['student']->matricule }}</td>
                                            <td>{{ $item['student']->personne->nom ?? '' }} {{ $item['student']->personne->prenoms ?? '' }}</td>
                                            <td>{{ number_format($item['total_due'], 0, ',', ' ') }} FCFA</td>
                                            <td class="text-success font-weight-bold">{{ number_format($item['total_paid'], 0, ',', ' ') }} FCFA</td>
                                            <td class="font-weight-bold">{{ $item['rate'] }} %</td>
                                            <td class="text-center align-middle">
                                                @if($item['is_allowed'])
                                                    <span class="badge badge-success px-3 py-2"><i class="fa fa-check mr-1"></i> AUTORISÉ</span>
                                                @else
                                                    <span class="badge badge-danger px-3 py-2"><i class="fa fa-times mr-1"></i> REFUSÉ</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>                                                
        </div>
    </div>
</div>
@endsection