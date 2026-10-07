<?php
namespace App\Modules\School\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    
    public function index(Request $request)
    {
        return view('School::dashboard.student');
    }
}