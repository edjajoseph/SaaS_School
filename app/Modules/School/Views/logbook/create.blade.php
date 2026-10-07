@extends('School::layouts.app3', [
    'namePage' => 'Saisie du Cahier de Textes',
    'class' => 'sidebar-mini',
    'activePage' => 'school.logbook',
    'activeModule' => 'pedagogie',
])

@section('content')
<div class="page-body">
    <div class="container-fluid">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark font-weight-bold">
                    <i class="fa fa-pencil-square-o text-primary mr-2"></i>SAISIE D'UNE SÉANCE DE COURS
                </h5>
                <a href="{{ route('school.logbook.index') }}" class="btn btn-secondary btn-sm font-weight-bold">
                    <i class="fa fa-arrow-left mr-1"></i> Retour au cahier de textes
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('school.logbook.store') }}" method="POST">
                    @csrf

                    @if($schedule)
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    @endif

                    <!-- Sélection de l'Établissement / École -->
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fa fa-university text-primary mr-1"></i> Établissement / École <span class="text-danger">*</span>
                            </label>
                            <select name="school_id" id="school_select" class="form-control @error('school_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner l'école --</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" 
                                        {{ (old('school_id', $user->school_id ?? $schedule?->school_id) == $school->id) ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('directeur_etudes') || !auth()->user()->staff)
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-dark">Enseignant / Intervenant <span class="text-danger">*</span></label>
                                <select name="staff_id" class="form-control @error('staff_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner l'enseignant --</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->personne->nom ?? '' }} {{ $staff->personne->prenoms ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <!-- Classe -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Classe <span class="text-danger">*</span></label>
                            <select name="school_class_id" class="form-control @error('school_class_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner la classe --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ (old('school_class_id', $schedule?->school_class_id) == $class->id) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Matière / UE -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Matière / UE <span class="text-danger">*</span></label>
                            <select name="school_subject_id" class="form-control @error('school_subject_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner la matière --</option>
                                @foreach($subjects as $schoolSubject)
                                    <option value="{{ $schoolSubject->id }}" 
                                        {{ (old('school_subject_id', $schedule?->subject_id ?? $schedule?->school_subject_id) == $schoolSubject->id) ? 'selected' : '' }}>
                                        {{ $schoolSubject->subject?->name ?? 'Matière sans nom' }}
                                        @if($schoolSubject->subject?->code)
                                            ({{ $schoolSubject->subject->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('school_subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de séance -->
                        <div class="col-md-3 form-group">
                            <label class="font-weight-bold text-dark">Type de séance <span class="text-danger">*</span></label>
                            <select name="session_type" class="form-control @error('session_type') is-invalid @enderror" required>
                                <option value="CM" {{ old('session_type') == 'CM' ? 'selected' : '' }}>Cours Magistral (CM)</option>
                                <option value="TD" {{ old('session_type') == 'TD' ? 'selected' : '' }}>Travaux Dirigés (TD)</option>
                                <option value="TP" {{ old('session_type') == 'TP' ? 'selected' : '' }}>Travaux Pratiques (TP)</option>
                                <option value="EXAMEN" {{ old('session_type') == 'EXAMEN' ? 'selected' : '' }}>Évaluation / Examen</option>
                            </select>
                            @error('session_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date de la séance -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Heure de début -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Heure de début <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedule?->start_time ?? '08:00') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Heure de fin -->
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Heure de fin <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedule?->end_time ?? '10:00') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Titre du Chapitre -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Titre du chapitre / Titre de la séance</label>
                        <input type="text" name="chapter_title" class="form-control @error('chapter_title') is-invalid @enderror" value="{{ old('chapter_title') }}" placeholder="Ex: Chapitre 2 : Les Structures de Contrôle">
                        @error('chapter_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Objectifs pédagogiques -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Objectifs pédagogiques de la séance</label>
                        <textarea name="objectives" class="form-control @error('objectives') is-invalid @enderror" rows="2" placeholder="Ex: Comprendre et manipuler les boucles for, while et do-while.">{{ old('objectives') }}</textarea>
                        @error('objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Résumé du cours / Thème abordé -->
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Résumé détaillé de la séance <span class="text-danger">*</span></label>
                        <textarea name="topic_covered" class="form-control @error('topic_covered') is-invalid @enderror" rows="5" required placeholder="Saisissez ici le contenu détaillé abordé durant le cours...">{{ old('topic_covered') }}</textarea>
                        @error('topic_covered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <!-- Devoirs / Travail à faire -->
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-bold text-dark">Devoirs / Activités à faire pour la prochaine séance</label>
                            <textarea name="homework" class="form-control @error('homework') is-invalid @enderror" rows="2" placeholder="Ex: Faire les exercices 1, 2 et 3 de la fiche TD n°2.">{{ old('homework') }}</textarea>
                            @error('homework')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold text-dark">Date limite de rendu</label>
                            <input type="date" name="homework_due_date" class="form-control @error('homework_due_date') is-invalid @enderror" value="{{ old('homework_due_date') }}">
                            @error('homework_due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="text-right mt-4">
                        <a href="{{ route('school.logbook.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-save mr-1"></i> Enregistrer la séance
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
