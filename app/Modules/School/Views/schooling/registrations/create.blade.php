<style>
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    background: transparent !important;
    color: #495057 !important;
}

.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    box-shadow: none !important;
}

.select2-container { width: 100% !important; display: block !important; }
.select2-dropdown { z-index: 1060 !important; border-color: #cccccc !important; }

.wizard-steps { display: flex; justify-content: space-between; position: relative; margin-bottom: 25px; }
.wizard-steps::before { content: ""; position: absolute; top: 18px; left: 0; width: 100%; height: 3px; background: #e9ecef; z-index: 1; }
.wizard-progress-bar { position: absolute; top: 18px; left: 0; height: 3px; background: #4099ff; z-index: 1; transition: width 0.3s ease; }
.step-item { position: relative; z-index: 2; text-align: center; background: #fff; padding: 0 10px; }
.step-circle { width: 38px; height: 38px; border-radius: 50%; background: #e9ecef; color: #6c757d; font-weight: bold; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px auto; border: 2px solid #fff; transition: all 0.3s ease; }
.step-item.active .step-circle { background: #4099ff; color: #fff; box-shadow: 0 0 10px rgba(64, 153, 255, 0.5); }
.step-item.completed .step-circle { background: #2ed8b6; color: #fff; }
.step-label { font-size: 12px; font-weight: 600; color: #495057; }
.step-content { display: none; }
.step-content.active { display: block; }

hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }

.file-upload-wrapper {
    position: relative;
    overflow: hidden;
    width: 100%;
}
.file-upload-wrapper input[type="file"] {
    font-size: 11px;
    width: 100%;
}
.doc-row-item {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px 10px;
}

#mediumModal .modal-body, 
#mediumModal1 .modal-body, 
#mediumModal2 .modal-body,
.modal-dialog-scrollable .modal-body {
    max-height: calc(100vh - 210px) !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
}

.select2-container--open, .select2-dropdown {
    z-index: 99999 !important;
}

.modal {
    overflow-y: hidden !important;
}

.modal-dialog {
    margin-top: 1.75rem;
    margin-bottom: 1.75rem;
    height: calc(100% - 3.5rem);
}

.modal-content {
    max-height: 100%;
}
</style>

<form id="registration-create-wizard-form" method="POST" action="{{ route('schooling.registrations.store') }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="modal-body p-4">
        <div class="wizard-steps">
            <div class="wizard-progress-bar" id="wizard-progress-reg-create" style="width: 0%;"></div>
            <div class="step-item active" id="step-tab-reg-create-1">
                <div class="step-circle">1</div>
                <div class="step-label">{{ __('Cadre Académique') }}</div>
            </div>
            <div class="step-item" id="step-tab-reg-create-2">
                <div class="step-circle">2</div>
                <div class="step-label">{{ __('Identité / Étudiant') }}</div>
            </div>
            <div class="step-item" id="step-tab-reg-create-3">
                <div class="step-circle">3</div>
                <div class="step-label">{{ __('Frais & Documents') }}</div>
            </div>
        </div>

        <hr class="hr-gradient mb-4">

        <!-- ÉTAPE 1 : CADRE ACADÉMIQUE -->
        <div class="step-content active" id="step-reg-create-1">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-university mr-1"></i> {{ __("Paramètres de l'Inscription") }}</h6>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Type d'opération") }} <span class="text-danger">*</span></label>
                        <select name="type" id="registration_type_select" class="form-control form-control-alternative select2-modal" required>
                            <option value="inscription" {{ old('type') == 'inscription' ? 'selected' : '' }}>{{ __("Nouvelle Inscription") }}</option>
                            <option value="reinscription" {{ old('type') == 'reinscription' ? 'selected' : '' }}>{{ __("Réinscription") }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Établissement") }} <span class="text-danger">*</span></label>
                        <select name="school_id" class="form-control form-control-alternative select2-modal" required>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Année Académique") }} <span class="text-danger">*</span></label>
                        <select name="academic_year_id" class="form-control form-control-alternative select2-modal" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Classe") }} <span class="text-danger">*</span></label>
                        <select name="school_class_id" class="form-control form-control-alternative select2-modal" required>
                            <option value=""></option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Date d'inscription") }} <span class="text-danger">*</span></label>
                        <input type="date" name="registration_date" class="form-control form-control-alternative" value="{{ old('registration_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Statut") }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-control form-control-alternative select2-modal" required>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>{{ __("Confirmée") }}</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __("En attente") }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 2 : IDENTITÉ OU SÉLECTION ÉTUDIANT -->
        <div class="step-content" id="step-reg-create-2">
            <div id="section_re_enrollment" style="display: none;">
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user-check mr-1"></i> {{ __("Sélectionner l'Étudiant Existant") }}</h6>
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Étudiant") }} <span class="text-danger">*</span></label>
                    <select name="student_id" id="student_id_select" class="form-control form-control-alternative select2-modal" data-placeholder="{{ __("Rechercher un étudiant par matricule ou nom...") }}">
                        <option value=""></option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ optional($student->personne)->nom_complet ?? (optional($student->personne)->nom . ' ' . optional($student->personne)->prenoms) }} (Matricule: {{ $student->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="section_new_student">
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user-plus mr-1"></i> {{ __("Informations du Nouvel Étudiant") }}</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Matricule") }}</label>
                            <input type="text" name="matricule" class="form-control form-control-alternative" value="{{ old('matricule') }}" placeholder="{{ __("Auto-généré si vide") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom") }} <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control form-control-alternative" value="{{ old('nom') }}" placeholder="{{ __("Ex: KOUASSI") }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Prénoms") }} <span class="text-danger">*</span></label>
                            <input type="text" name="prenoms" class="form-control form-control-alternative" value="{{ old('prenoms') }}" placeholder="{{ __("Ex: Jean-Marc") }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Sexe") }} <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-control form-control-alternative select2-modal">
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>{{ __("M") }}</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>{{ __("F") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Date de naissance") }}</label>
                            <input type="date" name="birth_date" class="form-control form-control-alternative" value="{{ old('birth_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Lieu de naissance") }}</label>
                            <input type="text" name="birth_place" class="form-control form-control-alternative" value="{{ old('birth_place') }}" placeholder="{{ __("Ex: Abidjan") }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Pays d'origine") }}</label>
                            <select name="country_id" class="form-control form-control-alternative select2-modal">
                                <option value="">{{ __("Sélectionner un pays...") }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nationalité (Texte)") }}</label>
                            <select name="nationality" class="form-control form-control-alternative select2-modal" data-placeholder="{{ __("Sélectionner une nationalité") }}">
                                <option value=""></option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('nationality') == $country->id ? 'selected' : '' }}>
                                        {{ $country->nationality }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Téléphone") }}</label>
                            <input type="text" name="telephone" class="form-control form-control-alternative" value="{{ old('telephone') }}" placeholder="{{ __("Ex: +225 0700000000") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Email") }}</label>
                            <input type="email" name="email" class="form-control form-control-alternative" value="{{ old('email') }}" placeholder="{{ __("Ex: etudiant@domaine.com") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Photo d'identité") }}</label>
                            <input type="file" name="photo" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Adresse géographique / Domicile") }}</label>
                            <input type="text" name="address" class="form-control form-control-alternative" value="{{ old('address') }}" placeholder="{{ __("Quartier, Rue, Domicile...") }}">
                        </div>
                    </div>

                    <div class="col-md-12"><hr class="my-2"></div>
                    <div class="col-md-12"><h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-users mr-1"></i> {{ __("Parents & Tuteur") }}</h6></div>

                    <!-- Père -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom du Père") }}</label>
                            <input type="text" name="father_name" class="form-control form-control-alternative" value="{{ old('father_name') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Profession du Père") }}</label>
                            <input type="text" name="father_job" class="form-control form-control-alternative" value="{{ old('father_job') }}" placeholder="{{ __("Ex: Cadre") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Tél. Père") }}</label>
                            <input type="text" name="father_phone" class="form-control form-control-alternative" value="{{ old('father_phone') }}">
                        </div>
                    </div>

                    <!-- Mère -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom de la Mère") }}</label>
                            <input type="text" name="mother_name" class="form-control form-control-alternative" value="{{ old('mother_name') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Profession de la Mère") }}</label>
                            <input type="text" name="mother_job" class="form-control form-control-alternative" value="{{ old('mother_job') }}" placeholder="{{ __("Ex: Commerçante") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Tél. Mère") }}</label>
                            <input type="text" name="mother_phone" class="form-control form-control-alternative" value="{{ old('mother_phone') }}">
                        </div>
                    </div>

                    <!-- Tuteur -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom du Tuteur") }}</label>
                            <input type="text" name="guardian_name" class="form-control form-control-alternative" value="{{ old('guardian_name') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Lien de Parenté / Relation") }}</label>
                            <input type="text" name="guardian_relation" class="form-control form-control-alternative" value="{{ old('guardian_relation') }}" placeholder="{{ __("Ex: Oncle, Tante...") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Téléphone Tuteur") }}</label>
                            <input type="text" name="guardian_phone" class="form-control form-control-alternative" value="{{ old('guardian_phone') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 3 : FRAIS ET DOCUMENTS -->
        <div class="step-content" id="step-reg-create-3">
            <div class="row">
                <div class="col-md-5">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-money-bill-wave mr-1"></i> {{ __("Règlement & Frais") }}</h6>
                    
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Reçu de versement / Preuve (PDF, Image)") }}</label>
                        <input type="file" name="payment_receipt" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg, application/pdf">
                        <small class="text-muted">{{ __("Format: JPG, PNG ou PDF (max 4Mo)") }}</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Remarques / Notes") }}</label>
                        <textarea name="notes" class="form-control form-control-alternative" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- AFFICHAGE DES DOCUMENTS MIS À JOUR -->
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-folder-open mr-1"></i> {{ __("Documents & Pièces fournies") }}</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-round" id="add_doc_btn"><i class="fa fa-plus mr-1"></i> {{ __('Ajouter') }}</button>
                    </div>
                    <div id="documents_container">
                        <div class="doc-row-item mb-2">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <select name="documents[0][document_type_id]" class="form-control form-control-alternative form-control-sm doc-type-select">
                                        <option value="">{{ __("Type...") }}</option>
                                        @foreach($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" name="documents[0][title]" class="form-control form-control-alternative form-control-sm" placeholder="Ex: Copie certifiée">
                                </div>
                                <div class="col-4">
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="documents[0][file]" class="form-control-file">
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <input type="checkbox" name="documents[0][is_provided]" value="1" checked title="Fourni ?">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" id="btn-reg-create-prev" style="display: none;">
            <i class="fa fa-arrow-left mr-1"></i> {{ __('Précédent') }}
        </button>

        <a href="{{ route('schooling.registrations.index') }}" class="btn btn-secondary btn-round waves-effect close-modal-btn" id="btn-reg-create-close">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>

        <div>
            <button type="button" class="btn btn-primary btn-round waves-effect shadow-sm" id="btn-reg-create-next">
                {{ __('Suivant') }} <i class="fa fa-arrow-right ml-1"></i>
            </button>
            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm" id="btn-reg-create-submit" style="display: none;">
                <i class="fa fa-save mr-1"></i> {{ __('Enregistrer l\'inscription') }}
            </button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    var currentStepReg = 1;
    var totalStepsReg = 3;

    function updateWizardRegUI() {
        $('#registration-create-wizard-form .step-content').removeClass('active');
        $('#step-reg-create-' + currentStepReg).addClass('active');

        for (var i = 1; i <= totalStepsReg; i++) {
            var $item = $('#step-tab-reg-create-' + i);
            if (i < currentStepReg) {
                $item.addClass('completed').removeClass('active');
            } else if (i === currentStepReg) {
                $item.addClass('active').removeClass('completed');
            } else {
                $item.removeClass('active completed');
            }
        }

        var progressPercent = ((currentStepReg - 1) / (totalStepsReg - 1)) * 100;
        $('#wizard-progress-reg-create').css('width', progressPercent + '%');

        if (currentStepReg === 1) {
            $('#btn-reg-create-prev').hide();
            $('#btn-reg-create-close').show();
        } else {
            $('#btn-reg-create-prev').show();
            $('#btn-reg-create-close').hide();
        }

        if (currentStepReg === totalStepsReg) {
            $('#btn-reg-create-next').hide();
            $('#btn-reg-create-submit').show();
        } else {
            $('#btn-reg-create-next').show();
            $('#btn-reg-create-submit').hide();
        }

        $('.modal-body').scrollTop(0);
    }

    $('#btn-reg-create-next').on('click', function() {
        if (currentStepReg < totalStepsReg) {
            currentStepReg++;
            updateWizardRegUI();
        }
    });

    $('#btn-reg-create-prev').on('click', function() {
        if (currentStepReg > 1) {
            currentStepReg--;
            updateWizardRegUI();
        }
    });

    $('#registration_type_select').on('change', function() {
        if ($(this).val() === 'reinscription') {
            $('#section_new_student').hide();
            $('#section_re_enrollment').show();
        } else {
            $('#section_new_student').show();
            $('#section_re_enrollment').hide();
        }
    });

    $('#registration-create-wizard-form .select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');
        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });

    $('.modal-body').on('scroll', function() {
        if ($('.select2-container--open').length > 0) {
            $('#registration-create-wizard-form .select2-modal').select2('close');
        }
    });

    $(document).on('select2:open', function() {
        let searchField = document.querySelector('.select2-search__field');
        if (searchField) {
            searchField.focus();
        }
    });

    // --- GESTION DYNAMIQUE DES DOCUMENTS AVEC SÉLECTEUR DE TYPE ---
    const documentTypeOptions = `@foreach($documentTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach`;

    let docIndex = 1;
    $('#add_doc_btn').on('click', function() {
        const html = `
            <div class="doc-row-item mb-2">
                <div class="row align-items-center">
                    <div class="col-3">
                        <select name="documents[${docIndex}][document_type_id]" class="form-control form-control-alternative form-control-sm doc-type-select">
                            <option value="">{{ __("Type...") }}</option>
                            ${documentTypeOptions}
                        </select>
                    </div>
                    <div class="col-4">
                        <input type="text" name="documents[${docIndex}][title]" class="form-control form-control-alternative form-control-sm" placeholder="Nom de la pièce">
                    </div>
                    <div class="col-4">
                        <div class="file-upload-wrapper">
                            <input type="file" name="documents[${docIndex}][file]" class="form-control-file">
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <input type="checkbox" name="documents[${docIndex}][is_provided]" value="1" checked>
                    </div>
                </div>
            </div>
        `;
        $('#documents_container').append(html);
        docIndex++;
    });
    
});
</script>