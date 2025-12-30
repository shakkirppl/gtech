<?php

namespace App\Http\Controllers;

use App\Models\FeesCollection;
use App\Models\Student;
use Illuminate\Http\Request;
use DB;
use Exception;
use Yajra\DataTables\Facades\DataTables;
class FeesCollectionController extends Controller
{
    //
public function index(Request $request)
{
    $query = FeesCollection::with(['student.course', 'student.scheme']);

    // 🔍 Normal Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('voucher_no', 'like', "%{$search}%")
              ->orWhereHas('student', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    // 📄 Pagination
    $collections = $query->orderBy('id', 'desc')->paginate(10);

    return view('fees.index', compact('collections'));
}

   public function create()
{
    // Voucher auto-generate
    $voucherNo = FeesCollection::max('id') + 1;

    return view('fees.create', compact('voucherNo'));
}

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'voucher_no' => 'required|unique:fees_collections',
            'date'       => 'required|date',
            'amount'     => 'required|numeric|min:1',
           'fees_type' => 'required|in:course_fee,exam_fee,material_fee,voucher_fee,others_fee',

        ]);

        FeesCollection::create($request->all());

        return redirect()->route('fees.index')
            ->with('success','Fees collected successfully');
    }

    public function destroy($id)
    {
        FeesCollection::findOrFail($id)->delete();

        return back()->with('success','Record deleted');
    }

    public function getPaidFees($student_id)
{
    $paid = FeesCollection::where('student_id', $student_id)
            ->sum('amount');

    return response()->json([
        'paid_fees' => $paid
    ]);
}


public function summary($studentId, $type)
{
    $student = Student::findOrFail($studentId);

    // Total fee by type
    $total = match ($type) {
        'course_fee'   => $student->course_fee,
        'exam_fee'     => $student->exam_fee,
        'material_fee' => $student->material_fee,
        'voucher_fee'  => $student->voucher_fee,
        'others_fee'   => $student->others_fee,
        default        => 0,
    };

    // Paid fee by type
    $paid = FeesCollection::where('student_id', $studentId)
        ->where('fees_type', $type)
        ->sum('amount');

    return response()->json([
        'total'   => $total,
        'paid'    => $paid,
        'balance' => max($total - $paid, 0),
    ]);
}
public function history($studentId)
{
    return FeesCollection::where('student_id', $studentId)
        ->orderBy('date', 'desc')
        ->get(['date','amount','fees_type']);
}
}
