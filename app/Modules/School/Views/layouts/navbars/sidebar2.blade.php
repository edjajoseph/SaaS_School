<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">        

        <!-- 1. GÉNÉRAL -->
        @permission('read-dashboard|read-general')
        <div class="pcoded-navigation-label">Général</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="{{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                <a href="{{ route('school.dashboard', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>T</b></span>
                    <span class="pcoded-mtext">Tableau de bord</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        @endpermission

        <!-- 2. RÉFÉRENTIELS & PARAMÈTRES -->
        @permission('read-settings-academic|read-settings-rh|read-settings-local')
        <div class="pcoded-navigation-label">Référentiel & Settings</div>
        <ul class="pcoded-item pcoded-left-item">
            
            <!-- Référentiels Académiques -->
            @permission('read-settings-academic')
            <li class="pcoded-hasmenu {{ request()->routeIs('settings.academic.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-settings"></i><b>A</b></span>
                    <span class="pcoded-mtext">Académiques</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-degrees')
                    <li class="{{ request()->routeIs('settings.academic.degrees.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.academic.degrees.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Diplômes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-period-types')
                    <li class="{{ request()->routeIs('settings.academic.period-types.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.academic.period-types.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Types de découpage</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-cycles')
                    <li class="{{ request()->routeIs('settings.academic.cycles.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.academic.cycles.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Cycles</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-levels')
                    <li class="{{ request()->routeIs('settings.academic.levels.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.academic.levels.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Niveaux d'études</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-series')
                    <li class="{{ request()->routeIs('settings.academic.series.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.academic.series.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Séries / Filières</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-subjects')
                    <li class="{{ request()->routeIs('settings.academic.subjects.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.academic.subjects.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Matières</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
            @endpermission

            <!-- Référentiels RH -->
            @permission('read-settings-rh')
            <li class="pcoded-hasmenu {{ request()->routeIs('settings.rh.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>R</b></span>
                    <span class="pcoded-mtext">RH</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-staff-roles')
                    <li class="{{ request()->routeIs('settings.rh.staff-roles.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.rh.staff-roles.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Fonctions</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-specialities')
                    <li class="{{ request()->routeIs('settings.rh.specialities.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.rh.specialities.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Spécialités</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-document-types')
                    <li class="{{ request()->routeIs('settings.rh.document_types.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.rh.document_types.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Types de document</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
            @endpermission

            <!-- Référentiels Localisation -->
            @permission('read-settings-local')
            <li class="pcoded-hasmenu {{ request()->routeIs('settings.local.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-world"></i><b>L</b></span>
                    <span class="pcoded-mtext">Localisation</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-countries')
                    <li class="{{ request()->routeIs('settings.local.countries.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.local.countries.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Pays</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 3. ORGANISATION & OFFRES ACADÉMIQUES -->
        @permission('read-organisation|read-academic-offers')
        <div class="pcoded-navigation-label">Organisation & Offres Académiques</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-organisation')
            <li class="pcoded-hasmenu {{ request()->routeIs('organisation.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>O</b></span>
                    <span class="pcoded-mtext">Organisation</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-schools')
                    <li class="{{ request()->routeIs('organisation.schools.*') ? 'active' : '' }}">
                        <a href="{{ route('organisation.schools.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Établissements</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-academic-years')
                    <li class="{{ request()->routeIs('organisation.academic-years.*') ? 'active' : '' }}">
                        <a href="{{ route('organisation.academic-years.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Années scolaires</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-periods')
                    <li class="{{ request()->routeIs('organisation.periods.*') ? 'active' : '' }}">
                        <a href="{{ route('organisation.periods.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Périodes Académiques</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-classes')
                    <li class="{{ request()->routeIs('organisation.classes.*') ? 'active' : '' }}">
                        <a href="{{ route('organisation.classes.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Classes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
            @endpermission

            @permission('read-academic-offers')
            <li class="pcoded-hasmenu {{ request()->routeIs('academic.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-book"></i><b>O</b></span>
                    <span class="pcoded-mtext">Offres Académiques</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-school-series')
                    <li class="{{ request()->routeIs('academic.school-series.*') ? 'active' : '' }}">
                        <a href="{{ route('academic.school-series.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Filière / Séries</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-teaching-units')
                    <li class="{{ request()->routeIs('academic.teaching-units.*') ? 'active' : '' }}">
                        <a href="{{ route('academic.teaching-units.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Unité d'Enseignement</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-school-subjects')
                    <li class="{{ request()->routeIs('academic.school-subjects.*') ? 'active' : '' }}">
                        <a href="{{ route('academic.school-subjects.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">ECUE / Matière / Module</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-evaluation-types')
                    <li class="{{ request()->routeIs('academic.evaluation-types.*') ? 'active' : '' }}">
                        <a href="{{ route('academic.evaluation-types.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Type d'évaluation</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 4. PLANNING, RESSOURCES HUMAINES & PAIE -->
        @permission('read-staff|read-rooms|read-schedules|read-payroll')
        <div class="pcoded-navigation-label">Planning & Ressources Humaines</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-staff')
            <li class="{{ request()->routeIs('schedule.staff.*') ? 'active' : '' }}">
                <a href="{{ route('schedule.staff.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-id-badge"></i><b>P</b></span>
                    <span class="pcoded-mtext">PAT & Enseignants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-rooms')
            <li class="{{ request()->routeIs('schedule.rooms.*') ? 'active' : '' }}">
                <a href="{{ route('schedule.rooms.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-location-pin"></i><b>S</b></span>
                    <span class="pcoded-mtext">Salles de cours</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-schedules')
            <li class="{{ request()->routeIs('schedule.schedules.*') ? 'active' : '' }}">
                <a href="{{ route('schedule.schedules.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-calendar"></i><b>E</b></span>
                    <span class="pcoded-mtext">Emplois du temps</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-leave-requests')
            <li class="{{ request()->routeIs('schedule.leave-requests.*') ? 'active' : '' }}">
                <a href="{{ route('schedule.leave-requests.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-time"></i><b>A</b></span>
                    <span class="pcoded-mtext">Autorisation d'absence</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-payroll')             
            <li class="pcoded-hasmenu {{ request()->routeIs('school.accounting.payrolls.*', 'school.accounting.teacher-rates.*', 'school.accounting.payroll-settings.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-wallet"></i><b>P</b></span>
                    <span class="pcoded-mtext">Paie du Personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('school.accounting.payrolls.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.payrolls.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bulletin de paie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li> 
                    <li class="{{ request()->routeIs('school.accounting.teacher-rates.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.teacher-rates.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Taux et volume horaire de vacation</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.payroll-settings.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.payroll-settings.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Configuration de la paie & Cotisations</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endpermission 
        </ul>
        @endpermission

        <!-- 5. INSCRIPTIONS ET SCOLARITÉS -->
        @permission('read-students|read-registrations')
        <div class="pcoded-navigation-label">Inscriptions et Scolarités</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-students')
            <li class="{{ request()->routeIs('schooling.students.*') ? 'active' : '' }}">
                <a href="{{ route('schooling.students.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>É</b></span>
                    <span class="pcoded-mtext">Étudiants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
            @permission('read-registrations')
            <li class="{{ request()->routeIs('schooling.registrations.*') ? 'active' : '' }}">
                <a href="{{ route('schooling.registrations.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-clipboard"></i><b>I</b></span>
                    <span class="pcoded-mtext">Inscriptions</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 6. ÉVALUATION & BULLETINS -->
        @permission('read-evaluations|read-reports')
        <div class="pcoded-navigation-label">Évaluation & Bulletins</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-evaluations')
            <li class="{{ request()->routeIs('evaluation.evaluations.*') ? 'active' : '' }}">
                <a href="{{ route('evaluation.evaluations.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-pencil-alt"></i><b>É</b></span>
                    <span class="pcoded-mtext">Évaluations</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            
            <li class="{{ request()->routeIs('evaluation.teacher.grades.subject-summary.*') ? 'active' : '' }}">
                <a href="{{ route('evaluation.teacher.grades.subject-summary', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-avarage-alt"></i><b>É</b></span>
                    <span class="pcoded-mtext">Moyenne de classe</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
            @permission('read-reports')
            <li class="{{ request()->routeIs('evaluation.reports.*') ? 'active' : '' }}">
                <a href="{{ route('evaluation.reports.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>B</b></span>
                    <span class="pcoded-mtext">Bulletins & PV</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 7. SUIVI DES PRÉSENCES --> 
        @permission('read-attendance-student|read-attendance-teacher|read-attendance-staff|read-terminals')
        <div class="pcoded-navigation-label">Suivi des Présences & Assiduité</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-attendance-student')
            <li class="{{ request()->routeIs('attendance.index', 'attendance.attendance.*', 'attendance.students.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-check-box"></i><b>E</b></span>
                    <span class="pcoded-mtext">Pointage Étudiant</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
            @permission('read-attendance-teacher')
            <li class="{{ request()->routeIs('attendance.teacher.attendance.dashboard', 'attendance.teacher.attendance.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.teacher.attendance.dashboard', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-check-box"></i><b>P</b></span>
                    <span class="pcoded-mtext">Pointage Enseignants</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
            @permission('read-attendance-staff')
            <li class="{{ request()->routeIs('attendance.staff-attendance.kiosk', 'attendance.staff-attendance.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.staff-attendance.kiosk', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-desktop"></i><b>K</b></span>
                    <span class="pcoded-mtext">Pointage Personnel</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission                  
            @permission('read-terminals')
            <li class="{{ request()->routeIs('attendance.attendance.terminals.*') ? 'active' : '' }}">
                <a href="{{ route('attendance.attendance.terminals.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-hardware-chip"></i><b>C</b></span>
                    <span class="pcoded-mtext">Configuration bornes</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 8. CAHIER DE TEXTES -->
        @permission('create-logbook|read-logbook')
        <div class="pcoded-navigation-label">Cahier de Textes & Progression</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('create-logbook')
            <li class="{{ request()->routeIs('school.logbook.create*') ? 'active' : '' }}">
                <a href="{{ route('school.logbook.create', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-plus"></i><b>S</b></span>
                    <span class="pcoded-mtext">Séance de cours</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
            @permission('read-logbook')
            <li class="{{ request()->routeIs('school.logbook.index*') ? 'active' : '' }}">
                <a href="{{ route('school.logbook.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-agenda"></i><b>C</b></span>
                    <span class="pcoded-mtext">Cahier de Textes</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission
        </ul>
        @endpermission
        
        <!-- 9. RECOUVREMENT & COMPTABILITÉ SCOLARITÉ -->
        @permission('read-payments|read-fee-plans|read-financial-reports')
        <div class="pcoded-navigation-label">Recouvrement & Scolarités</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-payments')
            <li class="{{ (request()->routeIs('school.accounting.*') && !request()->routeIs('school.accounting.reports.*') && !request()->routeIs('school.accounting.fiscal-years.*') && !request()->routeIs('school.accounting.chart-of-accounts.*') && !request()->routeIs('school.accounting.journals.*') && !request()->routeIs('school.accounting.entries.*') && !request()->routeIs('school.accounting.general-ledger.*') && !request()->routeIs('school.accounting.ledger.*') && !request()->routeIs('school.accounting.income-statement.*') && !request()->routeIs('school.accounting.balance-sheet.*') && !request()->routeIs('school.accounting.bank-reconciliation.*') && !request()->routeIs('school.accounting.payrolls.*') && !request()->routeIs('school.accounting.teacher-rates.*') && !request()->routeIs('school.accounting.payroll-settings.*')) ? 'active' : '' }}">
                <a href="{{ route('school.accounting.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-money"></i><b>P</b></span>
                    <span class="pcoded-mtext">Paiements</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-fee-plans')
            <li class="{{ request()->routeIs('school.fee-plans*') ? 'active' : '' }}">
                <a href="{{ route('school.fee-plans.index', ['tenant' => tenant()->getTenantKey()]) }}">
                    <span class="pcoded-micon"><i class="ti-receipt"></i><b>F</b></span>
                    <span class="pcoded-mtext">Plans tarifaires</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            @endpermission

            @permission('read-financial-reports')
            <li class="pcoded-hasmenu {{ request()->routeIs('school.accounting.reports.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-stats-up"></i><b>R</b></span>
                    <span class="pcoded-mtext">Rapports & États</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('school.accounting.reports.daily-cash*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.daily-cash', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Journal de Caisse</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.overdue*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.overdue', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Impayés & Créances</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.recovery-rate*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.recovery-rate', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Taux de Recouvrement</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.discounts*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.discounts', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Exonérations & Remises</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.cash-forecast*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.cash-forecast', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Prévision de Trésorerie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.student-statement*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.student-statement', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Fiche / Relevé Étudiant</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.cash-closure*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.cash-closure', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Arrêté de Caisse Journalier</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.reports.exam-clearance*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.reports.exam-clearance', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Autorisations aux Examens</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 10. COMPTABILITÉ GÉNÉRALE -->
        @permission('read-accounting-entries|read-accounting-statements')
        <div class="pcoded-navigation-label">Comptabilité Générale</div>
        <ul class="pcoded-item pcoded-left-item">
            @permission('read-accounting-entries')
            <li class="pcoded-hasmenu {{ (request()->routeIs('school.accounting.fiscal-years.*') || request()->routeIs('school.accounting.chart-of-accounts.*') || request()->routeIs('school.accounting.journals.*') || request()->routeIs('school.accounting.entries.*')) ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-book"></i><b>É</b></span>
                    <span class="pcoded-mtext">Écritures comptables</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('school.accounting.fiscal-years.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.fiscal-years.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Exercices Comptables</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.chart-of-accounts.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.chart-of-accounts.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Plan Comptable</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.journals.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.journals.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Journaux</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.entries.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.entries.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Saisies & Écritures</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endpermission

            @permission('read-accounting-statements')
            <li class="pcoded-hasmenu {{ (request()->routeIs('school.accounting.general-ledger.*') || request()->routeIs('school.accounting.ledger.*') || request()->routeIs('school.accounting.income-statement.*') || request()->routeIs('school.accounting.balance-sheet.*') || request()->routeIs('school.accounting.bank-reconciliation.*')) ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-files"></i><b>P</b></span>
                    <span class="pcoded-mtext">Pièces & États</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('school.accounting.general-ledger.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.general-ledger.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Balance Générale</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.ledger.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.ledger.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Grand Livre</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.income-statement.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.income-statement.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Compte de Résultat</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.balance-sheet.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.balance-sheet.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bilan</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('school.accounting.bank-reconciliation.*') ? 'active' : '' }}">
                        <a href="{{ route('school.accounting.bank-reconciliation.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Rapprochement Bancaire</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            @endpermission
        </ul>
        @endpermission

        <!-- 11. REPORTING -->
        @permission('read-students|read-registrations')
        <div class="pcoded-navigation-label">Reporting & Documents</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu {{ request()->routeIs('reporting.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-bar-chart-alt"></i><b>R</b></span>
                    <span class="pcoded-mtext">Reporting</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('reporting.teacher.documents.class-list*') ? 'active' : '' }}">
                        <a href="{{ route('reporting.teacher.documents.class-list', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Liste de classe</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('reporting.teacher.documents.evaluation-grades*') ? 'active' : '' }}">
                        <a href="{{ route('reporting.teacher.documents.evaluation-grades', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Liste des notes</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('reporting.teacher.documents.payslips*') ? 'active' : '' }}">
                        <a href="{{ route('reporting.teacher.documents.payslips', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Bulletin de paie</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        @endpermission

        <!-- 12. SÉCURITÉ ET ACCÈS -->
        @permission('read-users|read-roles|read-permissions')
        <div class="pcoded-navigation-label">Sécurité & Habilitations</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu {{ request()->routeIs('access.*') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-lock"></i><b>S</b></span>
                    <span class="pcoded-mtext">Sécurité & Accès</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @permission('read-users')
                    <li class="{{ request()->routeIs('access.users.*') ? 'active' : '' }}">
                        <a href="{{ route('access.users.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Utilisateurs</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-roles')
                    <li class="{{ request()->routeIs('access.roles.*') ? 'active' : '' }}">
                        <a href="{{ route('access.roles.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Rôles</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                    @permission('read-permissions')
                    <li class="{{ request()->routeIs('access.permissions.*') ? 'active' : '' }}">
                        <a href="{{ route('access.permissions.index', ['tenant' => tenant()->getTenantKey()]) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext">Permissions</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    @endpermission
                </ul>
            </li>
        </ul>
        @endpermission

    </div>
</nav>