@extends('School::layouts.app3', [
    'namePage' => 'État de Paie des Vacataires',
    'class' => 'sidebar-mini',
    'activePage' => 'teacher.payroll',
    'activeModule' => 'comptabilite',
])

@section('content')
<div class="page-body">
    <div class="container-fluid">
        
        <!-- Cartes Statistiques KPI -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm border-0">
                    <div class="card-body py-3">
                        <h6 class="text-uppercase mb-1 font-weight-bold opacity-75">Heures Cumulées</h6>
                        <h3 class="mb-0 font-weight-bold">{{ number_format($grandTotalHours, 2, ',', ' ') }} h</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm border-0">
                    <div class="card-body py-3">
                        <h6 class="text-uppercase mb-1 font-weight-bold opacity-75">Total Ponctions / Retards</h6>
                        <h3 class="mb-0 font-weight-bold">{{ number_format($grandTotalPenalties, 0, ',', ' ') }} FCFA</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body py-3">
                        <h6 class="text-uppercase mb-1 font-weight-bold opacity-75">Masse Salariale Nette</h6>
                        <h3 class="mb-0 font-weight-bold">{{ number_format($grandTotalNet, 0, ',', ' ') }} FCFA</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Actions d'Exportation -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark font-weight-bold">
                    <i class="fa fa-calculator text-primary mr-2"></i>RECAPITULATIF DES ÉMARGEMENTS & PAIE
                </h5>
                <div>
                    <a href="{{ route('attendance.teacher.payroll.export.pdf', request()->all()) }}" class="btn btn-sm btn-danger font-weight-bold mr-2">
                        <i class="fa fa-file-pdf-o mr-1"></i> Exporter en PDF
                    </a>
                    <a href="{{ route('attendance.teacher.payroll.export.excel', request()->all()) }}" class="btn btn-sm btn-success font-weight-bold">
                        <i class="fa fa-file-excel-o mr-1"></i> Exporter CSV / Excel
                    </a>                    
                </div>
            </div>
            <div class="card-body bg-light border-bottom">
                <form method="GET" action="{{ route('attendance.teacher.payroll.dashboard') }}" class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold text-dark">Date Début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold text-dark">Date Fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="font-weight-bold text-dark">Enseignant</label>
                        <select name="staff_id" class="form-control">
                            <option value="">-- Tous les enseignants --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $staffId == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->personne->nom ?? '' }} {{ $teacher->personne->prenoms ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            <i class="fa fa-search mr-1"></i> Calculer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tableau Récapitulatif Comptable -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Enseignant</th>
                                <th class="text-center">Taux Horaire</th>
                                <th class="text-center">Séances</th>
                                <th class="text-center">Heures Eff.</th>
                                <th class="text-center">Retard Total</th>
                                <th class="text-right">Montant Brut</th>
                                <th class="text-right text-warning">Ponction / Pénalités</th>
                                <th class="text-right font-weight-bold">Net à Payer</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($summary as $row)
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        {{ $row['staff']->personne->nom ?? '' }} {{ $row['staff']->personne->prenoms ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($row['staff']->hourly_rate ?? 0, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary fs-12">{{ $row['total_sessions'] }} cours</span>
                                    </td>
                                    <td class="text-center font-weight-bold text-primary">
                                        {{ $row['total_hours'] }} h
                                    </td>
                                    <td class="text-center">
                                        @if($row['total_late_min'] > 0)
                                            <span class="badge badge-warning text-dark">{{ $row['total_late_min'] }} min</span>
                                        @else
                                            <span class="badge badge-light text-muted">0 min</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($row['gross_total'], 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-right text-danger font-weight-bold">
                                        - {{ number_format($row['penalties_total'], 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-right font-weight-bold text-success fs-15">
                                        {{ number_format($row['net_total'], 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Telecharger le PDF -->
                                            <a href="{{ route('attendance.teacher.payroll.export.individual', ['staffId' => $row['staff']->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Télécharger la Fiche PDF">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>

                                            <!-- Envoyer par Mail -->
                                            <form action="{{ route('attendance.teacher.payroll.send.email', ['staffId' => $row['staff']->id]) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                                <input type="hidden" name="end_date" value="{{ $endDate }}">
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        title="Envoyer la fiche par e-mail"
                                                        onclick="return confirm('Envoyer la fiche de paie à cet enseignant par e-mail ?')">
                                                    <i class="fa fa-envelope-o"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="fa fa-folder-open-o fa-3x d-block mb-2 text-muted"></i>
                                        Aucun enregistrement d'émargement trouvé pour cette période.
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
@endsection