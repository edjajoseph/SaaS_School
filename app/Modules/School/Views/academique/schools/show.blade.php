<div class="page-body">
    <div class="row">
        <!-- Informations Générales -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white">
                    <h5 class="text-primary font-weight-bold mb-0"><i class="fa fa-info-circle mr-2"></i>FICHE ÉTABLISSEMENT</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($school->logo_path)
                            <img src="{{ asset('storage/' . $school->logo_path) }}" alt="Logo" class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                        @else
                            <i class="fa fa-building fa-4x text-secondary"></i>
                        @endif
                        <h4 class="mt-2 font-weight-bold">{{ $school->name }}</h4>
                        <span class="badge badge-primary px-2 py-1">{{ $school->code }}</span>
                    </div>
                    <hr>
                    <p><strong>Statut :</strong> <span class="badge badge-info">{{ ucfirst($school->status) }}</span></p>
                    <p><strong>Année Courante :</strong> {{ $school->currentAcademicYear->name ?? 'Non définie' }}</p>
                    <p><strong>N° Décision :</strong> {{ $school->official_approval_number ?: 'Aucun' }}</p>
                    <p><strong>Email :</strong> {{ $school->email ?: 'N/A' }}</p>
                    <p><strong>Téléphone 1 :</strong> {{ $school->phone_1 ?: 'N/A' }}</p>
                    <p><strong>Ville / Commune :</strong> {{ $school->city }} {{ $school->municipality ? '('.$school->municipality.')' : '' }}</p>
                    <p><strong>Adresse :</strong> {{ $school->address ?: 'Non renseignée' }}</p>
                    <hr>
                    <a href="{{ route('organisation.schools.index') }}" class="btn btn-warning btn-sm btn-round waves-effect">
                        <i class="fa fa-undo mr-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Configurations Rattachées (Cycles, Séries, Niveaux) -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white">
                    <h5 class="text-primary font-weight-bold mb-0"><i class="fa fa-cogs mr-2"></i>CONFIGURATIONS RATTACHÉES</h5>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold text-dark"><i class="fa fa-layers text-primary mr-1"></i> Cycles configurés</h6>
                    <div class="mb-3">
                        @forelse($school->cycles as $cycle)
                            <span class="badge badge-primary px-2 py-1 mr-1 mb-1">{{ $cycle->name }}</span>
                        @empty
                            <span class="text-muted small">Aucun cycle associatif.</span>
                        @endforelse
                    </div>

                    <h6 class="font-weight-bold text-dark"><i class="fa fa-list text-success mr-1"></i> Séries / Filières configurées</h6>
                    <div class="mb-3">
                        @forelse($school->series as $serie)
                            <span class="badge badge-success px-2 py-1 mr-1 mb-1">{{ $serie->name }}</span>
                        @empty
                            <span class="text-muted small">Aucune série associative.</span>
                        @endforelse
                    </div>

                    <h6 class="font-weight-bold text-dark"><i class="fa fa-graduation-cap text-info mr-1"></i> Niveaux d'études configurés</h6>
                    <div>
                        @forelse($school->levels as $level)
                            <span class="badge badge-info px-2 py-1 mr-1 mb-1">{{ $level->name }}</span>
                        @empty
                            <span class="text-muted small">Aucun niveau associatif.</span>
                        @endforelse
                    </div>
                    <a href="{{ route('organisation.schools.index') }}" class="btn btn-secondary btn-round waves-effect" >
                        <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>