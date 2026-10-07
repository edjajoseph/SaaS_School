<?php

namespace App\Modules\School\Http\Controllers\Web\Schedule;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\LeaveRequest;
use App\Modules\School\Models\School;
use App\Modules\School\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    protected $leaveService;

    public function __construct(LeaveRequestService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    /**
     * Récupérer l'ID de l'école courante
     */
    private function getCurrentSchoolId(Request $request)
    {
        return $request->input('school_id') 
            ?? session('current_school_id') 
            ?? auth()->user()->school_id;
    }

    // Vue du personnel : Mes demandes + formulaire
    public function myRequests(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId($request);
        $schools = School::all(); // Pour un sélecteur d'école si l'agent est rattaché à plusieurs écoles

        $requests = LeaveRequest::where('user_id', auth()->id())
            ->when($currentSchoolId, function ($query) use ($currentSchoolId) {
                $query->where('school_id', $currentSchoolId);
            })
            ->with('school')
            ->latest()
            ->paginate(10);

        return view('School::schedule.leave_requests.index', compact('requests', 'schools', 'currentSchoolId'));
    }

    // Soumettre une demande
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id'  => 'required|exists:schools,id',
            'type'       => 'required|in:permission,late_arrival,annual_leave,sick_leave,maternity_leave,special_leave',
            'start_date' => 'required|date|after_or_equal:today', // <-- Utiliser today au lieu de now
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:1000',
            'document'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
       

        $this->leaveService->createRequest($validated, auth()->user(), $validated['school_id']);

        return redirect()->back()->with('success', 'Votre demande d\'autorisation a été soumise avec succès.');
    }

    // Vue Administration / DRH / Dir. Études : Liste à valider par école
    public function pendingApprovals(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId($request);
        $schools = School::all();

        $pendingRequests = $currentSchoolId 
            ? $this->leaveService->getPendingRequestsForValidator(auth()->user(), $currentSchoolId)
            : collect();

        return view('School::schedule.leave_requests.approvals', compact('pendingRequests', 'schools', 'currentSchoolId'));
    }

    // Valider ou Rejeter
    public function process(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'action'           => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:500',
        ]);

        $this->leaveService->processRequest(
            $leaveRequest,
            $validated['action'],
            $validated['rejection_reason'] ?? null,
            auth()->user()
        );

        return redirect()->back()->with('success', 'La demande a été traitée avec succès.');
    }
}