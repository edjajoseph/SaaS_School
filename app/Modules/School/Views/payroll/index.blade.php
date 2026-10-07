@extends('School::layouts.app3', [
    'namePage' => 'Gestion des Bulletins de Paie',
    'class' => 'login-page sidebar-mini',
    'activePage' => 'payroll.index',
    'activeModule' => 'paye',
])

@section('content')
<style>
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
                    <h4 class="mb-0 text-primary font-weight-bold">
                        <i class="feather icon-file-text mr-2"></i>{{ __("BULLETINS DE PAIE DU PERSONNEL") }}
                    </h4>
                    
                    <!-- Bouton Nouveau Bulletin via Modal Medium -->
                    <a data-header-class="bg-primary text-white" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('school.accounting.payrolls.create', ['school_id' => $schoolId]) }}" class="btn btn-primary btn-round waves-effect shadow-sm text-white" title="Générer un bulletin">
                        <i class="ace-icon fa fa-plus mr-1"></i> Générer une Paie
                    </a>
                </div>
                <hr class="hr-gradient">                   
                
                <div class="card-block">
                    @include('School::alerts.success')
                    @include('School::alerts.errors')

                    <!-- Notification SweetAlert Flash Session -->
                    @if($message = session('success'))
                        <script>
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Félicitations !', 
                                text: {!! json_encode($message) !!}, 
                                confirmButtonColor: '#1ab394' 
                            });
                        </script>
                    @elseif($message = session('error'))
                        <script>
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Désolé !', 
                                text: {!! json_encode($message) !!}, 
                                confirmButtonColor: '#ed5565' 
                            });
                        </script>
                    @endif

                    <!-- Formulaire de Filtres -->
                    <form method="GET" action="{{ route('school.accounting.payrolls.index') }}" class="mb-4">
                        <div class="row">
                            <!-- Filtre Établissement -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-university text-primary mr-1"></i> {{ __("Établissement") }}
                                    </label>
                                    <select name="school_id" id="school_id" class="form-control form-control-alternative">
                                        <option value="">-- Toutes les écoles --</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>
                                                {{ $school->name ?? $school->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filtre Agent/Employé (Mis à jour dynamiquement) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-user text-primary mr-1"></i> {{ __("Employé / Enseignant") }}
                                    </label>
                                    <select name="staff_id" id="staff_id" class="form-control form-control-alternative">
                                        <option value="">-- Tous les employés de l'école --</option>
                                        @foreach($teachers as $teacher)
                                            @php
                                                $nomComplet = trim(($teacher->personne->nom ?? '') . ' ' . ($teacher->personne->prenoms ?? ''));
                                            @endphp
                                            <option value="{{ $teacher->id }}" {{ request('staff_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ !empty($nomComplet) ? $nomComplet : ($teacher->personne->nom_complet ?? 'Employé N°' . $teacher->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filtre Date -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold text-dark">
                                        <i class="fa fa-calendar text-primary mr-1"></i> {{ __("Période après le") }}
                                    </label>
                                    <input type="date" name="period_start" class="form-control form-control-alternative" value="{{ request('period_start') }}">
                                </div>
                            </div>

                            <!-- Bouton Soumettre -->
                            <div class="col-md-2 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary btn-round waves-effect shadow-sm w-100">
                                    <i class="fa fa-filter mr-1"></i> {{ __("Filtrer") }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table des bulletins -->
                    <div class="dt-responsive table-responsive">
                        <table id="basic-btn" class="table table-striped table-bordered table-hover nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° Bulletin</th>
                                    <th>Employé</th>
                                    <th>Type Contrat</th>
                                    <th>Période</th>
                                    <th class="text-right">Brut Total</th>
                                    <th class="text-right">Retenues</th>
                                    <th class="text-right">Net à Payer</th>
                                    <th width="1%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                    <tr>
                                        <td><span class="badge badge-primary px-2 py-1">{{ $payroll->payroll_number }}</span></td>
                                        <td class="font-weight-bold text-dark">
                                            @php
                                                $p = $payroll->staff->personne ?? null;
                                                $nomPayroll = trim(($p->nom ?? '') . ' ' . ($p->prenom ?? ''));
                                            @endphp
                                            {{ !empty($nomPayroll) ? $nomPayroll : ($p->nom_complet ?? 'N/A') }}
                                        </td>
                                        <td>
                                            @if($payroll->contract && $payroll->contract->contract_type)
                                                <span class="badge badge-info px-2 py-1">{{ $payroll->contract->contract_type }}</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payroll->period_start)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($payroll->period_end)->format('d/m/Y') }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($payroll->gross_amount + $payroll->bonuses_amount, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right text-danger font-weight-bold">- {{ number_format($payroll->penalties_amount, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-right text-success font-weight-bold">{{ number_format($payroll->net_amount, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Action Aperçu -->
                                                <a data-toggle="modal" id="mediumButton1" data-target="#mediumModal1" data-attr="{{ route('school.accounting.payrolls.show', $payroll->id) }}" class="btn btn-warning btn-mini mr-1 text-white" title="Voir détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Action Télécharger PDF -->
                                                <a href="{{ route('school.accounting.payrolls.pdf', $payroll->id) }}" class="btn btn-primary btn-mini mr-1" title="Télécharger PDF" target="_blank">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>

                                                <!-- Action Supprimer -->
                                                <form action="{{ route('school.accounting.payrolls.destroy', $payroll->id) }}" method="POST" class="d-inline" 
                                                    onsubmit="event.preventDefault(); Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: 'Cette action supprimera définitivement cette fiche de paie !',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#ed5565',
                                                        cancelButtonColor: '#1ab394',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => { if (result.isConfirmed) this.submit(); });">
                                                    
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    <button type="submit" class="btn btn-danger btn-mini" title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x d-block mb-2 text-secondary"></i>
                                            Aucun bulletin de paie généré pour cet établissement.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $payrolls->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>                                                
        </div>
    </div>
</div>

<!-- Modals dynamiques AJAX -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><i class="fa fa-calculator mr-1"></i> GÉNÉRER UN BULLETIN DE PAIE</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediumModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <h5 class="modal-title text-info"><i class="fa fa-file-text-o mr-1"></i> DÉTAILS DU BULLETIN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody1">
                <div class="text-center p-3">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Chargement en cours...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolSelect = document.getElementById('school_id');
    const staffSelect = document.getElementById('staff_id');

    if (schoolSelect && staffSelect) {
        schoolSelect.addEventListener('change', function () {
            const schoolId = this.value;

            // Vider le menu déroulant du personnel
            staffSelect.innerHTML = '<option value="">Chargement des agents...</option>';
            staffSelect.disabled = true;

            if (!schoolId) {
                staffSelect.innerHTML = '<option value="">-- Tous les employés de l'école --</option>';
                staffSelect.disabled = false;
                return;
            }

            // Requête Fetch pour récupérer les agents de l'école sélectionnée
            fetch(`/school/accounting/payrolls/staff-by-school/${schoolId}`)
                .then(response => response.json())
                .then(data => {
                    staffSelect.innerHTML = '<option value="">-- Tous les employés de l'école --</option>';

                    if (data.length > 0) {
                        data.forEach(staff => {
                            const option = document.createElement('option');
                            option.value = staff.id;
                            option.textContent = staff.name;
                            staffSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Aucun agent sous contrat dans cet établissement';
                        staffSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des agents:', error);
                    staffSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                })
                .finally(() => {
                    staffSelect.disabled = false;
                });
        });
    }
});
</script>
@endsection