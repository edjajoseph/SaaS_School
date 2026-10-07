<div class="card border shadow-sm mb-3">
    <!-- En-tête de la carte avec Bouton Imprimer -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
        <h6 class="m-0 font-weight-bold text-primary">Détails du Bulletin de Paie</h6>
        <div>
            <!-- Bouton Impression directe de la page ou redirection vers route PDF -->
            <a href="{{ route('school.accounting.payrolls.pdf', $payroll->id) }}" class="btn btn-primary btn-mini mr-1" title="Télécharger PDF" target="_blank">
                <i class="fa fa-file-pdf-o"></i> Imprimer / PDF
            </a>
            
            {{-- Exemple si vous préférez pointer directement vers la génération de votre PDF Blade : --}}
            {{-- 
            <a href="{{ route('payrolls.download-pdf', $payroll->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Imprimer / PDF
            </a> 
            --}}
        </div>
    </div>

    <div class="card-body p-3">
        <!-- Informations générales -->
        <div class="row mb-3 pb-2 border-bottom text-sm">
            <div class="col-6">
                <strong>Matricule :</strong> {{ $payroll->staff->registration_number ?? 'N/A' }}<br>
                <strong>Employé :</strong> {{ $payroll->staff->personne->nom_complet ?? 'N/A' }}<br>
                <strong>Fonction :</strong> {{ $payroll->staff->job_title ?? 'Enseignant' }}
            </div>
            <div class="col-6 text-right">
                <strong>N° Bulletin :</strong> <span class="badge badge-primary">{{ $payroll->payroll_number }}</span><br>
                <strong>Période :</strong> {{ \Carbon\Carbon::parse($payroll->period_start)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($payroll->period_end)->format('d/m/Y') }}<br>
                <strong>Mode de Paiement :</strong> Virement / Espèces
            </div>
        </div>

        <!-- Tableau Détaillé des Lignes de Paie -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Désignation / Rubrique</th>
                        <th class="text-right">Base</th>
                        <th class="text-center">Taux / %</th>
                        <th class="text-right">Gains</th>
                        <th class="text-right">Retenues</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ligne obligatoire : Salaire de base ou Brut de vacation -->
                    <tr>
                        <td class="font-weight-bold">{{ ($payroll->total_hours ?? 0) > 0 ? 'Rémunération des cours (Vacations)' : 'Salaire de Base' }}</td>
                        <td class="text-right">{{ number_format($payroll->gross_amount, 0, ',', ' ') }}</td>
                        <td class="text-center">-</td>
                        <td class="text-right text-success font-weight-bold">
                            {{ number_format($payroll->gross_amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="text-right text-danger">-</td>
                    </tr>

                    <!-- Affichage des autres rubriques (Primes, Cotisations, Taxes, etc.) -->
                    @if(isset($payroll->items) && count($payroll->items) > 0)
                        @foreach($payroll->items as $item)
                            {{-- On évite les doublons si le salaire de base est déjà présent dans $payroll->items --}}
                            @if(!str_contains(strtolower($item->label), 'salaire de base') && !str_contains(strtolower($item->label), 'vacation'))
                                <tr>
                                    <td>{{ $item->label }}</td>
                                    <td class="text-right">{{ $item->base_amount ? number_format($item->base_amount, 0, ',', ' ') : '-' }}</td>
                                    <td class="text-center">
                                        {{ $item->rate_or_value > 0 && $item->rate_or_value < 100 ? number_format($item->rate_or_value, 2) . '%' : '-' }}
                                    </td>
                                    <td class="text-right text-success">
                                        {{ in_array($item->type, ['gain', 'gain_non_taxable']) ? number_format($item->amount, 0, ',', ' ') . ' FCFA' : '-' }}
                                    </td>
                                    <td class="text-right text-danger">
                                        {{ $item->type === 'deduction' ? number_format($item->amount, 0, ',', ' ') . ' FCFA' : '-' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
                <tfoot class="font-weight-bold bg-light">
                    <tr>
                        <td colspan="3" class="text-right">TOTAL BRUT & RETENUES</td>
                        <td class="text-right text-success">{{ number_format($payroll->gross_amount + ($payroll->bonuses_amount ?? 0), 0, ',', ' ') }} FCFA</td>
                        <td class="text-right text-danger">- {{ number_format($payroll->penalties_amount ?? 0, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="table-primary text-dark" style="font-size: 1.1em;">
                        <td colspan="3" class="text-right font-weight-bold">NET À PAYER (FCFA)</td>
                        <td colspan="2" class="text-right font-weight-bold text-primary">{{ number_format($payroll->net_amount, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>