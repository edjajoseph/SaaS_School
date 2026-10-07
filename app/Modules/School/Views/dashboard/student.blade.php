@extends('School::layouts.app2', [
    'namePage' => 'Espace Étudiant',
    'activePage' => 'home',
    'activeModule' => 'dashboard',
])

@section('content')
    <div class="page-body">
        <div class="row">

            <!-- 1. Cartes KPIs Étudiant -->
            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Scolarité Totale</h6>
                        <h2 class="text-right"><i class="ti-wallet f-left"></i><span>{{ number_format($total_fee, 0, ',', ' ') }} FCFA</span></h2>
                        <p class="m-b-0">Statut: <span class="badge badge-light text-dark font-weight-bold">{{ strtoupper($registration_status) }}</span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Payé</h6>
                        <h2 class="text-right"><i class="ti-check-box f-left"></i><span>{{ number_format($total_paid, 0, ',', ' ') }} FCFA</span></h2>
                        <p class="m-b-0">Montant versé<span class="f-right"><i class="ti-money"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card {{ $balance_due > 0 ? 'bg-c-pink' : 'bg-c-green' }} order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Reste à Payer</h6>
                        <h2 class="text-right"><i class="ti-alert f-left"></i><span>{{ number_format($balance_due, 0, ',', ' ') }} FCFA</span></h2>
                        <p class="m-b-0">Solde restant<span class="f-right"><i class="ti-receipt"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Taux d'Assiduité</h6>
                        <h2 class="text-right"><i class="ti-user f-left"></i><span>{{ $attendance_rate }}%</span></h2>
                        <p class="m-b-0">{{ $absences_count }} absence(s) | {{ $late_count }} retard(s)</p>
                    </div>
                </div>
            </div>

            <!-- 2. Emploi du Temps Hebdomadaire -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="ti-calendar mr-2"></i>Mon Emploi du Temps Hebdomadaire
                        </h5>
                        <span class="badge badge-primary px-3 py-2">
                            Classe : {{ $registration->schoolClass->name ?? 'Non assignée' }}
                        </span>
                    </div>
                    <div class="card-block">
                        @php
                            $daysMap = [
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                            ];
                        @endphp

                        <div class="row">
                            @foreach($daysMap as $dayNumber => $dayName)
                                <div class="col-md-4 col-lg-2 mb-3">
                                    <div class="card border {{ $current_day == $dayNumber ? 'border-primary shadow-sm' : 'border-light' }} h-100 mb-0">
                                        <div class="card-header text-center {{ $current_day == $dayNumber ? 'bg-primary text-white' : 'bg-light text-dark' }} py-2 font-weight-bold">
                                            {{ $dayName }}
                                            @if($current_day == $dayNumber)
                                                <span class="d-block badge badge-light text-primary mt-1">Aujourd'hui</span>
                                            @endif
                                        </div>
                                        <div class="card-body p-2">
                                            @php
                                                $dayCourses = $weekly_schedule->get($dayNumber, collect());
                                            @endphp

                                            @forelse($dayCourses as $course)
                                                <div class="p-2 mb-2 rounded border-left border-3 {{ $course->is_completed ? 'bg-light border-secondary' : 'bg-light-primary border-primary' }}">
                                                    <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                                                        {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}
                                                    </div>
                                                    <div class="text-primary font-weight-bold mt-1" style="font-size: 0.9rem;">
                                                        {{ $course->subject_name }}
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 0.75rem;">
                                                        <span class="badge badge-info">{{ $course->session_type }}</span>
                                                        <span class="text-muted"><i class="ti-location-pin"></i> {{ $course->room_name ?? 'N/A' }}</span>
                                                    </div>
                                                    @if($course->teacher_name)
                                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                            <i class="ti-user"></i> {{ $course->teacher_name }}
                                                        </div>
                                                    @endif
                                                    @if($course->is_completed)
                                                        <span class="badge badge-secondary d-block mt-1">Terminé</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="text-center text-muted my-3" style="font-size: 0.8rem;">
                                                    <em>Aucun cours</em>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection