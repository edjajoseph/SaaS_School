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

.select2-container--default .select2-selection--single::before,
.select2-container--default .select2-selection--single::after {
    display: none !important;
}

.select2-container { 
    width: 100% !important; 
    display: block !important; 
}

.select2-dropdown { 
    z-index: 1060 !important; 
    border-color: #cccccc !important; 
}

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

hr { border: 0; height: 1px; background-color: #e9ecef; margin: 1.25rem 0; opacity: 1 !important; }
hr.hr-gradient { height: 2px; border: none; background: linear-gradient(to right, #4099ff, #2ed8b6); opacity: 1 !important; }
</style>

<form id="student-edit-wizard-form" method="POST" action="{{ route('schooling.students.update', $student) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-4">
        <div class="wizard-steps">
            <div class="wizard-progress-bar" id="wizard-progress-edit" style="width: 0%;"></div>
            <div class="step-item active" id="step-tab-edit-1">
                <div class="step-circle">1</div>
                <div class="step-label">{{ __('Identité & Contact') }}</div>
            </div>
            <div class="step-item" id="step-tab-edit-2">
                <div class="step-circle">2</div>
                <div class="step-label">{{ __('Scolarité & Naissance') }}</div>
            </div>
            <div class="step-item" id="step-tab-edit-3">
                <div class="step-circle">3</div>
                <div class="step-label">{{ __('Parents & Tuteur') }}</div>
            </div>
        </div>

        <hr class="hr-gradient mb-4">

        <!-- ÉTAPE 1 : IDENTITÉ & CONTACT -->
        <div class="step-content active" id="step-edit-1">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-user mr-1"></i> {{ __("Informations Personnelles") }}</h6>
                </div>
                
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Nom") }} <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control form-control-alternative" value="{{ old('nom', optional($student->personne)->nom) }}" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Prénoms") }} <span class="text-danger">*</span></label>
                        <input type="text" name="prenoms" class="form-control form-control-alternative" value="{{ old('prenoms', optional($student->personne)->prenoms) }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Sexe") }} <span class="text-danger">*</span></label>
                        <select name="sexe" class="form-control form-control-alternative select2-modal" required>
                            <option value=""></option>
                            <option value="M" {{ old('sexe', optional($student->personne)->sexe) == 'M' ? 'selected' : '' }}>{{ __("M") }}</option>
                            <option value="F" {{ old('sexe', optional($student->personne)->sexe) == 'F' ? 'selected' : '' }}>{{ __("F") }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Téléphone") }}</label>
                        <input type="text" name="telephone" class="form-control form-control-alternative" value="{{ old('telephone', optional($student->personne)->telephone) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Email") }}</label>
                        <input type="email" name="email" class="form-control form-control-alternative" value="{{ old('email', optional($student->personne)->email) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Pays d'Origine") }}</label>
                        <select name="country_id" class="form-control form-control-alternative select2-modal">
                            <option value=""></option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', optional($student->personne)->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Photo d'identité") }}</label>
                        <div class="d-flex align-items-center">
                            <div class="mr-3 position-relative" style="width: 70px; height: 70px;">
                                <img id="avatar-preview-edit" src="{{ optional($student->personne)->photo ? route('schooling.file.display', ['path' => ltrim($student->personne->photo, '/')]) : '#' }}" alt="Aperçu" class="rounded-circle border shadow-sm" style="width: 70px; height: 70px; object-fit: cover; {{ optional($student->personne)->photo ? 'display: block;' : 'display: none;' }}">
                                <div id="avatar-placeholder-edit" class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px; {{ optional($student->personne)->photo ? 'display: none !important;' : 'display: flex !important;' }}">
                                    <i class="fa fa-user fa-2x text-secondary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="photo" id="photo-input-edit" class="form-control form-control-alternative" accept="image/png, image/jpeg, image/jpg" onchange="previewImageEdit(this)">
                                <small class="text-muted d-block mt-1">Formats : JPG, PNG (Max: 2Mo)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 2 : SCOLARITÉ & NAISSANCE -->
        <div class="step-content" id="step-edit-2">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-3"><i class="fa fa-graduation-cap mr-1"></i> {{ __("Détails Académiques & Naissance") }}</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Matricule") }} <span class="text-danger">*</span></label>
                        <input type="text" name="registration_number" class="form-control form-control-alternative" value="{{ old('registration_number', $student->registration_number) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Nationalité") }}</label>
                        <select name="nationality" class="form-control form-control-alternative select2-modal">
                            <option value=""></option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('nationality', optional($student->personne)->nationality) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Date de naissance") }}</label>
                        <input type="date" name="birth_date" class="form-control form-control-alternative" value="{{ old('birth_date', $student->birth_date) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Lieu de naissance") }}</label>
                        <input type="text" name="birth_place" class="form-control form-control-alternative" value="{{ old('birth_place', $student->birth_place) }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Adresse / Domicile") }}</label>
                        <input type="text" name="address" class="form-control form-control-alternative" value="{{ old('address', $student->address) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉTAPE 3 : PARENTS & TUTEUR -->
        <div class="step-content" id="step-edit-3">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-male mr-1"></i> {{ __("Informations du Père") }}</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Nom du Père") }}</label>
                        <input type="text" name="father_name" class="form-control form-control-alternative" value="{{ old('father_name', $student->father_name) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Profession du Père") }}</label>
                        <input type="text" name="father_job" class="form-control form-control-alternative" value="{{ old('father_job', $student->father_job) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Téléphone Père") }}</label>
                        <input type="text" name="father_phone" class="form-control form-control-alternative" value="{{ old('father_phone', $student->father_phone) }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <hr class="mt-1 mb-3">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-female mr-1"></i> {{ __("Informations de la Mère") }}</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Nom de la Mère") }}</label>
                        <input type="text" name="mother_name" class="form-control form-control-alternative" value="{{ old('mother_name', $student->mother_name) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Profession Mère") }}</label>
                        <input type="text" name="mother_job" class="form-control form-control-alternative" value="{{ old('mother_job', $student->mother_job) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Téléphone Mère") }}</label>
                        <input type="text" name="mother_phone" class="form-control form-control-alternative" value="{{ old('mother_phone', $student->mother_phone) }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <hr class="mt-1 mb-3">
                    <h6 class="text-primary font-weight-bold mb-2"><i class="fa fa-user-shield mr-1"></i> {{ __("Tuteur Légal / Contact d'urgence") }}</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Nom du Tuteur") }}</label>
                        <input type="text" name="guardian_name" class="form-control form-control-alternative" value="{{ old('guardian_name', $student->guardian_name) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Lien de Parenté") }}</label>
                        <input type="text" name="guardian_relation" class="form-control form-control-alternative" value="{{ old('guardian_relation', $student->guardian_relation) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold text-dark">{{ __("Téléphone Tuteur") }}</label>
                        <input type="text" name="guardian_phone" class="form-control form-control-alternative" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-round waves-effect" id="btn-edit-prev" style="display: none;">
            <i class="fa fa-arrow-left mr-1"></i> {{ __('Précédent') }}
        </button>

        <button type="button" class="btn btn-secondary btn-round waves-effect close-modal-btn" data-dismiss="modal" data-bs-dismiss="modal" id="btn-edit-close">
            <i class="fa fa-times mr-1"></i> {{ __('Fermer') }}
        </button>

        <div>
            <button type="button" class="btn btn-primary btn-round waves-effect shadow-sm" id="btn-edit-next">
                {{ __('Suivant') }} <i class="fa fa-arrow-right ml-1"></i>
            </button>
            <button type="submit" class="btn btn-success btn-round waves-effect shadow-sm" id="btn-edit-submit" style="display: none;">
                <i class="fa fa-sync-alt mr-1"></i> {{ __('Mettre à jour') }}
            </button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    var currentStepEdit = 1;
    var totalStepsEdit = 3;

    function updateWizardEditUI() {
        $('#student-edit-wizard-form .step-content').removeClass('active');
        $('#step-edit-' + currentStepEdit).addClass('active');

        for (var i = 1; i <= totalStepsEdit; i++) {
            var $item = $('#step-tab-edit-' + i);
            if (i < currentStepEdit) {
                $item.addClass('completed').removeClass('active');
            } else if (i === currentStepEdit) {
                $item.addClass('active').removeClass('completed');
            } else {
                $item.removeClass('active completed');
            }
        }

        var progressPercent = ((currentStepEdit - 1) / (totalStepsEdit - 1)) * 100;
        $('#wizard-progress-edit').css('width', progressPercent + '%');

        if (currentStepEdit === 1) {
            $('#btn-edit-prev').hide();
            $('#btn-edit-close').show();
        } else {
            $('#btn-edit-prev').show();
            $('#btn-edit-close').hide();
        }

        if (currentStepEdit === totalStepsEdit) {
            $('#btn-edit-next').hide();
            $('#btn-edit-submit').show();
        } else {
            $('#btn-edit-next').show();
            $('#btn-edit-submit').hide();
        }
    }

    $('#btn-edit-next').on('click', function() {
        if (currentStepEdit < totalStepsEdit) {
            currentStepEdit++;
            updateWizardEditUI();
        }
    });

    $('#btn-edit-prev').on('click', function() {
        if (currentStepEdit > 1) {
            currentStepEdit--;
            updateWizardEditUI();
        }
    });

    $('#student-edit-wizard-form .select2-modal').each(function() {
        var $select = $(this);
        var $modal = $select.closest('.modal');
        $select.select2({
            dropdownParent: $modal.length ? $modal : $(document.body),
            width: '100%',
            allowClear: true
        });
    });
});

function previewImageEdit(input) {
    var preview = document.getElementById('avatar-preview-edit');
    var placeholder = document.getElementById('avatar-placeholder-edit');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.setProperty('display', 'none', 'important');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>