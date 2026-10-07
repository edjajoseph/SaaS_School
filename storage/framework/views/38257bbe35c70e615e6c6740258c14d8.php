<form action="<?php echo e(route('academic.materials.store', $subjectRate->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    
    <div id="materials-container">
        <!-- Ligne d'upload 1 -->
        <div class="material-row border p-3 mb-3 rounded bg-light position-relative">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label font-weight-bold text-dark">Titre du document <span class="text-danger">*</span></label>
                        <input type="text" name="titles[]" class="form-control" placeholder="ex: Chapitre 1 - Introduction" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label font-weight-bold text-dark">Type de support <span class="text-danger">*</span></label>
                        <select name="types[]" class="form-control" required>
                            <option value="course_note">Support de cours / Fiche</option>
                            <option value="syllabus">Syllabus</option>
                            <option value="td">Travaux Dirigés (TD)</option>
                            <option value="tp">Travaux Pratiques (TP)</option>
                            <option value="exam">Sujet d'examen / Évaluation</option>
                            <option value="other">Autre document</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold text-dark">Fichier (PDF, Word, PPT, ZIP - Max 20Mo) <span class="text-danger">*</span></label>
                        <input type="file" name="files[]" class="form-control-file border p-2 w-100 rounded bg-white" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'ajout de ligne -->
    <div class="d-flex justify-content-between align-items-center mt-2">
        <button type="button" id="add-material-row" class="btn btn-outline-success btn-sm">
            <i class="fa fa-plus-circle mr-1"></i> Ajouter un autre fichier
        </button>
        <a href="<?php echo e(route('school.accounting.teacher-rates.index')); ?>" class="btn btn-secondary btn-round waves-effect">
            <i class="fa fa-times mr-1"></i> <?php echo e(__('Fermer')); ?>

        </a>
        <button type="submit" class="btn btn-primary btn-round">
            <i class="fa fa-upload mr-1"></i> Tout téléverser
        </button>
    </div>
</form>

<script>
    document.getElementById('add-material-row').addEventListener('click', function() {
        const container = document.getElementById('materials-container');
        const newRow = document.createElement('div');
        newRow.className = 'material-row border p-3 mb-3 rounded bg-light position-relative';
        
        newRow.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm remove-row position-absolute" style="top: 10px; right: 10px;" title="Supprimer ce fichier">
                <i class="fa fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label font-weight-bold text-dark">Titre du document <span class="text-danger">*</span></label>
                        <input type="text" name="titles[]" class="form-control" placeholder="ex: Support complémentaire" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label font-weight-bold text-dark">Type de support <span class="text-danger">*</span></label>
                        <select name="types[]" class="form-control" required>
                            <option value="course_note">Support de cours / Fiche</option>
                            <option value="syllabus">Syllabus</option>
                            <option value="td">Travaux Dirigés (TD)</option>
                            <option value="tp">Travaux Pratiques (TP)</option>
                            <option value="exam">Sujet d'examen / Évaluation</option>
                            <option value="other">Autre document</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <label class="form-label font-weight-bold text-dark">Fichier (PDF, Word, PPT, ZIP - Max 20Mo) <span class="text-danger">*</span></label>
                        <input type="file" name="files[]" class="form-control-file border p-2 w-100 rounded bg-white" required>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newRow);
    });

    // Suppression d'une ligne ajoutée
    document.getElementById('materials-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.material-row').remove();
        }
    });
</script><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/payroll/rates/upload.blade.php ENDPATH**/ ?>