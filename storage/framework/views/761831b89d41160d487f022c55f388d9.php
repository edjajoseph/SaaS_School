<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">        

        <!-- 1. GÉNÉRAL -->
        <?php if (app('laratrust')->hasPermission('read-dashboard|read-general')) : ?>
        <div class="pcoded-navigation-label">Général</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php echo e(request()->routeIs('school.dashboard') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('school.dashboard')); ?>">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>T</b></span>
                    <span class="pcoded-mtext">Tableau de bord</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 2. RÉFÉRENTIELS & PARAMÈTRES -->
        <?php if (app('laratrust')->hasPermission('read-settings-academic|read-settings-rh|read-settings-local')) : ?>
        <div class="pcoded-navigation-label">Référentiel & Settings</div>
        <ul class="pcoded-item pcoded-left-item">
            
            <!-- Référentiels Académiques -->
            <?php if (app('laratrust')->hasPermission('read-settings-academic')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('settings.academic.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-settings"></i><b>A</b></span>
                    <span class="pcoded-mtext">Académiques</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-degrees')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.degrees.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.degrees.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Diplômes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-period-types')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.period-types.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.period-types.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Types de découpage</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-cycles')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.cycles.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.cycles.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Cycles</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-levels')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.levels.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.levels.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Niveaux d'études</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-series')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.series.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.series.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Séries / Filières</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-subjects')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.academic.subjects.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.academic.subjects.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Matières</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <!-- Référentiels RH -->
            <?php if (app('laratrust')->hasPermission('read-settings-rh')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('settings.rh.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>R</b></span>
                    <span class="pcoded-mtext">RH</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-staff-roles')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.rh.staff-roles.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.rh.staff-roles.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Fonctions</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-specialities')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.rh.specialities.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.rh.specialities.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Spécialités</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-document-types')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.rh.document_types.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.rh.document_types.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Types de document</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <!-- Référentiels Localisation -->
            <?php if (app('laratrust')->hasPermission('read-settings-local')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('settings.local.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-world"></i><b>L</b></span>
                    <span class="pcoded-mtext">Localisation</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-countries')) : ?>
                    <li class="<?php echo e(request()->routeIs('settings.local.countries.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('settings.local.countries.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Pays</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 3. ORGANISATION & OFFRES ACADÉMIQUES -->
        <?php if (app('laratrust')->hasPermission('read-organisation|read-academic-offers')) : ?>
        <div class="pcoded-navigation-label">Organisation & Offres Académiques</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-organisation')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('organisation.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>O</b></span>
                    <span class="pcoded-mtext">Organisation</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-schools')) : ?>
                    <li class="<?php echo e(request()->routeIs('organisation.schools.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('organisation.schools.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Établissements</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-academic-years')) : ?>
                    <li class="<?php echo e(request()->routeIs('organisation.academic-years.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('organisation.academic-years.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Années scolaires</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-periods')) : ?>
                    <li class="<?php echo e(request()->routeIs('organisation.periods.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('organisation.periods.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Périodes Académiques</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-classes')) : ?>
                    <li class="<?php echo e(request()->routeIs('organisation.classes.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('organisation.classes.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Classes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-academic-offers')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('academic.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-book"></i><b>O</b></span>
                    <span class="pcoded-mtext">Offres Académiques</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-school-series')) : ?>
                    <li class="<?php echo e(request()->routeIs('academic.school-series.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('academic.school-series.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Filière / Séries</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-teaching-units')) : ?>
                    <li class="<?php echo e(request()->routeIs('academic.teaching-units.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('academic.teaching-units.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Unité d'Enseignement</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-school-subjects')) : ?>
                    <li class="<?php echo e(request()->routeIs('academic.school-subjects.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('academic.school-subjects.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">ECUE / Matière / Module</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-evaluation-types')) : ?>
                    <li class="<?php echo e(request()->routeIs('academic.evaluation-types.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('academic.evaluation-types.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Type d'évaluation</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 4. PLANNING, RESSOURCES HUMAINES & PAIE -->
        <?php if (app('laratrust')->hasPermission('read-staff|read-rooms|read-schedules|read-payroll')) : ?>
        <div class="pcoded-navigation-label">Planning & Ressources Humaines</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-staff')) : ?>
            <li class="<?php echo e(request()->routeIs('schedule.staff.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schedule.staff.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-id-badge"></i><b>P</b></span>
                    <span class="pcoded-mtext">PAT & Enseignants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-rooms')) : ?>
            <li class="<?php echo e(request()->routeIs('schedule.rooms.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schedule.rooms.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-location-pin"></i><b>S</b></span>
                    <span class="pcoded-mtext">Salles de cours</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-schedules')) : ?>
            <li class="<?php echo e(request()->routeIs('schedule.schedules.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schedule.schedules.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-calendar"></i><b>E</b></span>
                    <span class="pcoded-mtext">Emplois du temps</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-leave-requests')) : ?>
            <li class="<?php echo e(request()->routeIs('schedule.leave-requests.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schedule.leave-requests.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-time"></i><b>A</b></span>
                    <span class="pcoded-mtext">Autorisation d'absence</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-payroll')) : ?>             
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('school.accounting.payrolls.*', 'school.accounting.teacher-rates.*', 'school.accounting.payroll-settings.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-wallet"></i><b>P</b></span>
                    <span class="pcoded-mtext">Paie du Personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="<?php echo e(request()->routeIs('school.accounting.payrolls.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.payrolls.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bulletin de paie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li> 
                    <li class="<?php echo e(request()->routeIs('school.accounting.teacher-rates.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.teacher-rates.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Taux et volume horaire de vacation</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.payroll-settings.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.payroll-settings.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Configuration de la paie & Cotisations</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?> 
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 5. INSCRIPTIONS ET SCOLARITÉS -->
        <?php if (app('laratrust')->hasPermission('read-students|read-registrations')) : ?>
        <div class="pcoded-navigation-label">Inscriptions et Scolarités</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-students')) : ?>
            <li class="<?php echo e(request()->routeIs('schooling.students.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schooling.students.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>É</b></span>
                    <span class="pcoded-mtext">Étudiants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
            <?php if (app('laratrust')->hasPermission('read-registrations')) : ?>
            <li class="<?php echo e(request()->routeIs('schooling.registrations.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('schooling.registrations.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-clipboard"></i><b>I</b></span>
                    <span class="pcoded-mtext">Inscriptions</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 6. ÉVALUATION & BULLETINS -->
        <?php if (app('laratrust')->hasPermission('read-evaluations|read-reports')) : ?>
        <div class="pcoded-navigation-label">Évaluation & Bulletins</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-evaluations')) : ?>
            <li class="<?php echo e(request()->routeIs('evaluation.evaluations.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('evaluation.evaluations.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-pencil-alt"></i><b>É</b></span>
                    <span class="pcoded-mtext">Évaluations</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            
            <li class="<?php echo e(request()->routeIs('evaluation.teacher.grades.subject-summary.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('evaluation.teacher.grades.subject-summary')); ?>">
                    <span class="pcoded-micon"><i class="ti-avarage-alt"></i><b>É</b></span>
                    <span class="pcoded-mtext">Moyenne de classe</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
            <?php if (app('laratrust')->hasPermission('read-reports')) : ?>
            <li class="<?php echo e(request()->routeIs('evaluation.reports.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('evaluation.reports.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>B</b></span>
                    <span class="pcoded-mtext">Bulletins & PV</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 7. SUIVI DES PRÉSENCES --> 
        <?php if (app('laratrust')->hasPermission('read-attendance-student|read-attendance-teacher|read-attendance-staff|read-terminals')) : ?>
        <div class="pcoded-navigation-label">Suivi des Présences & Assiduité</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-attendance-student')) : ?>
            <li class="<?php echo e(request()->routeIs('attendance.index', 'attendance.attendance.*', 'attendance.students.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('attendance.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-check-box"></i><b>E</b></span>
                    <span class="pcoded-mtext">Pointage Étudiant</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
            <?php if (app('laratrust')->hasPermission('read-attendance-teacher')) : ?>
            <li class="<?php echo e(request()->routeIs('attendance.teacher.attendance.dashboard', 'attendance.teacher.attendance.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('attendance.teacher.attendance.dashboard')); ?>">
                    <span class="pcoded-micon"><i class="ti-check-box"></i><b>P</b></span>
                    <span class="pcoded-mtext">Pointage Enseignants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
            <?php if (app('laratrust')->hasPermission('read-attendance-staff')) : ?>
            <li class="<?php echo e(request()->routeIs('attendance.staff-attendance.kiosk', 'attendance.staff-attendance.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('attendance.staff-attendance.kiosk')); ?>">
                    <span class="pcoded-micon"><i class="ti-desktop"></i><b>K</b></span>
                    <span class="pcoded-mtext">Pointage Personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>                  
            <?php if (app('laratrust')->hasPermission('read-terminals')) : ?>
            <li class="<?php echo e(request()->routeIs('attendance.attendance.terminals.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('attendance.attendance.terminals.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-hardware-chip"></i><b>C</b></span>
                    <span class="pcoded-mtext">Configuration bornes</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 8. CAHIER DE TEXTES -->
        <?php if (app('laratrust')->hasPermission('create-logbook|read-logbook')) : ?>
        <div class="pcoded-navigation-label">Cahier de Textes & Progression</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('create-logbook')) : ?>
            <li class="<?php echo e(request()->routeIs('school.logbook.create*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('school.logbook.create')); ?>">
                    <span class="pcoded-micon"><i class="ti-plus"></i><b>S</b></span>
                    <span class="pcoded-mtext">Séance de cours</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
            <?php if (app('laratrust')->hasPermission('read-logbook')) : ?>
            <li class="<?php echo e(request()->routeIs('school.logbook.index*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('school.logbook.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-agenda"></i><b>C</b></span>
                    <span class="pcoded-mtext">Cahier de Textes</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>
        
        <!-- 9. RECOUVREMENT & COMPTABILITÉ SCOLARITÉ -->
        <?php if (app('laratrust')->hasPermission('read-payments|read-fee-plans|read-financial-reports')) : ?>
        <div class="pcoded-navigation-label">Recouvrement & Scolarités</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-payments')) : ?>
            <li class="<?php echo e((request()->routeIs('school.accounting.*') && !request()->routeIs('school.accounting.reports.*') && !request()->routeIs('school.accounting.fiscal-years.*') && !request()->routeIs('school.accounting.chart-of-accounts.*') && !request()->routeIs('school.accounting.journals.*') && !request()->routeIs('school.accounting.entries.*') && !request()->routeIs('school.accounting.general-ledger.*') && !request()->routeIs('school.accounting.ledger.*') && !request()->routeIs('school.accounting.income-statement.*') && !request()->routeIs('school.accounting.balance-sheet.*') && !request()->routeIs('school.accounting.bank-reconciliation.*') && !request()->routeIs('school.accounting.payrolls.*') && !request()->routeIs('school.accounting.teacher-rates.*') && !request()->routeIs('school.accounting.payroll-settings.*')) ? 'active' : ''); ?>">
                <a href="<?php echo e(route('school.accounting.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-money"></i><b>P</b></span>
                    <span class="pcoded-mtext">Paiements</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-fee-plans')) : ?>
            <li class="<?php echo e(request()->routeIs('school.fee-plans*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('school.fee-plans.index')); ?>">
                    <span class="pcoded-micon"><i class="ti-receipt"></i><b>F</b></span>
                    <span class="pcoded-mtext">Plans tarifaires</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-financial-reports')) : ?>
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('school.accounting.reports.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-stats-up"></i><b>R</b></span>
                    <span class="pcoded-mtext">Rapports & États</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.daily-cash*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.daily-cash')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Journal de Caisse</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.overdue*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.overdue')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Impayés & Créances</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.recovery-rate*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.recovery-rate')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Taux de Recouvrement</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.discounts*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.discounts')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Exonérations & Remises</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.cash-forecast*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.cash-forecast')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Prévision de Trésorerie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.student-statement*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.student-statement')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Fiche / Relevé Étudiant</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.cash-closure*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.cash-closure')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Arrêté de Caisse Journalier</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.reports.exam-clearance*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.reports.exam-clearance')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Autorisations aux Examens</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 10. COMPTABILITÉ GÉNÉRALE -->
        <?php if (app('laratrust')->hasPermission('read-accounting-entries|read-accounting-statements')) : ?>
        <div class="pcoded-navigation-label">Comptabilité Générale</div>
        <ul class="pcoded-item pcoded-left-item">
            <?php if (app('laratrust')->hasPermission('read-accounting-entries')) : ?>
            <li class="pcoded-hasmenu <?php echo e((request()->routeIs('school.accounting.fiscal-years.*') || request()->routeIs('school.accounting.chart-of-accounts.*') || request()->routeIs('school.accounting.journals.*') || request()->routeIs('school.accounting.entries.*')) ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-book"></i><b>É</b></span>
                    <span class="pcoded-mtext">Écritures comptables</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="<?php echo e(request()->routeIs('school.accounting.fiscal-years.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.fiscal-years.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Exercices Comptables</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.chart-of-accounts.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.chart-of-accounts.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Plan Comptable</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.journals.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.journals.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Journaux</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.entries.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.entries.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Saisies & Écritures</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>

            <?php if (app('laratrust')->hasPermission('read-accounting-statements')) : ?>
            <li class="pcoded-hasmenu <?php echo e((request()->routeIs('school.accounting.general-ledger.*') || request()->routeIs('school.accounting.ledger.*') || request()->routeIs('school.accounting.income-statement.*') || request()->routeIs('school.accounting.balance-sheet.*') || request()->routeIs('school.accounting.bank-reconciliation.*')) ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-files"></i><b>P</b></span>
                    <span class="pcoded-mtext">Pièces & États</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="<?php echo e(request()->routeIs('school.accounting.general-ledger.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.general-ledger.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Balance Générale</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.ledger.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.ledger.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Grand Livre</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.income-statement.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.income-statement.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Compte de Résultat</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.balance-sheet.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.balance-sheet.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bilan</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('school.accounting.bank-reconciliation.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('school.accounting.bank-reconciliation.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Rapprochement Bancaire</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; // app('laratrust')->permission ?>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 11. REPORTING -->
        <?php if (app('laratrust')->hasPermission('read-students|read-registrations')) : ?>
        <div class="pcoded-navigation-label">Reporting & Documents</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('reporting.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-bar-chart-alt"></i><b>R</b></span>
                    <span class="pcoded-mtext">Reporting</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="<?php echo e(request()->routeIs('reporting.teacher.documents.class-list*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reporting.teacher.documents.class-list')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Liste de classe</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('reporting.teacher.documents.evaluation-grades*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reporting.teacher.documents.evaluation-grades')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Liste des notes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('reporting.teacher.documents.payslips*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('reporting.teacher.documents.payslips')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bulletin de paie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

        <!-- 12. SÉCURITÉ ET ACCÈS -->
        <?php if (app('laratrust')->hasPermission('read-users|read-roles|read-permissions')) : ?>
        <div class="pcoded-navigation-label">Sécurité & Habilitations</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu <?php echo e(request()->routeIs('access.*') ? 'active pcoded-trigger' : ''); ?>">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-lock"></i><b>S</b></span>
                    <span class="pcoded-mtext">Sécurité & Accès</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <?php if (app('laratrust')->hasPermission('read-users')) : ?>
                    <li class="<?php echo e(request()->routeIs('access.users.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('access.users.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Utilisateurs</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-roles')) : ?>
                    <li class="<?php echo e(request()->routeIs('access.roles.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('access.roles.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Rôles</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                    <?php if (app('laratrust')->hasPermission('read-permissions')) : ?>
                    <li class="<?php echo e(request()->routeIs('access.permissions.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('access.permissions.index')); ?>">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Permissions</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <?php endif; // app('laratrust')->permission ?>
                </ul>
            </li>
        </ul>
        <?php endif; // app('laratrust')->permission ?>

    </div>
</nav><?php /**PATH C:\laragon3\www\saas-hotel\app\Modules/School/Views/layouts/navbars/sidebar2.blade.php ENDPATH**/ ?>