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
</style>

<form id="registration-edit-wizard-form" method="POST" action="{{ route('schooling.registrations.update', $registration->id) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-4">
        <div class="wizard-steps">
            <div class="wizard-progress-bar" id="wizard-progress-reg-edit" style="width: 0%;"></div>
            <div class="step-item active" id="step-tab-reg-edit-1">
                <div class="step-circle">1</div>
                <div class="step-label">{{ __('Cadre Académique') }}</div>
            </div>
            <div class="step-item" id="step-tab-reg-edit-2">
                <div class="step-circle">2</div>
                <div class="step-label">{{ __('Identité & Étudiant') }}</div>
            </div>
            <div class="step-item" id="step-tab-reg-edit-3">
                <div class="step-circle">3</div>
                <div class="step-label">{{ __('Frais & Documents') }}</div>
            </div>
        </div>

        <hr class="hr-gradient mb-4">

        <!-- ÉTAPE 1 : CADRE ACADÉMIQUE -->
        <div class="step-content active" id="step-reg-edit-1">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-university mr-1"></i> {{ __("Paramètres de l'Inscription") }}</h6>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Type d'opération") }} <span class="text-danger">*</span></label>
                        <select name="type" id="registration_type_select_edit" class="form-control form-control-alternative select2-modal" required>
                            <option value="inscription" {{ old('type', $registration->type) == 'inscription' ? 'selected' : '' }}>{{ __("Nouvelle Inscription") }}</option>
                            <option value="reinscription" {{ old('type', $registration->type) == 'reinscription' ? 'selected' : '' }}>{{ __("Réinscription") }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Établissement") }} <span class="text-danger">*</span></label>
                        <select name="school_id" class="form-control form-control-alternative select2-modal" required>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $registration->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Année Académique") }} <span class="text-danger">*</span></label>
                        <select name="academic_year_id" class="form-control form-control-alternative select2-modal" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $registration->academic_year_id) == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
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
                                <option value="{{ $class->id }}" {{ old('school_class_id', $registration->school_class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Date d'inscription") }} <span class="text-danger">*</span></label>
                        <input type="date" name="registration_date" class="form-control form-control-alternative" value="{{ old('registration_date', \Carbon\Carbon::parse($registration->registration_date)->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Statut Inscription") }} <span class="text-danger">*</span></label>
                        <select name="status" class="form-control form-control-alternative select2-modal" required>
                            <option value="confirmed" {{ old('status', $registration->status) == 'confirmed' ? 'selected' : '' }}>{{ __("Confirmée") }}</option>
                            <option value="pending" {{ old('status', $registration->status) == 'pending' ? 'selected' : '' }}>{{ __("En attente") }}</option>
                            <option value="canceled" {{ old('status', $registration->status) == 'canceled' ? 'selected' : '' }}>{{ __("Annulée") }}</option>
                            <option value="transferred" {{ old('status', $registration->status) == 'transferred' ? 'selected' : '' }}>{{ __("Transférée") }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 2 : IDENTITÉ ÉTUDIANT & PERSONNE -->
        <div class="step-content" id="step-reg-edit-2">
            <div id="section_re_enrollment_edit" style="{{ old('type', $registration->type) == 'reinscription' ? 'display: block;' : 'display: none;' }}">
                <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user-check mr-1"></i> {{ __("Changer l'Étudiant Rattaché") }}</h6>
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold text-dark">{{ __("Étudiant") }} <span class="text-danger">*</span></label>
                    <select name="student_id" id="student_id_select_edit" class="form-control form-control-alternative select2-modal">
                        <option value=""></option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $registration->student_id) == $student->id ? 'selected' : '' }}>
                                {{ optional($student->personne)->nom_complet ?? (optional($student->personne)->nom . ' ' . optional($student->personne)->prenoms) }} (Matricule: {{ $student->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <hr class="my-4">
            </div>

            <div id="section_student_details_edit">
                @php
                    $personne = optional($registration->student)->personne;
                    $studentModel = $registration->student;
                @endphp

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user-edit mr-1"></i> {{ __("État Civil & Identité") }}</h6>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Matricule Étudiant") }}</label>
                            <input type="text" name="matricule" class="form-control form-control-alternative bg-white" value="{{ old('matricule', optional($studentModel)->matricule) }}" placeholder="{{ __("Auto-généré si vide") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom") }} <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control form-control-alternative" value="{{ old('nom', optional($personne)->nom) }}" required placeholder="{{ __("Ex: KOUASSI") }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Prénoms") }} <span class="text-danger">*</span></label>
                            <input type="text" name="prenoms" class="form-control form-control-alternative" value="{{ old('prenoms', optional($personne)->prenoms) }}" required placeholder="{{ __("Ex: Jean-Marc") }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Sexe") }} <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-control form-control-alternative select2-modal" required>
                                <option value="M" {{ old('sexe', optional($personne)->sexe) == 'M' ? 'selected' : '' }}>{{ __("Masculin (M)") }}</option>
                                <option value="F" {{ old('sexe', optional($personne)->sexe) == 'F' ? 'selected' : '' }}>{{ __("Féminin (F)") }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Date de naissance") }}</label>
                            <input type="date" name="birth_date" class="form-control form-control-alternative" value="{{ old('birth_date', optional($personne)->birth_date) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Lieu de naissance") }}</label>
                            <input type="text" name="birth_place" class="form-control form-control-alternative" value="{{ old('birth_place', optional($personne)->birth_place) }}" placeholder="{{ __("Ex: Abidjan") }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nationalité") }}</label>
                            <input type="text" name="nationality" class="form-control form-control-alternative" value="{{ old('nationality', optional($studentModel)->nationality ?? 'Ivoirienne') }}" placeholder="{{ __("Ex: Ivoirienne") }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("N° CNI / Passeport / Extrait") }}</label>
                            <input type="text" name="id_card_number" class="form-control form-control-alternative" value="{{ old('id_card_number', optional($personne)->id_card_number) }}" placeholder="{{ __("N° de pièce d'identité") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Situation matrimoniale") }}</label>
                            <select name="marital_status" class="form-control form-control-alternative select2-modal">
                                <option value="single" {{ old('marital_status', optional($personne)->marital_status) == 'single' ? 'selected' : '' }}>{{ __("Célibataire") }}</option>
                                <option value="married" {{ old('marital_status', optional($personne)->marital_status) == 'married' ? 'selected' : '' }}>{{ __("Marié(e)") }}</option>
                                <option value="divorced" {{ old('marital_status', optional($personne)->marital_status) == 'divorced' ? 'selected' : '' }}>{{ __("Divorcé(e)") }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Photo d'identité") }}</label>
                            <input type="file" name="photo" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg">
                            @if(optional($personne)->photo)
                                <small class="d-block mt-1 text-muted"><i class="fa fa-image mr-1"></i> {{ __("Une photo est déjà enregistrée") }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12"><hr class="my-2"></div>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-address-card mr-1"></i> {{ __("Coordonnées & Résidence") }}</h6>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Téléphone") }}</label>
                            <input type="text" name="telephone" class="form-control form-control-alternative" value="{{ old('telephone', optional($personne)->telephone) }}" placeholder="{{ __("Ex: +225 0700000000") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Email") }}</label>
                            <input type="email" name="email" class="form-control form-control-alternative" value="{{ old('email', optional($personne)->email) }}" placeholder="{{ __("Ex: etudiant@domaine.com") }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Ville / Commune de Résidence") }}</label>
                            <input type="text" name="city" class="form-control form-control-alternative" value="{{ old('city', optional($personne)->city) }}" placeholder="{{ __("Ex: Yopougon, Abidjan") }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Adresse géographique / Domicile") }}</label>
                            <input type="text" name="address" class="form-control form-control-alternative" value="{{ old('address', optional($personne)->address) }}" placeholder="{{ __("Quartier, Rue, Porte...") }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-12"><hr class="my-2"></div>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-users mr-1"></i> {{ __("Parents & Tuteurs") }}</h6>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom du Père") }}</label>
                            <input type="text" name="father_name" class="form-control form-control-alternative" value="{{ old('father_name', optional($studentModel)->father_name) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Tél. Père") }}</label>
                            <input type="text" name="father_phone" class="form-control form-control-alternative" value="{{ old('father_phone', optional($studentModel)->father_phone) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom de la Mère") }}</label>
                            <input type="text" name="mother_name" class="form-control form-control-alternative" value="{{ old('mother_name', optional($studentModel)->mother_name) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Tél. Mère") }}</label>
                            <input type="text" name="mother_phone" class="form-control form-control-alternative" value="{{ old('mother_phone', optional($studentModel)->mother_phone) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Nom du Tuteur Légal") }}</label>
                            <input type="text" name="guardian_name" class="form-control form-control-alternative" value="{{ old('guardian_name', optional($studentModel)->guardian_name) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-dark">{{ __("Téléphone Tuteur Légal") }}</label>
                            <input type="text" name="guardian_phone" class="form-control form-control-alternative" value="{{ old('guardian_phone', optional($studentModel)->guardian_phone) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 3 : FRAIS ET DOCUMENTS -->
        <div class="step-content" id="step-reg-edit-3">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-money-bill-wave mr-1"></i> {{ __("Règlement & Frais") }}</h6>                    
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Reçu de versement / Preuve (PDF, Image)") }}</label>
                        <input type="file" name="payment_receipt" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg, application/pdf">
                        @if($registration->payment_receipt)
                            <small class="d-block mt-1">
                                <a href="{{ route('schooling.file.display', ['path' => ltrim($registration->payment_receipt, '/')]) }}" target="_blank" class="text-primary font-weight-bold">
                                    <i class="fa fa-file-alt mr-1"></i> {{ __('Voir le reçu actuel') }}
                                </a>
                            </small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Remarques / Notes") }}</label>
                        <textarea name="notes" class="form-control form-control-alternative" rows="3">{{ old('notes', $registration->notes) }}</textarea>
                    </div>
                </div>

                <!-- SECTION DOCUMENTS MIS À JOUR -->
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-folder-open mr-1"></i> {{ __("Documents & Pièces fournies") }}</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-round" id="add_doc_btn_edit"><i class="fa fa-plus mr-1"></i> Ajouter</button>
                    </div>
                    <div id="documents_container_edit">
                        @if(isset($registration->documents) && count($registration->documents) > 0)
                            @foreach($registration->documents as $index => $doc)
                                <div class="doc-row-item mb-2">
                                    <div class="row align-items-center">
                                        <div class="col-3">
                                            <input type="hidden" name="documents[{{ $index }}][id]" value="{{ $doc->id }}">
                                            <select name="documents[{{ $index }}][document_type_id]" class="form-control form-control-alternative form-control-sm doc-type-select">
                                                <option value="">{{ __("Type...") }}</option>
                                                @foreach($documentTypes as $type)
                                                    <option value="{{ $type->id }}" {{ $doc->document_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="documents[{{ $index }}][title]" class="form-control form-control-alternative form-control-sm" value="{{ $doc->title }}" placeholder="Nom pièce">
                                        </div>
                                        <div class="col-4">
                                            <div class="file-upload-wrapper">
                                                <input type="file" name="documents[{{ $index }}][file]" class="form-control-file">
                                            </div>
                                            @if($doc->file_path)
                                                <small class="d-block mt-1"><a href="{{ route('schooling.file.display', ['path' => ltrim($doc->file_path, '/')]) }}" target="_blank" class="text-primary"><i class="fa fa-paperclip"></i> Voir la pièce</a></small>
                                            @endif
                                        </div>
                                        <div class="col-2 text-center">
                                            <input type="checkbox" name="documents[{{ $index }}][is_provided]" value="1" {{ $doc->is_provided ? 'checked' : '' }} title="Fourni ?">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
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
                                        <input type="text" name="documents[0][title]" class="form-control form-control-alternative form-control-sm" placeholder="Nom pièce">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" id="btn-reg-edit-prev" style="display: none;">
            <i class="fa fa-arrow-left mr-1"></i> {{ __('Précédent') }}
        </button>

        <a href="{{ route('schooling.registrations.index') }}" class="btn btn-secondary btn-round waves-effect close-modal-btn" id="btn-reg-create-close">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </a>

        <div>
            <button type="button" class="btn btn-primary btn-round waves-effect shadow-sm" id="btn-reg-edit-next">
                {{ __('Suivant') }} <i class="fa fa-arrow-right ml-1"></i>
            </button>
            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm" id="btn-reg-edit-submit" style="display: none;">
                <i class="fa fa-save mr-1"></i> {{ __('Mettre à jour') }}
            </button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    var currentStepRegEdit = 1;
    var totalStepsRegEdit = 3;

    function updateWizardRegEditUI() {
        $('#registration-edit-wizard-form .step-content').removeClass('active');
        $('#step-reg-edit-' + currentStepRegEdit).addClass('active');

        for (var i = 1; i <= totalStepsRegEdit; i++) {
            var $item = $('#step-tab-reg-edit-' + i);
            if (i < currentStepRegEdit) {
                $item.addClass('completed').removeClass('active');
            } else if (i === currentStepRegEdit) {
                $item.addClass('active').removeClass('completed');
            } else {
                $item.removeClass('active completed');
            }
        }

        var progressPercent = ((currentStepRegEdit - 1) / (totalStepsRegEdit - 1)) * 100;
        $('#wizard-progress-reg-edit').css('width', progressPercent + '%');

        if (currentStepRegEdit === 1) {
            $('#btn-reg-edit-prev').hide();
            $('#btn-reg-edit-close').show();
        } else {
            $('#btn-reg-edit-prev').show();
            $('#btn-reg-edit-close').hide();
        }

        if (currentStepRegEdit === totalStepsRegEdit) {
            $('#btn-reg-edit-next').hide();
            $('#btn-reg-edit-submit').show();
        } else {
            $('#btn-reg-edit-next').show();
            $('#btn-reg-edit-submit').hide();
        }
    }

    $('#btn-reg-edit-next').on('click', function() {
        if (currentStepRegEdit < totalStepsRegEdit) {
            currentStepRegEdit++;
            updateWizardRegEditUI();
        }
    });

    $('#btn-reg-edit-prev').on('click', function() {
        if (currentStepRegEdit > 1) {
            currentStepRegEdit--;
            updateWizardRegEditUI();
        }
    });

    $('#registration_type_select_edit').on('change', function() {
        if ($(this).val() === 'reinscription') {
            $('#section_re_enrollment_edit').show();
        } else {
            $('#section_re_enrollment_edit').hide();
        }
    });

    $('#registration-edit-wizard-form .select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');
        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });

    const documentTypeOptionsEdit = `@foreach($documentTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach`;

    let docIndexEdit = {{ isset($registration->documents) ? count($registration->documents) : 1 }};
    $('#add_doc_btn_edit').on('click', function() {
        const html = `
            <div class="doc-row-item mb-2">
                <div class="row align-items-center">
                    <div class="col-3">
                        <select name="documents[${docIndexEdit}][document_type_id]" class="form-control form-control-alternative form-control-sm doc-type-select">
                            <option value="">{{ __("Type...") }}</option>
                            ${documentTypeOptionsEdit}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="text" name="documents[${docIndexEdit}][title]" class="form-control form-control-alternative form-control-sm" placeholder="Nom de la pièce">
                    </div>
                    <div class="col-4">
                        <div class="file-upload-wrapper">
                            <input type="file" name="documents[${docIndexEdit}][file]" class="form-control-file">
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="checkbox" name="documents[${docIndexEdit}][is_provided]" value="1" checked>
                    </div>
                </div>
            </div>
        `;
        $('#documents_container_edit').append(html);
        docIndexEdit++;
    });    
});
</script>