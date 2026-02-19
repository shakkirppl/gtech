<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentReportController extends Controller
{
    /* =========================
       Student Date Wise Report
    ========================= */
    // public function dateWise(Request $request)
    // {
    //     $students = Student::with('course','scheme');

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $students->whereBetween('admission_date', [
    //             $request->from_date,
    //             $request->to_date
    //         ]);
    //     }

    //     $students = $students->orderBy('admission_date')
    //         ->paginate(25)
    //         ->withQueryString();

    //     return view('students.report-date', compact('students'))
    //         ->with($request->only('from_date','to_date'));
    // }

    public function dateWise(Request $request)
{
    $query = Student::query();

    // Date Filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('admission_date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    // 🔥 TOTAL FEE
    $total_fee = (clone $query)->sum('total_fees');

    // 🔥 TOTAL PAID (from fees_collections table)
    $studentIds = (clone $query)->select('id');

    $total_paid = \App\Models\FeesCollection::whereIn(
        'student_id',
        $studentIds
    )->sum('amount');

    $total_balance = $total_fee - $total_paid;

    // Load students with relation + paid amount
    $students = $query
        ->with(['course', 'scheme'])
        ->withSum('fees_collections as paid_amount', 'amount')
        ->orderBy('admission_date')
        ->paginate(25)
        ->withQueryString();

    return view('students.report-date', compact(
        'students',
        'total_fee',
        'total_paid',
        'total_balance'
    ))->with($request->only('from_date','to_date'));
}

public function dateWiseExport(Request $request)
{
    $query = Student::with(['course', 'scheme'])
        ->withSum('fees_collections as paid_amount', 'amount');

    // Date filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('admission_date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    $filename = "student_date_wise_" . now()->format('Ymd_His') . ".csv";

    return response()->stream(function () use ($query) {

        $handle = fopen('php://output', 'w');

        // ✅ UTF-8 BOM (important for Excel)
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Headings
        fputcsv($handle, [
            'Sl No',
            'Admission Date',
            'Reg No',
            'Name',
            'Course',
            'Scheme',
            'Total Fee',
            'Paid',
            'Balance',
            'Status'
        ]);

        $sl = 1;
        $grandTotal = 0;
        $grandPaid = 0;

        $query->orderBy('admission_date')
              ->chunk(500, function ($students) use ($handle, &$sl, &$grandTotal, &$grandPaid) {

            foreach ($students as $s) {

                $paid = $s->paid_amount ?? 0;
                $balance = $s->total_fees - $paid;

                $grandTotal += $s->total_fees;
                $grandPaid += $paid;

                fputcsv($handle, [
                    $sl++,
                    optional($s->admission_date)->format('d-m-Y'),
                    $s->reg_no,
                    $s->name,
                    $s->course->name ?? '',
                    $s->scheme->name ?? '',
                    number_format($s->total_fees, 2),
                    number_format($paid, 2),
                    number_format($balance, 2),
                    ucfirst($s->status)
                ]);
            }
        });

        // ✅ Add totals row
        fputcsv($handle, []);
        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
            'TOTAL',
            number_format($grandTotal, 2),
            number_format($grandPaid, 2),
            number_format($grandTotal - $grandPaid, 2),
            ''
        ]);

        fclose($handle);

    }, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
    ]);
}


    /* =========================
       Student Status Wise Report
    ========================= */
// public function statusWise(Request $request)
// {
//     $students = Student::with(['course', 'scheme'])
//         ->withSum('fees_collections as paid_amount', 'amount');

//     if ($request->filled('status')) {
//         $students->where('status', $request->status);
//     }

//       if ($request->filled('from_date') && $request->filled('to_date')) {
//             $students->whereBetween('admission_date', [
//                 $request->from_date,
//                 $request->to_date
//             ]);
//         }

//     $students = $students
//         ->orderBy('name')
//         ->paginate(25)
//         ->withQueryString();

//     return view('students.report-status', compact('students'))
//         ->with($request->only('status'));
// }

public function statusWise(Request $request)
{
    $query = Student::query();

    // Filters
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('admission_date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    // 🔥 TOTAL FEE (direct column)
    $total_fee = (clone $query)->sum('total_fees');

    // 🔥 TOTAL PAID (relation table)
    $total_paid = \App\Models\FeesCollection::whereIn(
            'student_id',
            (clone $query)->pluck('id')
        )->sum('amount');

    $total_balance = $total_fee - $total_paid;

    // Load students with relations
    $students = $query->with(['course', 'scheme'])
        ->withSum('fees_collections as paid_amount', 'amount')
        ->orderBy('name')
        ->paginate(25)
        ->withQueryString();

    return view('students.report-status', compact(
        'students',
        'total_fee',
        'total_paid',
        'total_balance'
    ))->with($request->only('status'));
}

public function statusWiseExport(Request $request)
{
    $query = Student::query()
        ->with(['course:id,name', 'scheme:id,name'])
        ->withSum('fees_collections as paid_amount', 'amount');

    // ✅ Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // ✅ Date filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('admission_date', [
            $request->from_date,
            $request->to_date
        ]);
    }

    $filename = "student_status_wise_" . now()->format('Ymd_His') . ".csv";

    return response()->streamDownload(function () use ($query) {

        $handle = fopen('php://output', 'w');

        // ✅ UTF-8 BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // ✅ Header
        fputcsv($handle, [
            'Sl No',
            'Student ID',
            'Reg No',
            'Name',
            'Course',
            'Scheme',
            'Admission Date',
            'Total Fee',
            'Paid',
            'Balance',
            'Status'
        ]);

        $sl = 1;
        $grandTotal = 0;
        $grandPaid = 0;

        $query->orderBy('name')
              ->chunk(500, function ($students) use ($handle, &$sl, &$grandTotal, &$grandPaid) {

            foreach ($students as $s) {

                $paid = $s->paid_amount ?? 0;
                $balance = $s->total_fees - $paid;

                $grandTotal += $s->total_fees;
                $grandPaid += $paid;

                fputcsv($handle, [
                    $sl++,
                    $s->id,
                    $s->reg_no,
                    $s->name,
                    $s->course->name ?? '',
                    $s->scheme->name ?? '',
                    optional($s->admission_date)->format('d-m-Y'),
                    $s->total_fees,
                    $paid,
                    $balance,
                    ucfirst($s->status),
                ]);
            }
        });

        // ✅ Totals Row
        fputcsv($handle, []);
        fputcsv($handle, [
            '',
            '',
            '',
            '',
            '',
            '',
            'TOTAL',
            $grandTotal,
            $grandPaid,
            $grandTotal - $grandPaid,
            ''
        ]);

        fclose($handle);

    }, $filename, [
        "Content-Type" => "text/csv; charset=UTF-8",
    ]);
}

}
