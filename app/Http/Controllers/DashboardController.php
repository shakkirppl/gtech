<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Student;
use App\Models\FeesCollection;
class DashboardController extends Controller
{
    //
public function dashboard()
{
    try {

        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();

        // STUDENTS
        $totalStudents    = Student::count();
        $activeStudents   = Student::where('status', 1)->count();
        $inactiveStudents = Student::where('status', 0)->count();

        $todayAdmissions = Student::whereDate('admission_date', $today)->count();
        $monthAdmissions = Student::whereMonth('admission_date', now()->month)
                                  ->whereYear('admission_date', now()->year)
                                  ->count();

        // FEES COLLECTION
        $todayCollection = FeesCollection::whereDate('date', $today)->sum('amount');

        $weekCollection = FeesCollection::whereBetween('date', [$weekStart, now()])
                            ->sum('amount');

        $monthCollection = FeesCollection::whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->sum('amount');

        $overallCollection = FeesCollection::sum('amount');

        // DUE
        $dueAmount = Student::sum('total_fees') - FeesCollection::sum('amount');

        // RECENT
        $recentCollections = FeesCollection::with('student')
            ->latest()
            ->limit(5)
            ->get();

        // 📊 CHART DATA (LAST 7 DAYS)
        $chartData = FeesCollection::selectRaw('DATE(date) as day, SUM(amount) as total')
            ->whereDate('date', '>=', now()->subDays(6))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin', compact(
            'today',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'todayAdmissions',
            'monthAdmissions',
            'todayCollection',
            'weekCollection',
            'monthCollection',
            'overallCollection',
            'dueAmount',
            'recentCollections',
            'chartData'
        ))->with([
            'now' => now()->toDateString(),
            'name' => auth()->user()->name
        ]);

    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

}
