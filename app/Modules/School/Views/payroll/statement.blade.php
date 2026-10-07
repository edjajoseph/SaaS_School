@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-white shadow-lg rounded-xl my-6 font-sans text-gray-800">
    
    <!-- En-tête -->
    <div class="flex justify-between items-center border-b pb-6 mb-6">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-wider text-indigo-900">ÉTAT D'ÉMARGEMENT & DE PAIE VACATIONS</h1>
            <p class="text-xs text-gray-500">Période du {{ $payroll['period']['start']->format('d/m/Y') }} au {{ $payroll['period']['end']->format('d/m/Y') }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-700">{{ strtoupper($payroll['staff']->personne->nom) }} {{ $payroll['staff']->personne->prenoms }}</h2>
            <p class="text-xs font-semibold text-indigo-600">Matricule : {{ $payroll['staff']->registration_number }}</p>
        </div>
    </div>

    <!-- Synthèse des Heures et Montants -->
    <div class="overflow-hidden border border-gray-300 rounded-lg mb-6">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="bg-indigo-900 text-white font-semibold uppercase">
                    <th class="p-3 border">Type de Séance</th>
                    <th class="p-3 border text-center">Volume Effectué (Heures)</th>
                    <th class="p-3 border text-right">Taux Horaire</th>
                    <th class="p-3 border text-right">Montant Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-700">Cours Magistraux (CM)</td>
                    <td class="p-3 text-center font-medium">{{ number_format($payroll['summary']['CM']['hours'], 2) }} h</td>
                    <td class="p-3 text-right">{{ number_format($payroll['summary']['CM']['rate'], 0, ',', ' ') }} FCFA</td>
                    <td class="p-3 text-right font-bold text-gray-800">{{ number_format($payroll['summary']['CM']['total'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-700">Travaux Dirigés (TD)</td>
                    <td class="p-3 text-center font-medium">{{ number_format($payroll['summary']['TD']['hours'], 2) }} h</td>
                    <td class="p-3 text-right">{{ number_format($payroll['summary']['TD']['rate'], 0, ',', ' ') }} FCFA</td>
                    <td class="p-3 text-right font-bold text-gray-800">{{ number_format($payroll['summary']['TD']['total'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-700">Travaux Pratiques (TP)</td>
                    <td class="p-3 text-center font-medium">{{ number_format($payroll['summary']['TP']['hours'], 2) }} h</td>
                    <td class="p-3 text-right">{{ number_format($payroll['summary']['TP']['rate'], 0, ',', ' ') }} FCFA</td>
                    <td class="p-3 text-right font-bold text-gray-800">{{ number_format($payroll['summary']['TP']['total'], 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="bg-indigo-50 font-bold border-t-2 border-indigo-900">
                    <td class="p-3 text-indigo-900 uppercase">Total Général</td>
                    <td class="p-3 text-center text-indigo-900 text-sm">{{ number_format($payroll['total_hours'], 2) }} h</td>
                    <td class="p-3 text-right text-gray-400">-</td>
                    <td class="p-3 text-right text-indigo-900 text-base">{{ number_format($payroll['gross_total'], 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-2 gap-6 items-center pt-8 border-t text-xs">
        <div>
            <p class="font-bold">Signature de l'Enseignant :</p>
            <div class="h-16 mt-2 border-b border-dashed border-gray-400"></div>
        </div>
        <div class="text-right">
            <p class="font-bold">Approbation de la Direction Financière :</p>
            <div class="h-16 mt-2 border-b border-dashed border-gray-400"></div>
        </div>
    </div>
</div>
@endsection