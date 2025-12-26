<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeesCollection;
use App\Models\Student;

class FeesReportController extends Controller
{
    public function index(Request $request)
    {
        $query = FeesCollection::with('student');

        // Date filter
        if ($request->filled(['from_date','to_date'])) {
            $query->whereBetween('date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        // Student filter
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $reports = $query->orderBy('date','desc')->paginate(25);

        // Total
        $total = (clone $query)->sum('amount');

        return view('fees.report', [
            'reports' => $reports,
            'total'   => $total,
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
            'student_id'=> $request->student_id,
            'selectedStudent' => $request->student_id
                ? Student::find($request->student_id)
                : null
        ]);
    }

    // AJAX Select2 student search
    public function searchStudents(Request $request)
    {
        return Student::where('name','like',"%{$request->q}%")
            ->orWhere('reg_no','like',"%{$request->q}%")
            ->limit(20)
            ->get(['id','name','reg_no']);
    }
public function studentWise(Request $request)
{
    $reports = null;
    $total   = 0;
    $student = null;

    if ($request->filled('student')) {

        $student = Student::where('name', 'like', '%' . $request->student . '%')
            ->orWhere('reg_no', 'like', '%' . $request->student . '%')
            ->first();

        if ($student) {
            $reports = FeesCollection::where('student_id', $student->id)
                ->orderBy('date')
                ->paginate(25);

            $total = FeesCollection::where('student_id', $student->id)
                ->sum('amount');
        }
    }

    return view('fees.student-report', compact(
        'reports', 'total', 'student'
    ))->with('search', $request->student);
}

}
