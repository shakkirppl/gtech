<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentReportController extends Controller
{
    /* =========================
       Student Date Wise Report
    ========================= */
    public function dateWise(Request $request)
    {
        $students = Student::with('course','scheme');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $students->whereBetween('admission_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $students = $students->orderBy('admission_date')
            ->paginate(25)
            ->withQueryString();

        return view('students.report-date', compact('students'))
            ->with($request->only('from_date','to_date'));
    }

    /* =========================
       Student Status Wise Report
    ========================= */
    public function statusWise(Request $request)
    {
        $students = Student::with('course','scheme');

        if ($request->filled('status')) {
            $students->where('status', $request->status);
        }

        $students = $students->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('students.report-status', compact('students'))
            ->with($request->only('status'));
    }
}
