<style>
/* 1. Reset du fond, bordure et conteneur principal Select2 */
.select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    height: calc(2.25rem + 2px) !important;
    display: flex !important;
    align-items: center !important;
    position: relative !important;
    box-shadow: none !important;
}

/* 2. Style du texte sélectionné */
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057 !important;
    background-color: transparent !important;
    line-height: normal !important;
    padding-left: 0.75rem !important;
    padding-right: 2rem !important;
    width: 100% !important;
}

/* 3. Reformatage et affichage de la flèche descendante */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    width: 30px !important;
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: transparent !important;
}

/* Force le dessin du chevron natif de Select2 */
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6c757d transparent transparent transparent !important;
    border-style: solid !important;
    border-width: 6px 5px 0 5px !important;
    height: 0 !important;
    width: 0 !important;
    margin: 0 !important;
    position: static !important;
}

/* Animation de la flèche lors du déroulement du menu */
.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #6c757d transparent !important;
    border-width: 0 5px 6px 5px !important;
}

/* 4. Masquer le select natif et forcer la largeur */
select.select2,
select.select2-hidden-accessible {
    display: none !important;
    visibility: hidden !important;
}

.select2-container {
    width: 100% !important;
    display: block !important;
}

/* 5. Z-Index pour Modale */
.select2-container--open,
.select2-dropdown {
    z-index: 9999999 !important;
}
</style>

<form method="POST" action="{{ route('evaluation.evaluations.update', $evaluation) }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="row">            
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Établissement <span class="text-danger">*</span></label>
                <select name="school_id" id="school_id_select" class="form-control select2 @error('school_id') is-invalid @enderror" required>
                    <option value="">Sélectionner un établissement</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $evaluation->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>           

            <!-- Intitulé de l'évaluation -->
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-pencil text-primary mr-1"></i> {{ __("Titre / Intitulé") }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control form-control-alternative @error('title') is-invalid @enderror" 
                           value="{{ old('title', $evaluation->title) }}" 
                           placeholder="Ex: Devoir Surveillé N°1" 
                           required>
                    @error('title')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Date d'évaluation -->
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calendar text-primary mr-1"></i> {{ __("Date d'évaluation") }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="evaluated_at" 
                           class="form-control form-control-alternative @error('evaluated_at') is-invalid @enderror" 
                           value="{{ old('evaluated_at', \Carbon\Carbon::parse($evaluation->evaluated_at)->format('Y-m-d')) }}" 
                           required>
                    @error('evaluated_at')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Classe (Select2 avec gestion enseignant/admin) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-users text-primary mr-1"></i> {{ __("Classe") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_class_id" id="school_class_id_select" class="form-control select2 @error('school_class_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('Sélectionner une classe') }} --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('school_class_id', $evaluation->school_class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_class_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Matière (Select2 rechargé dynamiquement selon la classe) -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-book text-primary mr-1"></i> {{ __("Matière") }} <span class="text-danger">*</span>
                    </label>
                    <select name="school_subject_id" id="school_subject_id_select" class="form-control select2 @error('school_subject_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('Sélectionner une matière') }} --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('school_subject_id', $evaluation->school_subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject->name ?? $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_subject_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Type d'évaluation -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-tag text-primary mr-1"></i> {{ __("Type d'évaluation") }} <span class="text-danger">*</span>
                    </label>
                    <select name="evaluation_type_id" class="form-control select2 @error('evaluation_type_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('Sélectionner un type') }} --</option>
                        @foreach($evaluationTypes as $type)
                            <option value="{{ $type->id }}" {{ old('evaluation_type_id', $evaluation->evaluation_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('evaluation_type_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Période académique -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-clock-o text-primary mr-1"></i> {{ __("Période académique") }} <span class="text-danger">*</span>
                    </label>
                    <select name="academic_period_id" class="form-control select2 @error('academic_period_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('Sélectionner la période') }} --</option>
                        @foreach($academicPeriods as $period)
                            <option value="{{ $period->id }}" {{ old('academic_period_id', $evaluation->academic_period_id) == $period->id ? 'selected' : '' }}>
                                {{ $period->periodTypeItem->name ?? $period->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_period_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Barème Max -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-calculator text-primary mr-1"></i> {{ __("Barème Max") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="max_score" 
                           class="form-control form-control-alternative @error('max_score') is-invalid @enderror" 
                           value="{{ old('max_score', $evaluation->max_score) }}" 
                           min="1" 
                           required>
                    @error('max_score')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Coefficient -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">
                        <i class="fa fa-percent text-primary mr-1"></i> {{ __("Coefficient") }} <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="coefficient" 
                           class="form-control form-control-alternative @error('coefficient') is-invalid @enderror" 
                           value="{{ old('coefficient', $evaluation->coefficient) }}" 
                           min="0.1" 
                           required>
                    @error('coefficient')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Option de publication -->
            <div class="col-md-12">
                <div class="form-check mt-2">
                    <label class="form-check-label text-dark font-weight-bold">
                        <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $evaluation->is_published) ? 'checked' : '' }}>
                        <span class="form-check-sign"></span>
                        <i class="fa fa-eye text-primary mr-1"></i> {{ __("Publier les notes de cette évaluation") }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Pied de page de la modale -->
    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <a href="{{ route('evaluation.evaluations.index') }}" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>
        <div>
            <button type="submit" class="btn btn-info btn-round waves-effect shadow-sm">
                <i class="fa fa-save mr-1"></i> {{ __('Mettre à jour') }}
            </button>
        </div>
    </div>
</form>

<script>
    (function($) {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('.modal.show').length ? $('.modal.show') : $(document.body),
                width: '100%'
            });
        }

        // Écouteur AJAX pour mettre à jour la liste des matières en fonction de la classe choisie
        $('#school_class_id_select').on('change', function() {
            var classId = $(this).val();
            var $subjectSelect = $('#school_subject_id_select');
            
            if (classId) {
                $.ajax({
                    url: "{{ route('evaluation.ajax.evaluations.subjects') }}",
                    type: 'GET',
                    data: { school_class_id: classId },
                    success: function(data) {
                        $subjectSelect.empty().append('<option value="">-- {{ __("Sélectionner une matière") }} --</option>');
                        $.each(data, function(key, value) {
                            $subjectSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $subjectSelect.trigger('change');
                    }
                });
            }
        });

        // Gestion de la fermeture propre de la modale
        $(document).off('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]')
                   .on('click.closeModal', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function(e) {
            e.preventDefault();
            var $modal = $(this).closest('.modal');
            if ($modal.length) {
                $modal.modal('hide');
            } else {
                $('.modal.show, .modal.in').modal('hide');
            }
        });
    })(jQuery);
</script>