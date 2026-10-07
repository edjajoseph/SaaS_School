@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    
    <!-- En-tête et Filtre par Classe -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Emploi du Temps</h1>
            <p class="text-sm text-gray-500">Planification des cours et gestion des salles</p>
        </div>

        <form method="GET" action="{{ route('school.schedules.index') }}" class="flex items-center gap-3">
            <label for="school_class_id" class="text-sm font-medium text-gray-700">Classe :</label>
            <select name="school_class_id" id="school_class_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">-- Sélectionner une classe --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </form>

        @if($selectedClassId)
            <button onclick="openModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow transition">
                + Ajouter une séance
            </button>
        @endif
    </div>

    <!-- Messages d'erreur de conflit -->
    @if($errors->has('schedule_conflict'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Conflits détectés dans l'emploi du temps :</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->get('schedule_conflict')[0] as $conflict)
                            <li>{{ $conflict }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Grille de l'emploi du temps -->
    @if($selectedClassId)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
            <table class="w-full border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="p-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Horaire</th>
                        @foreach($days as $dayKey => $dayLabel)
                            <th class="p-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ $dayLabel }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($timeSlots as $slot)
                        @php $slotFormatted = \Carbon\Carbon::createFromFormat('H:i', $slot)->format('H:i:s'); @endphp
                        <tr>
                            <!-- Colonne des Heures -->
                            <td class="p-3 text-center text-xs font-bold text-gray-500 bg-gray-50 border-r border-gray-200">
                                {{ $slot }}
                            </td>

                            <!-- Colonnes des Jours -->
                            @foreach($days as $dayKey => $dayLabel)
                                @php
                                    // Recherche si un cours existe sur ce jour et ce créneau horaire
                                    $session = $schedules->first(function($item) use ($dayKey, $slotFormatted) {
                                        return $item->day_of_week === $dayKey 
                                            && $item->start_time <= $slotFormatted 
                                            && $item->end_time > $slotFormatted;
                                    });
                                @endphp

                                <td class="p-2 border-r border-gray-200 align-top h-24 relative hover:bg-gray-50 transition">
                                    @if($session)
                                        <div class="h-full p-2 rounded-lg text-white text-xs shadow-xs flex flex-col justify-between" 
                                             style="background-color: {{ $session->subject->color_code ?? '#3B82F6' }};">
                                            
                                            <div>
                                                <div class="font-bold text-sm leading-tight mb-1">
                                                    {{ $session->subject->name }}
                                                </div>
                                                <div class="opacity-90 flex items-center gap-1">
                                                    📍 Salle : {{ $session->room->name ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <div class="mt-2 pt-1 border-t border-white/20 flex justify-between items-center text-[11px] opacity-90">
                                                <span>👤 {{ $session->teacher ? $session->teacher->personne->nom : 'Non assigné' }}</span>
                                                <span class="font-semibold">{{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white p-12 text-center rounded-xl border border-gray-200 shadow-sm">
            <div class="text-gray-400 text-5xl mb-3">📅</div>
            <h3 class="text-lg font-medium text-gray-800">Aucune classe sélectionnée</h3>
            <p class="text-gray-500 text-sm mt-1">Veuillez choisir une classe dans le menu déroulant ci-dessus pour afficher son emploi du temps.</p>
        </div>
    @endif
</div>

<!-- Modal d'Ajout d'une Séance -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-xs z-50 flex justify-center items-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Planifier un cours</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
        </div>

        <form action="{{ route('school.schedules.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="school_id" value="{{ session('active_school_id', 1) }}">
            <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">

            <!-- Matière -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Matière</label>
                <select name="subject_id" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Enseignant & Salle -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Enseignant</label>
                    <select name="staff_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                        <option value="">Non assigné</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->personne->nom }} {{ $teacher->personne->prenoms }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Salle</label>
                    <select name="room_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                        <option value="">Non attribuée</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->capacity }} pl.)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jour de la semaine -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jour</label>
                <select name="day_of_week" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                    @foreach($days as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Horaires -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Heure Début</label>
                    <input type="time" name="start_time" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Heure Fin</label>
                    <input type="time" name="end_time" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500">
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('scheduleModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }
</script>
@endsection