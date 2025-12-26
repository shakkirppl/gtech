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
    if ($request->ajax()) {

        $collections = FeesCollection::with(['student.course','student.scheme'])
            ->select('fees_collections.*');

        return DataTables::of($collections)
            ->addIndexColumn()
            ->addColumn('student', function ($row) {
                return $row->student->name ?? '-';
            })
            ->addColumn('voucher', fn($row) => $row->voucher_no)
            ->addColumn('date', fn($row) => $row->date)
            ->addColumn('amount', fn($row) => number_format($row->amount, 2))
            ->addColumn('action', function ($row) {
                return '
                <form action="'.route('fees.destroy',$row->id).'" method="POST" style="display:inline">
                    '.csrf_field().method_field("DELETE").'
                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm(\'Delete?\')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('fees.index');
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
            'amount'     => 'required|numeric|min:1'
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
}
