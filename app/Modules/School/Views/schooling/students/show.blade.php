<div class="page-body">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('schooling.students.pdf', $student->id) }}" target="_blank" class="btn btn-sm btn-primary shadow-sm">
            <i class="fa fa-print mr-1"></i> {{ __('Imprimer / Télécharger PDF') }}
        </a>
    </div>

    <div class="row">
        <!-- ÉTAT CIVIL -->
        <div class="col-md-4 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-id-card mr-1"></i> {{ __('ÉTAT CIVIL') }}</h6>
                </div>
                <div class="card-body text-center pb-2">
                    <div class="profile-photo-container mb-3">
                        @if(optional($student->personne)->photo)
                            <img src="{{ route('schooling.file.display', ['path' => ltrim($student->personne->photo, '/')]) }}" class="img-fluid rounded-circle shadow-sm border" style="width: 90px; height: 90px; object-fit: cover;" alt="Photo">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border" style="width: 90px; height: 90px;">
                                <i class="fa fa-user fa-3x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold mb-1 text-break">
                        {{ optional($student->personne)->nom_complet ?? (optional($student->personne)->nom . ' ' . optional($student->personne)->prenoms) }}
                    </h5>
                    <p class="text-muted mb-2">
                        <span class="badge badge-primary px-2 py-1">{{ $student->registration_number ?? $student->student_code }}</span>
                    </p>
                </div>
                <div class="card-body pt-0 small">
                    <hr class="mt-0">
                    <p class="mb-1"><strong>{{ __('Sexe') }} :</strong> {{ optional($student->personne)->sexe == 'M' ? __('Masculin') : (optional($student->personne)->sexe == 'F' ? __('Féminin') : 'N/A') }}</p>
                    <p class="mb-1"><strong>{{ __('Né(e) le') }} :</strong> 
                        {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : (optional($student->personne)->birth_date ? \Carbon\Carbon::parse($student->personne->birth_date)->format('d/m/Y') : 'N/A') }} 
                        {{ __('à') }} {{ $student->birth_place ?? optional($student->personne)->birth_place ?? 'N/A' }}
                    </p>
                    <p class="mb-1"><strong>{{ __('Nationalité') }} :</strong> {{ $student->nationality ?? optional(optional($student->personne)->country)->nationality ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('Téléphone') }} :</strong> {{ optional($student->personne)->telephone ?: 'N/A' }}</p>
                    <p class="mb-1 text-break"><strong>{{ __('Email') }} :</strong> {{ optional($student->personne)->email ?: 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- CURSUS & TUTEUR -->
        <div class="col-md-8 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-graduation-cap mr-1"></i> {{ __('CURSUS ACADÉMIQUE & TUTEUR') }}</h6>
                </div>
                <div class="card-body p-3 small">
                    <h6 class="text-dark font-weight-bold mb-2"><i class="fa fa-university mr-1"></i> {{ __('Inscription Courante') }}</h6>
                    <p class="mb-1"><strong>{{ __('Établissement') }} :</strong> {{ optional($student->school)->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('Niveau d\'étude') }} :</strong> {{ optional($student->level)->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>{{ __('Classe') }} :</strong> {{ optional($student->classroom)->name ?? 'N/A' }}</p>

                    <hr>
                    <h6 class="text-dark font-weight-bold mb-2"><i class="fa fa-users mr-1"></i> {{ __('Responsables / Tuteurs') }}</h6>
                    <p class="mb-1"><strong>{{ __('Nom du tuteur') }} :</strong> {{ $student->guardian_name ?? 'N/A' }} {{ $student->guardian_relation ? '('.$student->guardian_relation.')' : '' }}</p>
                    <p class="mb-0"><strong>{{ __('Contact Tuteur') }} :</strong> {{ $student->guardian_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>