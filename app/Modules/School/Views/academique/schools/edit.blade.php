<form method="POST" action="{{ route('organisation.schools.update', $school) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            <!-- Code -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-barcode text-success mr-1"></i> {{ __("Code") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="code" class="form-control form-control-alternative @error('code') is-invalid @enderror" value="{{ old('code', $school->code) }}" required>
                    @error('code')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Nom -->
            <div class="col-md-5">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-building text-success mr-1"></i> {{ __("Nom de l'établissement") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-alternative @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}" required>
                    @error('name')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Statut (Type) -->
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-flag text-success mr-1"></i> {{ __("Statut") }} <span class="text-danger">*</span>
                    </label>
                    <select name="status" class="form-control form-control-alternative @error('status') is-invalid @enderror" required>
                        <option value="private" {{ old('status', $school->status) == 'private' ? 'selected' : '' }}>Privé</option>
                        <option value="public" {{ old('status', $school->status) == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="confessional" {{ old('status', $school->status) == 'confessional' ? 'selected' : '' }}>Confessionnel</option>
                    </select>
                    @error('status')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
            </div>

            <!-- Année académique par défaut -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar text-success mr-1"></i> {{ __("Année académique courante") }}
                    </label>
                    <select name="current_academic_year_id" class="form-control form-control-alternative">
                        <option value="">-- Sélectionner une année --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('current_academic_year_id', $school->current_academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Agrément officiel -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-certificate text-success mr-1"></i> {{ __("N° Décision d'ouverture / Agrément") }}
                    </label>
                    <input type="text" name="official_approval_number" class="form-control form-control-alternative" value="{{ old('official_approval_number', $school->official_approval_number) }}">
                </div>
            </div>

            <!-- Email & Téléphones -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-envelope text-success mr-1"></i> {{ __("Email") }}</label>
                    <input type="email" name="email" class="form-control form-control-alternative" value="{{ old('email', $school->email) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-phone text-success mr-1"></i> {{ __("Téléphone 1") }}</label>
                    <input type="text" name="phone_1" class="form-control form-control-alternative" value="{{ old('phone_1', $school->phone_1) }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-phone text-success mr-1"></i> {{ __("Téléphone 2") }}</label>
                    <input type="text" name="phone_2" class="form-control form-control-alternative" value="{{ old('phone_2', $school->phone_2) }}">
                </div>
            </div>

            <!-- Ville & Site Web -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-map-marker text-success mr-1"></i> {{ __("Ville") }}</label>
                    <input type="text" name="city" class="form-control form-control-alternative" value="{{ old('city', $school->city) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-globe text-success mr-1"></i> {{ __("Site Web") }}</label>
                    <input type="url" name="website" class="form-control form-control-alternative" value="{{ old('website', $school->website) }}">
                </div>
            </div>

            <!-- Configurations Pivots -->
            <div class="col-md-12"><hr></div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-layers text-success mr-1"></i> {{ __("Cycles Proposés") }}</label>
                    <select name="cycles[]" class="form-control select2" multiple>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}" {{ $school->cycles->contains($cycle->id) ? 'selected' : '' }}>
                                {{ $cycle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-list text-success mr-1"></i> {{ __("Séries / Filières") }}</label>
                    <select name="series[]" class="form-control select2" multiple>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" {{ $school->series->contains($serie->id) ? 'selected' : '' }}>
                                {{ $serie->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark"><i class="fa fa-graduation-cap text-success mr-1"></i> {{ __("Niveaux") }}</label>
                    <select name="levels[]" class="form-control select2" multiple>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ $school->levels->contains($level->id) ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('organisation.schools.index') }}" class="btn btn-secondary btn-round waves-effect" >
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>