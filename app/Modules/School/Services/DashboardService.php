<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\User;
use App\Modules\School\Models\Registration;
use App\Modules\School\Models\Payment;
use App\Modules\School\Models\TeacherAttendance;
use App\Modules\School\Models\StudentAttendance;
use App\Modules\School\Models\AttendanceTerminal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Métriques pour le Super Administrateur / Administrateur
     */
    public function getAdminMetrics(): array
    {
        $today = now()->toDateString();

        // 1. Répartition des comptes utilisateurs de l'école par rôle (Laratrust)
        $usersByRole = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('roles.display_name', DB::raw('count(*) as total'))
            ->groupBy('roles.id', 'roles.display_name')
            ->pluck('total', 'display_name')
            ->toArray();

        // 2. Activité des comptes aujourd'hui
        $activeUsersToday = User::whereDate('updated_at', $today)->count();

        // 3. Statut des Bornes d'Émargement / Pointeuses de l'école
        $totalTerminals = AttendanceTerminal::count();
        $onlineTerminals = AttendanceTerminal::where('last_ping_at', '>=', now()->subMinutes(15))->count();
        $offlineTerminals = max(0, $totalTerminals - $onlineTerminals);

        // 4. Comptes en attente d'activation (pwd_change = false ou isactive = false)
        $inactiveUsersCount = User::where('isactive', false)->count();

        return [
            'total_users' => User::count(),
            'active_users_today' => $activeUsersToday,
            'inactive_users_count' => $inactiveUsersCount,
            'users_by_role' => $usersByRole,
            'total_terminals' => $totalTerminals,
            'online_terminals' => $onlineTerminals,
            'offline_terminals' => $offlineTerminals,
            'latest_users' => User::latest()->take(5)->get(),
        ];
    }

    /**
     * Métriques pour le Directeur Académique / Responsable Pédagogique
     */
    public function getAcademicMetrics(): array
    {
        // 1. Métriques de base
        $totalStudents = Registration::where('status', 'confirmed')->count();
        $pendingValidations = Registration::where('status', 'pending')->count();
        $evaluationsPendingPv = DB::table('evaluations')->where('is_published', false)->whereNull('deleted_at')->count();
        
        // 2. Répartition des effectifs par classe
        $studentsByClass = DB::table('registrations')
            ->join('school_classes', 'registrations.school_class_id', '=', 'school_classes.id')
            ->where('registrations.status', 'validated')
            ->select('school_classes.name as class_name', DB::raw('count(*) as total'))
            ->groupBy('school_classes.id', 'school_classes.name')
            ->pluck('total', 'class_name')
            ->toArray();

        // 3. Répartition des enseignants par matière enseignée (via table pivot ou schedules)
        $teachersBySubject = DB::table('schedules')
            ->join('subjects', 'schedules.school_subject_id', '=', 'subjects.id')
            ->whereNotNull('schedules.staff_id')
            ->select('subjects.name as subject_name', DB::raw('count(distinct staff_id) as total_teachers'))
            ->groupBy('subjects.id', 'subjects.name')
            ->pluck('total_teachers', 'subject_name')
            ->toArray();

        // 4. Exécution des heures de cours par classe (prévues dans schedules vs exécutées dans teacher_attendances)
        $classesExecution = DB::table('school_classes')
            ->leftJoin('schedules', 'school_classes.id', '=', 'schedules.school_class_id')
            ->leftJoin('teacher_attendances', 'schedules.id', '=', 'teacher_attendances.schedule_id')
            ->select(
                'school_classes.id',
                'school_classes.name as class_name',
                DB::raw('COUNT(DISTINCT schedules.school_subject_id) as total_subjects'),
                // Calcul des heures prévues (différence entre end_time et start_time en heures)
                DB::raw('COALESCE(SUM(TIMESTAMPDIFF(HOUR, schedules.start_time, schedules.end_time)), 0) as total_planned_hours'),
                DB::raw('COALESCE(SUM(teacher_attendances.hours_done), 0) as total_executed_hours')
            )
            ->groupBy('school_classes.id', 'school_classes.name')
            ->get()
            ->map(function ($item) {
                $item->remaining_hours = max(0, $item->total_planned_hours - $item->total_executed_hours);
                $item->progress_percentage = $item->total_planned_hours > 0 
                    ? round(($item->total_executed_hours / $item->total_planned_hours) * 100, 1) 
                    : 0;
                return $item;
            });

        // 5. Matières achevées (is_completed = 1) et créneaux horaires libérés pour reprogrammation
        $completedSubjectsWithFreedSlots = DB::table('schedules')
            ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
            ->join('subjects', 'schedules.school_subject_id', '=', 'subjects.id')
            ->leftJoin('users as teachers', 'schedules.staff_id', '=', 'teachers.id')
            ->leftJoin('rooms', 'schedules.room_id', '=', 'rooms.id')
            ->where('schedules.is_completed', 1)
            ->select(
                'school_classes.name as class_name',
                'subjects.name as subject_name',
                'teachers.name as teacher_name',
                'schedules.day_of_week',
                'schedules.start_time',
                'schedules.end_time',
                'rooms.name as room_name'
            )
            ->get();

        // 6. Taux d'absentéisme et de retard des Enseignants
        $teacherTotalSessions = DB::table('teacher_attendances')->count();

        // Absences (0 heure réalisée)
        $teacherAbsences = DB::table('teacher_attendances')->where('hours_done', 0)->count();

        // Retards / Séances partielles (heures faites inférieures à la durée programmée)
        $teacherDelays = DB::table('teacher_attendances')
            ->where('hours_done', '>', 0)
            ->whereRaw('hours_done < TIMESTAMPDIFF(HOUR, start_time, end_time)')
            ->count();

        $teacherAbsenceRate = $teacherTotalSessions > 0 ? round(($teacherAbsences / $teacherTotalSessions) * 100, 1) : 0;
        $teacherDelayRate = $teacherTotalSessions > 0 ? round(($teacherDelays / $teacherTotalSessions) * 100, 1) : 0;

        // 7. Taux d'absentéisme et de retard des Étudiants
        $studentAbsenceTotal = DB::table('student_attendances')->count();
        $studentAbsences = DB::table('student_attendances')->where('status', 'absent')->count();
        $studentDelays = DB::table('student_attendances')->where('status', 'late')->count();

        $studentAbsenceRate = $studentAbsenceTotal > 0 ? round(($studentAbsences / $studentAbsenceTotal) * 100, 1) : 0;
        $studentDelayRate = $studentAbsenceTotal > 0 ? round(($studentDelays / $studentAbsenceTotal) * 100, 1) : 0;

        // Progression globale
        $totalPlannedGlobal = $classesExecution->sum('total_planned_hours');
        $totalExecutedGlobal = $classesExecution->sum('total_executed_hours');
        $completionRate = $totalPlannedGlobal > 0 ? round(($totalExecutedGlobal / $totalPlannedGlobal) * 100, 1) : 0;

        return [
            'total_students'         => $totalStudents,
            'pending_validations'    => $pendingValidations,
            'completion_rate'        => $completionRate,
            'evaluations_pending_pv' => $evaluationsPendingPv,
            'students_by_class'      => $studentsByClass,
            'teachers_by_subject'    => $teachersBySubject,
            'classes_execution'      => $classesExecution,
            'freed_slots'            => $completedSubjectsWithFreedSlots,
            'attendance_stats'       => [
                'teachers' => [
                    'absence_rate'   => $teacherAbsenceRate,
                    'absences_count' => $teacherAbsences,
                    'delay_rate'     => $teacherDelayRate,   // Key ajoutée
                    'delays_count'   => $teacherDelays,     // Key ajoutée
                ],
                'students' => [
                    'absence_rate'   => $studentAbsenceRate,
                    'delay_rate'     => $studentDelayRate,
                    'absences_count' => $studentAbsences,
                    'delays_count'   => $studentDelays,     // Assurez-vous d'ajouter aussi delays_count si utilisé
                ],
            ]
        ];
    }

    /**
     * KPIs & Analytics : Service Scolarité & Vie Scolaire
     */ 
    public function getScolariteMetrics(): array
    {
        $today = now()->toDateString();
        $nowTime = now()->toTimeString();

        // 1. Statut des dossiers d'inscription
        $registrationStats = Registration::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 2. Présences / Absences du jour (Table: student_attendances)
        $attendanceToday = StudentAttendance::whereDate('date', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 3. Salles occupées actuellement (Jointure entre teacher_attendances et schedules)
        $occupiedRoomsCount = TeacherAttendance::whereDate('teacher_attendances.date', $today)
            ->whereTime('teacher_attendances.start_time', '<=', $nowTime)
            ->whereTime('teacher_attendances.end_time', '>=', $nowTime)
            ->join('schedules', 'teacher_attendances.schedule_id', '=', 'schedules.id')
            ->whereNotNull('schedules.room_id')
            ->distinct('schedules.room_id')
            ->count('schedules.room_id');

        return [
            'total_registrations'     => array_sum($registrationStats),
            'pending_registrations'   => $registrationStats['pending'] ?? 0,
            'validated_registrations' => $registrationStats['validated'] ?? 0,
            'rejected_registrations'  => $registrationStats['rejected'] ?? 0,
            // APRÈS (compte les inscriptions sans documents OU avec au moins un document non fourni)
            'missing_docs_count' => Registration::whereDoesntHave('documents')
            ->orWhereHas('documents', function ($query) {
                $query->where('is_provided', false);
            })
            ->count(),
            'absents_today'           => $attendanceToday['absent'] ?? 0,
            'presents_today'          => $attendanceToday['present'] ?? 0,
            'late_today'              => $attendanceToday['late'] ?? 0,
            'occupied_rooms'          => $occupiedRoomsCount,
            'latest_registrations'    => Registration::with('student.personne')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * KPIs & Analytics : Agent de Caisse & Comptable
     */
    public function getCaisseMetrics(): array
    {
        $today = now()->toDateString();

        // 1. Recouvrement financier (Calculé sur student_fee_accounts)
        $totalExpected = DB::table('student_fee_accounts')
            ->whereNull('deleted_at')
            ->sum('total_due');

        $totalCollected = DB::table('student_fee_accounts')
            ->whereNull('deleted_at')
            ->sum('total_paid');

        $unpaidAmount = max(0, $totalExpected - $totalCollected);
        $recoveryRate = $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100, 2) : 0;

        // 2. Répartition des paiements du jour par mode de règlement
        $paymentsByMethodToday = Payment::where('status', 'paid')
            ->whereDate('created_at', $today)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        // 3. Évolution mensuelle des encaissements sur l'année en cours
        $monthlyCollections = Payment::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 4. Calcul du coût total de la vacation enseignante
        $vacationCost = DB::table('teacher_attendances')
            ->leftJoin('teacher_subject_rates', function ($join) {
                $join->on('teacher_attendances.staff_id', '=', 'teacher_subject_rates.staff_id')
                    ->on('teacher_attendances.school_class_id', '=', 'teacher_subject_rates.school_class_id')
                    ->on('teacher_attendances.school_subject_id', '=', 'teacher_subject_rates.school_subject_id');
            })
            ->select(DB::raw("
                SUM(
                    teacher_attendances.hours_done * CASE 
                        WHEN teacher_attendances.session_type = 'CM' THEN COALESCE(teacher_subject_rates.rate_cm, 0)
                        WHEN teacher_attendances.session_type = 'TD' THEN COALESCE(teacher_subject_rates.rate_td, 0)
                        WHEN teacher_attendances.session_type = 'TP' THEN COALESCE(teacher_subject_rates.rate_tp, 0)
                        WHEN teacher_attendances.session_type = 'EXAMEN' THEN COALESCE(teacher_subject_rates.rate_examen, 0)
                        ELSE 0
                    END
                ) as total_vacation_cost
            "))
            ->value('total_vacation_cost') ?? 0;

        // 5. Calcul des Marges Financières
        $netMarginCollected = $totalCollected - $vacationCost;
        $projectedNetMargin = $totalExpected - $vacationCost;

        return [
            'today_collections'       => array_sum($paymentsByMethodToday),
            'total_collected'         => $totalCollected,
            'total_expected'          => $totalExpected,
            'unpaid_amount'           => $unpaidAmount,
            'recovery_rate'           => $recoveryRate,
            'total_vacation_cost'     => round($vacationCost, 2),
            'net_margin_collected'    => round($netMarginCollected, 2),
            'projected_net_margin'    => round($projectedNetMargin, 2),
            'methods_breakdown_today' => $paymentsByMethodToday,
            'monthly_chart_data'      => $monthlyCollections,
            'latest_payments'         => Payment::with('registration.student.personne')
                ->where('status', 'paid')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * KPIs & Analytics : Corps Enseignant / Intervenants
     */
    public function getTeacherMetrics($user): array
    {
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();
        $staffId = $user->id;
        $currentSchoolId = session('current_school_id') ?? $user->school_id;

        // 1. Récupération globale de tous les cours de la semaine de l'enseignant
        $allTeacherSchedules = DB::table('schedules')
            ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
            ->join('subjects', 'schedules.school_subject_id', '=', 'subjects.id')
            ->leftJoin('rooms', 'schedules.room_id', '=', 'rooms.id')
            ->leftJoin('teacher_attendances', function ($join) use ($today) {
                $join->on('schedules.id', '=', 'teacher_attendances.schedule_id')
                    ->whereDate('teacher_attendances.date', '=', $today);
            })
            ->where('schedules.staff_id', $staffId)
            ->where('schedules.is_active', 1)
            ->whereNull('schedules.deleted_at')
            ->select(
                'schedules.id as schedule_id',
                'schedules.day_of_week',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.session_type',
                'school_classes.name as class_name',
                'subjects.name as subject_name',
                'rooms.name as room_name',
                'teacher_attendances.id as attendance_id',
                'teacher_attendances.topic_covered'
            )
            ->orderBy('schedules.start_time')
            ->orderBy('schedules.day_of_week')
            ->get();

        // 2. Filtrage des cours du jour
        $todaySessions = $allTeacherSchedules
            ->where('day_of_week', now()->dayOfWeekIso)
            ->map(function ($session) use ($currentTime) {
                if ($currentTime >= $session->start_time && $currentTime <= $session->end_time) {
                    $session->status_code = 'ONGOING';
                } elseif ($currentTime > $session->end_time) {
                    $session->status_code = 'PASSED';
                } else {
                    $session->status_code = 'UPCOMING';
                }
                return $session;
            });

        $currentSession = $todaySessions->firstWhere('status_code', 'ONGOING');

        // 3. Construction de la Matrice Hebdomadaire (Groupée par Plage Horaire)
        $matrixSchedule = $allTeacherSchedules->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->end_time)->format('H:i');
        })->map(function ($timeSlot) {
            return $timeSlot->keyBy('day_of_week');
        });

        // 4. Métriques complémentaires
        $completedHoursMonth = DB::table('teacher_attendances')
            ->where('staff_id', $staffId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereNull('deleted_at')
            ->sum('hours_done');

        $totalTeacherSessions = DB::table('teacher_attendances')
            ->where('staff_id', $staffId)
            ->whereDate('date', '<=', $today)
            ->whereNull('deleted_at')
            ->count();

        $filledLogbooksCount = DB::table('teacher_attendances')
            ->where('staff_id', $staffId)
            ->whereDate('date', '<=', $today)
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereNotNull('topic_covered')->orWhereNotNull('chapter_title');
            })
            ->count();

        $logbookCompletionRate = $totalTeacherSessions > 0 ? round(($filledLogbooksCount / $totalTeacherSessions) * 100, 1) : 100;

        $pendingGradesCount = DB::table('evaluations')
            ->join('schedules', function ($join) use ($staffId) {
                $join->on('evaluations.school_class_id', '=', 'schedules.school_class_id')
                    ->on('evaluations.school_subject_id', '=', 'schedules.school_subject_id')
                    ->where('schedules.staff_id', '=', $staffId);
            })
            ->where('evaluations.is_published', 0)
            ->whereNull('evaluations.deleted_at')
            ->distinct('evaluations.id')
            ->count('evaluations.id');

        // 5. Calcul du taux de présence moyen des étudiants
        $teacherScheduleIds = DB::table('schedules')
            ->where('staff_id', $staffId)
            ->pluck('id');

        $studentAttendanceQuery = DB::table('student_attendances')
            ->whereIn('schedule_id', $teacherScheduleIds);

        $totalStudentAttendances = $studentAttendanceQuery->count();
        $presentStudentAttendances = (clone $studentAttendanceQuery)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $averageStudentAttendance = $totalStudentAttendances > 0 
            ? round(($presentStudentAttendances / $totalStudentAttendances) * 100, 1) 
            : 100;

        // 6. Récupération de la dernière demande d'autorisation d'absence / congé
        $latestLeaveRequest = DB::table('leave_requests')
            ->where('user_id', $user->id)
            ->when($currentSchoolId, function ($query) use ($currentSchoolId) {
                $query->where('school_id', $currentSchoolId);
            })
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->first();

        // Convertir les chaînes de dates en objets Carbon si un enregistrement existe
        if ($latestLeaveRequest) {
            $latestLeaveRequest->start_date = \Carbon\Carbon::parse($latestLeaveRequest->start_date);
            $latestLeaveRequest->end_date = \Carbon\Carbon::parse($latestLeaveRequest->end_date);
        }

        return [
            'today_sessions_count'       => $todaySessions->count(),
            'today_sessions'             => $todaySessions,
            'current_session'            => $currentSession,
            'matrix_schedule'            => $matrixSchedule,
            'completed_hours_month'      => round($completedHoursMonth, 2),
            'logbook_completion_rate'    => $logbookCompletionRate,
            'pending_logbooks_count'     => max(0, $totalTeacherSessions - $filledLogbooksCount),
            'pending_grades_count'       => $pendingGradesCount,
            'average_student_attendance' => $averageStudentAttendance,
            'current_day'                => now()->dayOfWeekIso,
            'latestLeaveRequest'         => $latestLeaveRequest, // <-- Ajouté aux métriques
        ];
    }

    /**
     * KPIs & Analytics : Étudiant / Parent
     */
    public function getStudentMetrics(User $user): array
    {
        // On récupère la dernière inscription de l'étudiant
        $registration = Registration::where('personne_id', $user->personne_id)->latest()->first();
        $registrationId = $registration?->id;
        $classId = $registration?->school_class_id;

        // 1. Situation financière de l'étudiant
        $totalFee = $registration?->total_fee ?? 0;
        $totalPaid = Payment::where('registration_id', $registrationId)
            ->where('status', 'paid')
            ->sum('amount');
        $balanceDue = max(0, $totalFee - $totalPaid);

        // 2. Taux d'assiduité individuel (Table: student_attendances)
        $totalAttendances = DB::table('student_attendances')
            ->where('student_id', $user->personne_id)
            ->count();

        $absencesCount = DB::table('student_attendances')
            ->where('student_id', $user->personne_id)
            ->where('status', 'absent')
            ->count();

        $lateCount = DB::table('student_attendances')
            ->where('student_id', $user->personne_id)
            ->where('status', 'late')
            ->count();

        $attendanceRate = $totalAttendances > 0 
            ? round((($totalAttendances - $absencesCount) / $totalAttendances) * 100, 2) 
            : 100;

        // 3. Récupération de l'emploi du temps hebdomadaire de la classe de l'étudiant (Table: schedules)
        $weeklySchedule = collect();
        if ($classId) {
            $weeklySchedule = DB::table('schedules')
                ->join('subjects', 'schedules.school_subject_id', '=', 'subjects.id')
                ->leftJoin('users as teachers', 'schedules.staff_id', '=', 'teachers.id')
                ->leftJoin('rooms', 'schedules.room_id', '=', 'rooms.id')
                ->where('schedules.school_class_id', $classId)
                ->where('schedules.is_active', 1)
                ->select(
                    'schedules.id',
                    'schedules.day_of_week',
                    'schedules.start_time',
                    'schedules.end_time',
                    'schedules.session_type',
                    'schedules.is_completed',
                    'subjects.name as subject_name',
                    'teachers.name as teacher_name',
                    'rooms.name as room_name'
                )
                ->orderBy('schedules.day_of_week')
                ->orderBy('schedules.start_time')
                ->get()
                ->groupBy('day_of_week'); // Groupé par jour (1=Lundi, ..., 7=Dimanche)
        }

        return [
            'registration'        => $registration,
            'registration_status' => $registration?->status ?? 'non_inscrit',
            'total_fee'           => $totalFee,
            'total_paid'          => $totalPaid,
            'balance_due'         => $balanceDue,
            'attendance_rate'     => $attendanceRate,
            'absences_count'      => $absencesCount,
            'late_count'          => $lateCount,
            'weekly_schedule'     => $weeklySchedule,
            'current_day'         => now()->dayOfWeekIso, // Jour actuel (1 à 7)
        ];
    }
}