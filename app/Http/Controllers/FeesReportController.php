<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeesCollection;
use App\Models\Student;

class FeesReportController extends Controller
{
    public function index(Request $request)
{
    $query = FeesCollection::with(['student.course', 'student.scheme']);

    // Date filter
    if ($request->filled(['from_date', 'to_date'])) {
        $query->whereBetween('date', [
            $request->from_date,
            $request->to_date
        ]);
    }


    // Fees Type filter
    if ($request->filled('fees_type') && $request->fees_type != 'All') {
        $query->where('fees_type', $request->fees_type);
    }

    $reports = $query->orderBy('date', 'desc')->paginate(25);

    // Total
    $total = (clone $query)->sum('amount');

    return view('fees.report', [
        'reports' => $reports,
        'total'   => $total,
        'from_date' => $request->from_date,
        'to_date'   => $request->to_date,
        'student_id'=> $request->student_id,
        'fees_type' => $request->fees_type ?? 'All',
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
    $query = Student::with(['course', 'scheme']);

    // 🔍 Normal Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('reg_no', 'like', "%$search%")
              ->orWhereHas('course', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%$search%");
              });
        });
    }

    // 📄 Pagination
    $students = $query->orderBy('id', 'desc')->paginate(10);

    return view('fees.student-report', compact('students'));
}

public function studentView(Request $request, $id)
{
    $feesType = $request->fees_type ?? 'All';

    $student = Student::with(['course', 'scheme'])
        ->findOrFail($id);

    $feesQuery = $student->fees_collections();

    if ($feesType !== 'All') {
        $feesQuery->where('fees_type', $feesType);
    }

    $feesCollections = $feesQuery->orderBy('date')->get();

    $paid = $student->fees_collections()->sum('amount');
    $balance = $student->total_fees - $paid;

    return view('fees.student_view', compact(
        'student',
        'feesCollections',
        'paid',
        'balance',
        'feesType'
    ));
}

public function studentWiseo(Request $request)
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
