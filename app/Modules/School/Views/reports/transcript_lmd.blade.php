@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-white shadow-lg rounded-xl my-6 font-sans text-gray-800">
    
    <!-- En-tête du Relevé -->
    <div class="flex justify-between items-center border-b pb-6 mb-6">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-wider text-indigo-900">{{ $enrollment->school->name }}</h1>
            <p class="text-xs text-gray-500">{{ $enrollment->school->address }} - {{ $enrollment->school->city }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-700">RELEVÉ DE NOTES</h2>
            <p class="text-xs font-semibold text-indigo-600">Année Académique : {{ $enrollment->academicYear->name }}</p>
        </div>
    </div>

    <!-- Informations de l'Étudiant -->
    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg text-sm mb-6 border border-gray-200">
        <div>
            <p><span class="font-bold text-gray-600">Matricule :</span> {{ $enrollment->student->registration_number }}</p>
            <p><span class="font-bold text-gray-600">Nom & Prénoms :</span> {{ strtoupper($enrollment->student->personne->nom) }} {{ $enrollment->student->personne->prenoms }}</p>
            <p><span class="font-bold text-gray-600">Né(e) le :</span> {{ $enrollment->student->birth_date?->format('d/m/Y') }} à {{ $enrollment->student->birth_place }}</p>
        </div>
        <div>
            <p><span class="font-bold text-gray-600">Niveau :</span> {{ $enrollment->schoolClass->level->name }}</p>
            <p><span class="font-bold text-gray-600">Classe :</span> {{ $enrollment->schoolClass->name }}</p>
            <p><span class="font-bold text-gray-600">Semestre :</span> {{ $term->name }}</p>
        </div>
    </div>

    <!-- Tableau LMD : UE & ECUE -->
    <div class="overflow-hidden border border-gray-300 rounded-lg mb-6">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="bg-indigo-900 text-white font-semibold uppercase">
                    <th class="p-2 border">Unités d'Enseignement (UE) / ECUE</th>
                    <th class="p-2 border text-center">Coef.</th>
                    <th class="p-2 border text-center">Moy. /20</th>
                    <th class="p-2 border text-center">Crédits</th>
                    <th class="p-2 border text-center">Décision / Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unitsData as $data)
                    <!-- Ligne En-tête UE -->
                    <tr class="bg-gray-100 font-bold border-t border-b border-gray-300">
                        <td class="p-2 text-indigo-900">
                            {{ $data['unit']->code }} : {{ $data['unit']->name }}
                        </td>
                        <td class="p-2 text-center">{{ $data['unit']->coefficient }}</td>
                        <td class="p-2 text-center text-sm {{ $data['average'] >= 10 ? 'text-green-700' : 'text-red-600' }}">
                            {{ number_format($data['average'], 2) ?? 'N/A' }}
                        </td>
                        <td class="p-2 text-center">{{ $data['credits_earned'] }} / {{ $data['unit']->credits }}</td>
                        <td class="p-2 text-center">
                            @if($data['is_validated'])
                                <span class="px-2 py-0.5 text-[10px] bg-green-100 text-green-800 font-bold rounded">UE Validée</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-800 font-bold rounded">UE Non Validée</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Détail des ECUE / Matières -->
                    @foreach($data['subjects'] as $sub)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2 pl-6 text-gray-700">├─ {{ $sub['subject']->name }}</td>
                            <td class="p-2 text-center text-gray-500">{{ $sub['subject']->coefficient }}</td>
                            <td class="p-2 text-center font-medium">{{ number_format($sub['average'], 2) ?? '-' }}</td>
                            <td class="p-2 text-center text-gray-400">-</td>
                            <td class="p-2 text-center text-gray-400">-</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Synthèse et Délibération -->
    <div class="grid grid-cols-2 gap-6 items-center pt-4 border-t">
        <div class="space-y-1 text-xs">
            <p><span class="font-bold">Moyenne Générale du Semestre :</span> <span class="text-base font-bold text-indigo-900">{{ number_format($semesterAverage, 2) }} / 20</span></p>
            <p><span class="font-bold">Total Crédits Capitalisés :</span> <span class="font-bold text-green-700">{{ $totalCreditsEarned }} / {{ $totalSemesterCredits }} ECTS</span></p>
            <p><span class="font-bold">Mention :</span> {{ $mention }}</p>
        </div>

        <div class="text-right text-xs space-y-12">
            <p>Fait à {{ $enrollment->school->city }}, le {{ date('d/m/Y') }}</p>
            <p class="font-bold underline">Le President du Jury / Le Directeur</p>
        </div>
    </div>
</div>
@endsection