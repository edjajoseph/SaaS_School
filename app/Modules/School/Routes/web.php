<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Auth
use App\Modules\School\Http\Controllers\Auth\LoginController;

// Dashboard
use App\Modules\School\Http\Controllers\DashboardController;
use App\Modules\School\Http\Controllers\Dashboard\AdminDashboardController;
use App\Modules\School\Http\Controllers\Dashboard\TeacherDashboardController;
use App\Modules\School\Http\Controllers\Dashboard\StudentDashboardController;

// Settings
use App\Modules\School\Http\Controllers\Web\Settings\DegreeController;
use App\Modules\School\Http\Controllers\Web\Settings\StaffRoleController;
use App\Modules\School\Http\Controllers\Web\Settings\CountryController;
use App\Modules\School\Http\Controllers\Web\Settings\SpecialityController;
use App\Modules\School\Http\Controllers\Web\Settings\PeriodTypeController;
use App\Modules\School\Http\Controllers\Web\Settings\CycleController;
use App\Modules\School\Http\Controllers\Web\Settings\LevelsController;
use App\Modules\School\Http\Controllers\Web\Settings\SerieController;
use App\Modules\School\Http\Controllers\Web\Settings\SubjectController;
use App\Modules\School\Http\Controllers\Web\Settings\DocumentTypeController;

// Academique
use App\Modules\School\Http\Controllers\Web\Academique\AcademicYearController;
use App\Modules\School\Http\Controllers\Web\Academique\SchoolClassController;
use App\Modules\School\Http\Controllers\Web\Academique\SchoolController;
use App\Modules\School\Http\Controllers\Web\Academique\AcademicPeriodController;
use App\Modules\School\Http\Controllers\Web\Academique\CourseMaterialController;


//Organisation
use App\Modules\School\Http\Controllers\Web\Academique\TeachingUnitController;
use App\Modules\School\Http\Controllers\Web\Academique\SchoolSubjectController;
use App\Modules\School\Http\Controllers\Web\Academique\SchoolSerieController;
use App\Modules\School\Http\Controllers\Web\Academique\EvaluationTypeController;

// Schedule(Emploi du temps)
use App\Modules\School\Http\Controllers\Web\Schedule\StaffController;
use App\Modules\School\Http\Controllers\Web\Schedule\RoomController;
use App\Modules\School\Http\Controllers\Web\Schedule\ScheduleController;
use App\Modules\School\Http\Controllers\Web\Schedule\LeaveRequestController;

// Sécurity
use App\Modules\School\Http\Controllers\Web\Security\PermissionController;
use App\Modules\School\Http\Controllers\Web\Security\PersonneController;
use App\Modules\School\Http\Controllers\Web\Security\RoleController;
use App\Modules\School\Http\Controllers\Web\Security\UserController;

// Schooling
use App\Modules\School\Http\Controllers\Web\Schooling\RegistrationController;
use App\Modules\School\Http\Controllers\Web\Schooling\StudentController;

// Evaluation
use App\Modules\School\Http\Controllers\Web\Evaluation\EvaluationController;
use App\Modules\School\Http\Controllers\Web\Evaluation\GradeController;
use App\Modules\School\Http\Controllers\Web\Evaluation\ReportCardController;
use App\Modules\School\Http\Controllers\Web\Evaluation\TeacherGradeController;


//Attendance(Pointage)
use App\Modules\School\Http\Controllers\Web\Attendance\AttendanceController;
use App\Modules\School\Http\Controllers\Web\Attendance\StaffAttendanceController;
use App\Modules\School\Http\Controllers\Web\Attendance\AttendanceTerminalController;
use App\Modules\School\Http\Controllers\Web\Attendance\TeacherScheduleAttendanceController;
use App\Modules\School\Http\Controllers\Web\Attendance\TeacherPayrollController;


//Logbook(Cahier de texte)
use App\Modules\School\Http\Controllers\Web\Logbook\TeacherAttendanceController;


//Accounting (Recouvrement)
use App\Modules\School\Http\Controllers\Web\Accounting\PaymentController;
use App\Modules\School\Http\Controllers\Web\Accounting\FeePlanController;
use App\Modules\School\Http\Controllers\Web\Accounting\FinancialReportController;

//Comptabilité générale
use App\Modules\School\Http\Controllers\Web\Accounting\FiscalYearController;
use App\Modules\School\Http\Controllers\Web\Accounting\ChartOfAccountController;
use App\Modules\School\Http\Controllers\Web\Accounting\JournalController;
use App\Modules\School\Http\Controllers\Web\Accounting\AccountingEntryController;

use App\Modules\School\Http\Controllers\Web\Accounting\GeneralLedgerController;
use App\Modules\School\Http\Controllers\Web\Accounting\AccountLedgerController;
use App\Modules\School\Http\Controllers\Web\Accounting\IncomeStatementController;
use App\Modules\School\Http\Controllers\Web\Accounting\BalanceSheetController;
use App\Modules\School\Http\Controllers\Web\Accounting\BankReconciliationController;

//Paie du personnel
use App\Modules\School\Http\Controllers\Web\Accounting\PayrollController;
use App\Modules\School\Http\Controllers\Web\Accounting\TeacherSubjectRateController;
use App\Modules\School\Http\Controllers\Web\Accounting\PayrollSettingController;
use App\Modules\School\Http\Controllers\Web\Accounting\PayrollTaxRuleController;



//Recherche-Print-Documentation
use App\Modules\School\Http\Controllers\Web\Document\TeacherDocumentController;




// --------------------------------------------------------------------------
// Auth Routes
// --------------------------------------------------------------------------
// Formulaire de connexion (GET)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('tenant.login');

Route::post('/login', [LoginController::class, 'login'])->name('tenant.login.submit');

// --------------------------------------------------------------------------
// Protected Routes
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboards
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('school.dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {      
        Route::get('/admin', [AdminDashboardController::class, 'index'])->name('school.admin');
        //Route::get('/teacher', [TeacherDashboardController::class, 'index'])->name('school.teacher'); 
        //Route::get('/student', [StudentDashboardController::class, 'index'])->name('school.student');
    
    });


    

    // -- Organisation de l'Établissement & Offre Académique ------------------
    Route::prefix('settings')->name('settings.')->group(function () {

        Route::prefix('rh')->name('rh.')->group(function () {

            // Route ressource pour les Rôles / Fonctions du Personnel
            Route::resource('staff-roles', StaffRoleController::class);

            // Route ressource pour les Spécialités
            Route::resource('specialities', SpecialityController::class);

            // Route ressource pour les Spécialités
            Route::resource('document_types', DocumentTypeController::class);

            Route::patch('/{id}/toggle-status', [DocumentTypeController::class, 'toggleStatus'])->name('document_types.toggle-status');

        });

        Route::prefix('local')->name('local.')->group(function () {

            // Route ressource pour les Pays & Nationalités
            Route::resource('countries', CountryController::class);

        });

        Route::prefix('academic')->name('academic.')->group(function () {

            // Route ressource pour les Diplômes
            Route::resource('degrees', DegreeController::class); 
            

            // Structure Pédagogique
            Route::resource('cycles', CycleController::class);

            Route::patch('/cycles/{cycle}/toggle-status', [CycleController::class, 'toggleStatus'])->name('cycles.toggle-status');
            
            Route::patch('levels/{level}/toggle-active', [LevelsController::class, 'toggleActive'])->name('levels.toggle-active');

            Route::resource('levels', LevelsController::class);

            Route::patch('series/{serie}/toggle-active', [SerieController::class, 'toggleActive'])->name('series.toggle-active');

            Route::resource('series', SerieController::class)->parameters([
                'series' => 'serie'
            ]);

            Route::resource('period-types', PeriodTypeController::class);

            // Référentiel centralisé des matières
            Route::resource('subjects', SubjectController::class);
            

        });

        
    });
    

    Route::prefix('organisation')->name('organisation.')->group(function () {
        
        // Établissements & Périodes
        Route::patch('schools/{school}/set-current-academic-year', [SchoolController::class, 'setCurrentAcademicYear'])->name('schools.set-current-academic-year');

        Route::patch('schools/{school}/toggle-active', [SchoolController::class, 'toggleActive'])->name('schools.toggle-active');

        Route::resource('schools', SchoolController::class);

        Route::patch('academic-years/{academic_year}/set-current', [AcademicYearController::class, 'toggleCurrent'])->name('academic-years.set-current');

        Route::resource('academic-years', AcademicYearController::class);

        Route::patch('terms/{period}/set-current', [AcademicPeriodController::class, 'setCurrent'])->name('terms.set-current');

        Route::patch('terms/{period}/toggle-closed', [AcademicPeriodController::class, 'toggleClosed'])->name('terms.toggle-closed');

        Route::resource('periods', AcademicPeriodController::class);        

        Route::patch('school-classes/{school_class}/toggle-active', [SchoolClassController::class, 'toggleActive'])->name('classes.toggle-active');

        Route::resource('classes', SchoolClassController::class);  

    });

    Route::prefix('academic')->name('academic.')->group(function (){

        Route::resource('teaching-units', TeachingUnitController::class)->names('teaching-units');

        // Route AJAX pour la cascade Select2
        Route::get('school-subjects/teaching-units', [SchoolSubjectController::class, 'getTeachingUnitsBySchool'])->name('school-subjects.teaching-units');

        Route::patch('school-classes/{teachingUnit}/toggle-active', [TeachingUnitController::class, 'toggleActive'])->name('teaching-units.toggle-active');

        // Resource Route
        Route::resource('school-subjects', SchoolSubjectController::class);

        Route::resource('school-series', SchoolSerieController::class);

        /*
        |--------------------------------------------------------------------------
        | Types d'évaluations (CC, TP, Examen, Rattrapage)
        |--------------------------------------------------------------------------
        */
        Route::resource('evaluation-types', EvaluationTypeController::class)->except(['show']);
        Route::post('evaluation-types/{evaluation_type}/toggle-status', [EvaluationTypeController::class, 'toggleStatus'])
            ->name('evaluation-types.toggle-status');


        Route::prefix('school/academic/materials')->name('materials.')->middleware(['auth'])->group(function () {
            Route::get('/upload/{teacherSubjectRateId}', [CourseMaterialController::class, 'create'])->name('create');
            Route::post('/upload/{teacherSubjectRateId}', [CourseMaterialController::class, 'store'])->name('store');
            Route::get('/download/{id}', [CourseMaterialController::class, 'download'])->name('download');
            Route::delete('/{id}', [CourseMaterialController::class, 'destroy'])->name('destroy');
        });

    });


    Route::prefix('schedule')->name('schedule.')->group(function () {

        // Route ressource pour le Membre du Personnel / Staff
        Route::resource('staff', StaffController::class);

        Route::get('/file-display/{path}', function ($path) {
            if (!Storage::disk('local')->exists($path)) {
                abort(404);
            }    
            return Storage::disk('local')->response($path);
        })->where('path', '.*')->name('staff-file.display');


        Route::get('/staff/{id}/pdf', [StaffController::class, 'exportPdf'])->name('staff.pdf');

        // Route spécifique pour basculer l'activation d'une salle
        Route::patch('rooms/{room}/toggle-active', [RoomController::class, 'toggleActive'])->name('rooms.toggle-active');

        // Route Resource standard pour Room (index, create, store, show, edit, update, destroy)
        Route::resource('rooms', RoomController::class);
        
        // Route resource standard pour Schedule
        Route::resource('schedules', ScheduleController::class);

        Route::get('schedule/get-school-data/{schoolId}', [ScheduleController::class, 'getSchoolData'])->name('get-school-data');

        // Espace Employé / Enseignant
        Route::get('/leave-requests', [LeaveRequestController::class, 'myRequests'])->name('leave-requests.index');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');

        // Espace Valideur (DRH / Directeur d'Études)
        Route::get('/leave-approvals', [LeaveRequestController::class, 'pendingApprovals'])->name('leave-requests.approvals');
        Route::post('/leave-requests/{leaveRequest}/process', [LeaveRequestController::class, 'process'])->name('leave-requests.process');

    });

   

    Route::prefix('schooling')->name('schooling.')->group(function () {

        // Resource routes pour la gestion des étudiants
        Route::resource('students', StudentController::class);

        Route::get('students/{student}/pdf', [StudentController::class, 'generatePdf'])->name('students.pdf');
        
        // -- Inscriptions & Scolarité ---------------------------------------------
        Route::resource('registrations', RegistrationController::class);


        Route::get('/file-display/{path}', function ($path) {
            // Vérification sur le disk 'public' du tenant
            if (!Storage::disk('public')->exists($path)) {
                abort(404);
            }    
            return Storage::disk('public')->response($path);
        })->where('path', '.*')->name('file.display');

        Route::get('registrations/{id}/pdf', [RegistrationController::class, 'generatePdf'])->name('registrations.pdf');
    });


    Route::prefix('evaluation')->name('evaluation.')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Gestion des Évaluations (Devoirs, TP, Examens)
        |--------------------------------------------------------------------------
        */
        Route::resource('evaluations', EvaluationController::class);
        
        // Publication / Masquage des notes d'une évaluation
        Route::patch('evaluations/{evaluation}/toggle-publish', [EvaluationController::class, 'togglePublish'])
            ->name('evaluations.toggle-publish');

        /*
        |--------------------------------------------------------------------------
        | Saisie & Gestion des Notes (Grades)
        |--------------------------------------------------------------------------
        */
        // Grille de saisie rapide AJAX par évaluation
        Route::get('evaluations/{evaluation}/grades/grid', [GradeController::class, 'grid'])
            ->name('evaluations.grades.grid');
        
        // Sauvegarde en lot des notes saisies dans la grille
        Route::post('evaluations/{evaluation}/grades/batch-store', [GradeController::class, 'batchStore'])
            ->name('evaluations.grades.batch-store');

        // CRUD individuel des notes (optionnel)
        Route::resource('grades', GradeController::class)->only(['store', 'update', 'destroy']);
        
        Route::get('evaluations/{evaluation}/print-pdf', [EvaluationController::class, 'printPdf'])
            ->name('evaluations.print-pdf');


        Route::get('/ajax/evaluations/classes', [EvaluationController::class, 'getClassesBySchool'])->name('ajax.evaluations.classes');
        Route::get('/ajax/evaluations/subjects', [EvaluationController::class, 'getSubjectsByClass'])->name('ajax.evaluations.subjects');

        /*
        |--------------------------------------------------------------------------
        | Relevés de Notes, Bulletins & PV de Délibération (LMD & Classique)
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportCardController::class, 'index'])->name('index');
            
            // Routes AJAX pour les filtres dynamiques
            Route::get('ajax/school-options', [ReportCardController::class, 'getSchoolOptions'])->name('ajax.school-options');
            Route::get('ajax/year-periods', [ReportCardController::class, 'getPeriodsByYear'])->name('ajax.year-periods');
        
            // Impression PDF
            Route::get('bulletin/{registration}/{academicPeriod}', [ReportCardController::class, 'generateBulletin'])->name('bulletin.pdf');
            Route::get('releve-lmd/{registration}/{academicPeriod}', [ReportCardController::class, 'generateReleveLmd'])->name('releve-lmd.pdf');
            Route::get('pv-deliberation/{schoolClass}/{academicPeriod}', [ReportCardController::class, 'generatePvDeliberation'])->name('pv-deliberation.pdf');
        });

        Route::prefix('teacher/grades')->name('teacher.grades.')->group(function () {
            // Affichage de la synthèse et du formulaire
            Route::get('/subject-summary', [TeacherGradeController::class, 'index'])->name('subject-summary');
            
            // Action de calcul et d'archivage
            Route::post('/calculate-archive', [TeacherGradeController::class, 'calculateAndArchive'])->name('calculate-archive');

            Route::get('/evaluation/teacher/get-school-details', [TeacherGradeController::class, 'getSchoolDetails'])->name('get-school-details');

            Route::get('/evaluation/teacher/grades/get-class-subjects', [TeacherGradeController::class, 'getClassSubjects'])->name('get-class-subjects');
        });

    });
    
    Route::prefix('attendance')->name('attendance.')->middleware(['auth'])->group(function () {
    
        // Page de sélection (Menu principal)
        Route::get('/', [AttendanceController::class, 'index'])->name('index');

        // Affichage de la feuille de présence pour un créneau (Schedule)
        Route::get('take/{schedule}', [AttendanceController::class, 'takeAttendance'])->name('take');
        
        // Enregistrement / Mise à jour du pointage
        Route::post('store/{schedule}', [AttendanceController::class, 'storeAttendance'])->name('store');

        // Route d'impression PDF de la fiche de présence
        Route::get('print/{schedule}', [AttendanceController::class, 'printAttendance'])->name('print');

        // Kiosque de Pointage Personnel (Securisé par IP/MAC)
        Route::prefix('staff-attendance')->name('staff-attendance.')->group(function () {
            Route::get('kiosk', [StaffAttendanceController::class, 'kioskView'])->name('kiosk');
            Route::post('kiosk/scan', [StaffAttendanceController::class, 'handleKioskScan'])->name('kiosk.scan');
        });

        // Administration & Configuration des Machines de pointage
        Route::prefix('attendance/terminals')->name('attendance.terminals.')->middleware(['auth'])->group(function () {
            Route::get('/', [AttendanceTerminalController::class, 'index'])->name('index');
            Route::post('/store', [AttendanceTerminalController::class, 'store'])->name('store');
            Route::delete('/{terminal}', [AttendanceTerminalController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('teacher/attendance')->name('teacher.attendance.')->middleware(['auth'])->group(function () {
            // Espace de pointage personnel de l'enseignant connecté
            Route::get('/', [TeacherScheduleAttendanceController::class, 'dashboard'])->name('dashboard');
            
            // Soumission du pointage (Arrivée / Départ)
            Route::post('/submit/{schedule}', [TeacherScheduleAttendanceController::class, 'submitAttendance'])->name('submit');
        });
       /* Route::prefix('admin/payroll')->name('teacher.payroll.')->middleware(['auth'])->group(function () {
            Route::get('/', [TeacherPayrollController::class, 'index'])->name('dashboard');
            Route::get('/export/pdf', [TeacherPayrollController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export/excel', [TeacherPayrollController::class, 'exportExcel'])->name('export.excel');

            // Route pour la fiche individuelle
            Route::get('/export/individual/{staffId}', [TeacherPayrollController::class, 'exportIndividualPdf'])->name('export.individual');

            // Route d'envoi par e-mail
            Route::post('/send-email/{staffId}', [TeacherPayrollController::class, 'sendEmail'])->name('send.email');

        });*/
        
    });

    Route::prefix('admin/logbook')->name('school.logbook.')->middleware(['auth'])->group(function () {
        
        Route::get('/', [TeacherAttendanceController::class, 'index'])->name('index');
        Route::get('/create', [TeacherAttendanceController::class, 'create'])->name('create');
        Route::post('/', [TeacherAttendanceController::class, 'store'])->name('store');
        
        // Visas Administratifs (Individuel & Groupé)
        Route::post('/{attendance}/validate', [TeacherAttendanceController::class, 'validateEntry'])->name('validate');
        Route::post('/bulk-validate', [TeacherAttendanceController::class, 'bulkValidate'])->name('bulk-validate');
        Route::post('/logbook/{id}/unvalidate', [TeacherAttendanceController::class, 'unvalidate'])->name('unvalidate');
        Route::get('/school/logbook/export-pdf', [TeacherAttendanceController::class, 'exportPdf'])->name('export-pdf');
     
    });    

    Route::middleware(['auth'])->prefix('admin/accounting')->name('school.accounting.')->group(function () {
    
        // Comptes étudiants
        Route::get('/accounts', [PaymentController::class, 'index'])->name('index');

        Route::get('/accounts/{id}', [PaymentController::class, 'show'])->name('show');
        
        // Encaissements
        Route::post('/accounts/{id}/pay', [PaymentController::class, 'store'])->name('pay');

        Route::post('/accounts/{id}/payments', [PaymentController::class, 'store'])->name('store');
        
        // Impression du reçu PDF
        Route::get('/payments/{id}/receipt', [PaymentController::class, 'receiptPdf'])->name('receipt.pdf');

        // Fiche récapitulative globale du compte étudiant
        Route::get('/accounts/{id}/statement-pdf', [PaymentController::class, 'accountStatementPdf'])->name('account.statement.pdf');
            
        // Mises à jour & Frais spécifiques (Attention : Suppression des doublons de préfixe '/accounting')
        Route::put('/fee-accounts/{id}', [PaymentController::class, 'updateAccount'])->name('update-account');

        Route::post('/account/{id}/add-extra-fee', [PaymentController::class, 'addExtraFee'])->name('add-extra-fee');

        // Exercices Comptables
        Route::resource('fiscal-years', FiscalYearController::class);
        
        // Plan Comptable General
        Route::resource('chart-of-accounts', ChartOfAccountController::class);
        
        // Journaux Comptables
        Route::resource('journals', JournalController::class);
        
        // Pieces & Ecritures Comptables
        Route::resource('entries', AccountingEntryController::class);
        
        Route::get('/entries/{entry}/export-pdf', [AccountingEntryController::class, 'pdf'])->name('entries.export-pdf');

        // Balance Générale des Comptes
        Route::get('/general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
        Route::get('/general-ledger/export-pdf', [GeneralLedgerController::class, 'exportPdf'])->name('general-ledger.export-pdf');
        Route::get('/general-ledger/export-excel', [GeneralLedgerController::class, 'exportExcel'])->name('general-ledger.export-excel');

        // 1. Grand Livre
        Route::get('/ledger', [AccountLedgerController::class, 'index'])->name('ledger.index');

        // 2. Compte de Résultat
        Route::get('/income-statement', [IncomeStatementController::class, 'index'])->name('income-statement.index');

        // 3. Bilan
        Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet.index');
    
        // 4. Rapprochement Bancaire
        Route::get('/bank-reconciliation', [BankReconciliationController::class, 'index'])->name('bank-reconciliation.index');

        Route::post('bank-reconciliation/reconcile', [BankReconciliationController::class, 'reconcile'])->name('bank-reconciliation.reconcile');

        // Grand Livre PDF
        Route::get('/ledger/export-pdf', [AccountLedgerController::class, 'exportPdf'])->name('ledger.export-pdf');

        // Compte de Résultat PDF
        Route::get('/income-statement/export-pdf', [IncomeStatementController::class, 'exportPdf'])->name('income-statement.export-pdf');

        // Bilan PDF
        Route::get('/balance-sheet/export-pdf', [BalanceSheetController::class, 'exportPdf'])->name('balance-sheet.export-pdf');

        // Rapprochement Bancaire PDF
        Route::get('/bank-reconciliation/export-pdf', [BankReconciliationController::class, 'exportPdf'])->name('bank-reconciliation.export-pdf');

        //
        Route::get('/general-ledger/export-pdf', [GeneralLedgerController::class, 'exportPdf'])->name('general-ledger.export-pdf');

        //Paie des enseignants vacataire
        Route::middleware(['auth'])->prefix('payroll')->group(function () {
            Route::get('/teacher-rates', [TeacherSubjectRateController::class, 'index'])->name('teacher-rates.index');
             Route::get('/teacher-rates/{id}/edit', [TeacherSubjectRateController::class, 'edit'])->name('teacher-rates.edit');
            Route::put('/teacher-rates/{id}', [TeacherSubjectRateController::class, 'update'])->name('teacher-rates.update');
            Route::get('/teacher-rates/{id}', [TeacherSubjectRateController::class, 'show'])->name('teacher-rates.show');
            Route::post('/teacher-rates/{id}/reset', [TeacherSubjectRateController::class, 'resetToDefault'])->name('teacher-rates.reset');

            Route::get('/school/accounting/payrolls/staff-by-school/{schoolId}', function ($schoolId) {
                $staff = App\Modules\School\Models\Staff::whereHas('contracts', function ($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })
                    ->with('personne')
                    ->get()
                    ->map(function ($item) {
                        $nomComplet = trim(($item->personne->nom ?? '') . ' ' . ($item->personne->prenoms ?? ''));
                        return [
                            'id' => $item->id,
                            'name' => !empty($nomComplet) ? $nomComplet : ($item->personne->nom_complet ?? 'Employé N°' . $item->id),
                        ];
                    });
            
                return response()->json($staff);
            })->name('staff-by-school');
        
        //Paie du personnel et des enseignants permanent        
            Route::get('/', [PayrollController::class, 'index'])->name('payrolls.index');
            Route::get('/create', [PayrollController::class, 'create'])->name('payrolls.create');
            Route::post('/', [PayrollController::class, 'store'])->name('payrolls.store');
            Route::get('/{id}', [PayrollController::class, 'show'])->name('payrolls.show');
            Route::delete('/{id}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');
            Route::get('/{id}/pdf', [PayrollController::class, 'downloadPdf'])->name('payrolls.pdf');
        });

        Route::prefix('accounting/reports')->name('reports.')->group(function () {
            // Routes existantes pour l'affichage web...
            Route::get('student-statement', [FinancialReportController::class, 'studentStatement'])->name('student-statement');
            Route::get('cash-closure', [FinancialReportController::class, 'cashClosure'])->name('cash-closure');
            Route::get('exam-clearance', [FinancialReportController::class, 'examClearance'])->name('exam-clearance');

            // === ROUTES PDF À AJOUTER ===
            Route::get('student-statement/pdf', [FinancialReportController::class, 'exportStudentAccountPdf'])->name('student-statement-pdf');
            Route::get('cash-closure/pdf', [FinancialReportController::class, 'exportDailyCashPvPdf'])->name('cash-closure-pdf');
            Route::get('exam-clearance/pdf', [FinancialReportController::class, 'examClearancePdf'])->name('exam-clearance-pdf');
        });


        // Configuration Générale
        Route::get('/payroll-settings', [PayrollSettingController::class, 'index'])->name('payroll-settings.index');
        Route::put('/payroll-settings/{id}', [PayrollSettingController::class, 'update'])->name('payroll-settings.update');

        // CRUD des Règles Dynamiques (Taxes, CNPS, Mutuelles)
        Route::post('/payroll-tax-rules', [PayrollTaxRuleController::class, 'store'])->name('payroll-tax-rules.store');
        Route::put('/payroll-tax-rules/{id}', [PayrollTaxRuleController::class, 'update'])->name('payroll-tax-rules.update');
        Route::delete('/payroll-tax-rules/{id}', [PayrollTaxRuleController::class, 'destroy'])->name('payroll-tax-rules.destroy');
        

    });

    Route::prefix('admin/accounting')->name('school.fee-plans.')->group(function () {
        Route::get('/fee-plans', [FeePlanController::class, 'index'])->name('index');
        Route::get('/fee-plans/create', [FeePlanController::class, 'create'])->name('create');
        Route::post('/fee-plans', [FeePlanController::class, 'store'])->name('store');
        Route::get('/fee-plans/{feePlan}', [FeePlanController::class, 'show'])->name('show');
        Route::get('/fee-plans/{feePlan}/edit', [FeePlanController::class, 'edit'])->name('edit');
        Route::put('/fee-plans/{feePlan}', [FeePlanController::class, 'update'])->name('update');
        Route::delete('/fee-plans/{feePlan}', [FeePlanController::class, 'destroy'])->name('destroy');

        Route::post('fee-plans/{feePlan}/sync', [FeePlanController::class, 'syncStudents'])->name('sync');

        // Route manquante à ajouter :
        Route::patch('/fee-plans/{feePlan}/toggle-active', [FeePlanController::class, 'toggleActive'])->name('toggle-active');
    });

    Route::prefix('admin/accounting/reports')->name('school.accounting.reports.')->middleware(['auth'])->group(function () {
        Route::get('/daily-cash', [FinancialReportController::class, 'dailyCashJournal'])->name('daily-cash');
        Route::get('/school/accounting/reports/export/pdf/daily-cash-pv', [FinancialReportController::class, 'exportPdfDailyCashPv'])->name('export.pdf.daily-cash-pv');
        Route::get('/overdue', [FinancialReportController::class, 'overdueAccounts'])->name('overdue');
        Route::get('/recovery-rate', [FinancialReportController::class, 'recoveryRate'])->name('recovery-rate');
        Route::get('/discounts', [FinancialReportController::class, 'discountsReport'])->name('discounts');
        Route::get('/cash-forecast', [FinancialReportController::class, 'cashForecastReport'])->name('cash-forecast');
        // Export PDF du Prévisionnel de Trésorerie
        Route::get('/accounting/reports/cash-forecast/pdf', [FinancialReportController::class, 'cashForecastPdf'])->name('cash_forecast.pdf');
        // Route pour l'export PDF du rapport d'exonérations
        Route::get('/accounting/reports/discounts/pdf', [FinancialReportController::class, 'discountsReportPdf'])->name('discounts.pdf');
        // Export PDF du Taux de Recouvrement Global
        Route::get('/accounting/reports/recovery-rate/pdf', [FinancialReportController::class, 'recoveryRatePdf'])->name('recovery_rate.pdf');
        // Export PDF du rapport des impayés et créances
        Route::get('/accounting/reports/overdue/pdf', [FinancialReportController::class, 'overdueAccountsPdf'])->name('overdue.pdf');
    });

    Route::prefix('admin/accounting/reports/export')->name('school.accounting.reports.export.')->group(function () {
        // PDF Exports
        Route::get('/pdf/receipt/{payment}', [FinancialReportController::class, 'exportPaymentReceiptPdf'])->name('pdf.receipt');
        Route::get('/pdf/student-account/{account}', [FinancialReportController::class, 'exportStudentAccountPdf'])->name('pdf.student-account');
        Route::get('/pdf/daily-cash-pv', [FinancialReportController::class, 'exportDailyCashPvPdf'])->name('pdf.daily-cash-pv');
    
        // Excel Exports
        Route::get('/excel/overdue', [FinancialReportController::class, 'exportOverdueExcel'])->name('excel.overdue');
        Route::get('/excel/payments-period', [FinancialReportController::class, 'exportPaymentsPeriodExcel'])->name('excel.payments-period');
        Route::get('/excel/recovery-rate', [FinancialReportController::class, 'exportRecoveryRateExcel'])->name('excel.recovery-rate');
    });


    Route::prefix('reporting')->name('reporting.')->middleware(['auth'])->group(function () {
        
        Route::prefix('teacher/documents')->name('teacher.documents.')->middleware(['auth'])->group(function () {
            // 1. Liste de classe par filtre
            Route::get('/class-list', [TeacherDocumentController::class, 'classList'])->name('class-list');
            Route::get('/class-list/{classId}/print', [TeacherDocumentController::class, 'printClassList'])->name('class-list.print');
        
            // 2. Consultation et Téléchargement des bulletins
            Route::get('/payslips', [TeacherDocumentController::class, 'payslips'])->name('payslips');
            Route::get('/payslips/permanent/{id}/download', [TeacherDocumentController::class, 'downloadPermanentPayslip'])->name('payslips.permanent.download');
            Route::get('/payslips/vacataire/{year}/{month}/download', [TeacherDocumentController::class, 'downloadVacatairePayslip'])->name('payslips.vacataire.download');

            // Consultation des notes
            Route::get('/evaluation-grades', [TeacherDocumentController::class, 'evaluationGrades'])->name('evaluation-grades');
            // Export PDF
            Route::get('/evaluation-grades/{evaluationId}/print', [TeacherDocumentController::class, 'printEvaluationGrades'])->name('evaluation-grades.print');
        });
     
    });    


    // -- Gestion des Accès & Comptes ------------------------------------------ 
    Route::prefix('access')->name('access.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('personnes', PersonneController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });
});