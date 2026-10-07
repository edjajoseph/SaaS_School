<?php
namespace App\Modules\School\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    
    public function index(Request $request)
    {
        return view('School::dashboard.teacher');

    }
}