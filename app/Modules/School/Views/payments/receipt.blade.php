@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-8 bg-white border-2 border-gray-300 shadow-md rounded-xl my-6 font-sans text-gray-800">
    
    <!-- En-tête Reçu -->
    <div class="flex justify-between items-start border-b-2 border-indigo-900 pb-4 mb-6">
        <div>
            <h1 class="text-lg font-bold text-indigo-900 uppercase">{{ $payment->studentFeeAccount->enrollment->school->name }}</h1>
            <p class="text-xs text-gray-500">SERVICE COMPTABILITÉ & RECOUVREMENT</p>
        </div>
        <div class="text-right">
            <span class="inline-block px-3 py-1 bg-indigo-900 text-white font-mono text-sm font-bold rounded">
                N° {{ $payment->receipt_number }}
            </span>
            <p class="text-xs text-gray-500 mt-1">Date : {{ $payment->paid_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Informations Étudiant -->
    <div class="bg-gray-50 p-4 rounded-lg border text-xs space-y-1 mb-6">
        <p><span class="font-bold text-gray-600">Matricule :</span> {{ $payment->studentFeeAccount->enrollment->student->registration_number }}</p>
        <p><span class="font-bold text-gray-600">Nom & Prénoms :</span> {{ strtoupper($payment->studentFeeAccount->enrollment->student->personne->nom) }} {{ $payment->studentFeeAccount->enrollment->student->personne->prenoms }}</p>
        <p><span class="font-bold text-gray-600">Classe / Niveau :</span> {{ $payment->studentFeeAccount->enrollment->schoolClass->name }}</p>
    </div>

    <!-- Détails du Règlement -->
    <div class="space-y-3 text-xs mb-6">
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-600">Mode de Règlement :</span>
            <span class="font-bold uppercase text-gray-800">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
        </div>
        @if($payment->transaction_reference)
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-600">Référence Transaction :</span>
            <span class="font-mono font-bold text-gray-800">{{ $payment->transaction_reference }}</span>
        </div>
        @endif
        <div class="flex justify-between border-b pb-2 items-center">
            <span class="text-gray-600">Montant Versé :</span>
            <span class="text-lg font-bold text-green-700">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>

    <!-- État de Compte Après Paiement -->
    <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg text-xs grid grid-cols-3 text-center mb-8">
        <div>
            <p class="text-gray-500">Scolarité Net</p>
            <p class="font-bold text-gray-800">{{ number_format($payment->studentFeeAccount->total_due - $payment->studentFeeAccount->discount_amount, 0, ',', ' ') }} FCFA</p>
        </div>
        <div>
            <p class="text-gray-500">Total Encaisse</p>
            <p class="font-bold text-green-700">{{ number_format($payment->studentFeeAccount->total_paid, 0, ',', ' ') }} FCFA</p>
        </div>
        <div>
            <p class="text-gray-500">Reste à Payer</p>
            <p class="font-bold text-red-600">{{ number_format($payment->studentFeeAccount->balance, 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    <!-- Signatures -->
    <div class="flex justify-between items-end text-xs pt-4 border-t">
        <div class="text-center">
            <p class="font-bold">L'Étudiant(e) / Le Payeur</p>
            <div class="h-12"></div>
        </div>
        <div class="text-center">
            <p class="font-bold">Le Caissier / L'Agent Comptable</p>
            <p class="text-[10px] text-gray-400 italic">({{ $payment->receivedBy->name ?? 'Système' }})</p>
            <div class="h-10"></div>
        </div>
    </div>
</div>
@endsection