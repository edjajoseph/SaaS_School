<form method="POST" action="{{ route('organisation.periods.update', $period) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">
            {{-- Sélection de l'Établissement (Affiché uniquement si Admin) --}}
            @if(isset($isAdmin) && $isAdmin)
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">
                            <i class="fa fa-university text-success mr-1"></i> {{ __("Établissement") }} <span class="text-danger">*</span>
                        </label>
                        <select name="school_id" class="form-control form-control-alternative @error('school_id') is-invalid @enderror" required>
                            <option value="">{{ __("Sélectionnez l'établissement") }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $period->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->code ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            @else
                {{-- Champ masqué pour préserver l'établissement existant si non-admin --}}
                <input type="hidden" name="school_id" value="{{ $period->school_id }}">
            @endif

            <!-- Année Académique -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-graduation-cap text-success mr-1"></i> {{ __("Année académique") }} <span class="text-danger">*</span>
                    </label>
                    <select name="academic_year_id" class="form-control form-control-alternative @error('academic_year_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez l'année") }}</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $period->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name ?? $year->code }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Élément de type de période -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-list text-success mr-1"></i> {{ __("Type de période") }} <span class="text-danger">*</span>
                    </label>
                    <select name="period_type_item_id" class="form-control form-control-alternative @error('period_type_item_id') is-invalid @enderror" required>
                        <option value="">{{ __("Sélectionnez le terme / période") }}</option>
                        @foreach($periodTypeItems as $item)
                            <option value="{{ $item->id }}" {{ old('period_type_item_id', $period->period_type_item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->periodType->name ?? 'Général' }} - {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('period_type_item_id')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Date de début -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar-plus-o text-success mr-1"></i> {{ __("Date de début") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="start_date" 
                           class="form-control form-control-alternative @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', $period->start_date ? \Carbon\Carbon::parse($period->start_date)->format('Y-m-d') : '') }}" 
                           required>
                    @error('start_date')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Date de fin -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar-minus-o text-success mr-1"></i> {{ __("Date de fin") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="end_date" 
                           class="form-control form-control-alternative @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date', $period->end_date ? \Carbon\Carbon::parse($period->end_date)->format('Y-m-d') : '') }}" 
                           required>
                    @error('end_date')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <!-- Options de statut -->
            <div class="col-md-6 mt-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           name="is_current" 
                           value="1" 
                           class="custom-control-input" 
                           id="is_current_edit_{{ $period->id }}" 
                           {{ old('is_current', $period->is_current) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="is_current_edit_{{ $period->id }}">
                        <i class="fa fa-star text-warning mr-1"></i> {{ __("Définir comme période courante") }}
                    </label>
                </div>
            </div>

            <div class="col-md-6 mt-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           name="is_closed" 
                           value="1" 
                           class="custom-control-input" 
                           id="is_closed_edit_{{ $period->id }}" 
                           {{ old('is_closed', $period->is_closed) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="is_closed_edit_{{ $period->id }}">
                        <i class="fa fa-lock text-danger mr-1"></i> {{ __("Marquer comme clôturée") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('organisation.periods.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm">
            <i class="fa fa-refresh mr-1"></i> {{ __('Mettre à jour') }}
        </button>
    </div>
</form>