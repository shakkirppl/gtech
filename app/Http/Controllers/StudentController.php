<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Yajra\DataTables\DataTables;
class StudentController extends Controller
{
  public function index(Request $request)
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

    return view('students.index', compact('students'));
}

    public function create()
    {
        try {
            $courses = Course::where('status',1)->get();
            $schemes = Scheme::where('status',1)->get();
            $slNo = Student::max('id') + 1;
            return view('students.create', compact('courses','schemes', 'slNo'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load form');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'reg_no'          => 'required|unique:students,reg_no',
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'admission_date'  => 'required|date',
            'course_id'       => 'required|exists:courses,id',
            'scheme_id'       => 'required|exists:schemes,id',
            'total_fees'      => 'required|numeric|min:0',
            'status'          => 'required|in:Present,Leave,Completed',
        ]);

        DB::beginTransaction();

        try {
            // Safe SL No generation


            Student::create([
                'reg_no'          => $request->reg_no,
                'name'            => $request->name,
                'address'         => $request->address,
                'phone'           => $request->phone,
                'qualification'   => $request->qualification,
                'admission_date'  => $request->admission_date,
                'course_id'       => $request->course_id,
                'scheme_id'       => $request->scheme_id,
                'course_fee'      => $request->course_fee ?? 0,
                'material_fee'    => $request->material_fee ?? 0,
                'voucher_fee'      => $request->voucher_fee ?? 0,
                'others_fee'      => $request->others_fees ?? 0,
                'exam_fee'      => $request->exam_fees ?? 0,
                'total_fees'      => $request->total_fees ?? 0,
                'status'         => $request->status,
                'narration'      => $request->narration ?? null,
            ]);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully');

        } catch (Exception $e) {
            DB::rollBack();
  return response()->json([
        'error' => $e->getMessage()
    ], 500);
            return back()
                ->withInput()
                ->with('error', 'Student creation failed');
        }
    }

    public function edit(Student $student)
    {
        try {
            $courses = Course::where('status',1)->get();
            $schemes = Scheme::where('status',1)->get();

            return view('students.edit', compact('student','courses','schemes'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load edit page');
        }
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'reg_no' => 'required|unique:students,reg_no,' . $student->id,
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'status' => 'required|in:Present,Leave,Completed',
        ]);

        try {
            $student->update([
                'reg_no'        => $request->reg_no,
                'name'          => $request->name,
                'address'       => $request->address,
                'phone'         => $request->phone,
                'qualification' => $request->qualification,
                'admission_date'=> $request->admission_date,
                'course_id'     => $request->course_id,
                'scheme_id'     => $request->scheme_id,
                'course_fee'      => $request->course_fee ?? 0,
                'material_fee'    => $request->material_fee ?? 0,
                'voucher_fee'      => $request->voucher_fee ?? 0,
                'others_fee'      => $request->others_fees ?? 0,
                'exam_fee'      => $request->exam_fees ?? 0,
                'total_fees'      => $request->total_fees ?? 0,
                'status'        => $request->status ?? $student->status,
                'narration'      => $request->narration ?? null,
            ]);

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully');

        } catch (Exception $e) {
              return response()->json([
        'error' => $e->getMessage()
    ], 500);
            return back()
                ->withInput()
                ->with('error', 'Student update failed');
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();

            return back()->with('success', 'Student deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Student delete failed');
        }
    }

//     public function search(Request $request)
// {
//     $q = $request->q;

//     return Student::select('id','reg_no','name','phone')
//         ->where(function ($query) use ($q) {
//             $query->where('name', 'like', "%{$q}%")
//                   ->orWhere('reg_no', 'like', "%{$q}%")
//                   ->orWhere('phone', 'like', "%{$q}%");
//         })
//         ->limit(20)
//         ->get()
//         ->map(function ($s) {
//             return [
//                 'id'   => $s->id,
//                 'text' => "{$s->id} | {$s->reg_no} | {$s->name} | {$s->phone}"
//             ];
//         });
// }

public function search(Request $request)
{
    $search = $request->q;

    $students = Student::with('course')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reg_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('course', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->limit(20)
        ->get();

    $formatted = $students->map(function ($student) {
        return [
            'id' => $student->id,
            'text' => $student->name . ' | ' 
                    . $student->reg_no . ' | ' 
                    . $student->phone . ' | Course: ' 
                    . ($student->course->name ?? 'N/A'),
        ];
    });

    return response()->json($formatted);
}

    public function show($id)
    {
        return Student::with('course','scheme')
            ->select('id','name','phone','course_id','scheme_id','total_fees')
            ->findOrFail($id);
    }

    public function updateStatus(Student $student)
{
    $student->status = !$student->status;
    $student->save();

    return back()->with('success', 'Student status updated successfully.');
}

public function details(Student $student, Request $request){
    $type = $request->fees_type ?? 'All';
    $query = $student->fees_collections();
    if($type !== 'All') $query->where('fees_type', $type);
    $fees_collections = $query->get();

    $paid = $student->fees_collections()->sum('amount');
    $balance = $student->total_fees - $paid;

    return response()->json([
        'id' => $student->id,
        'reg_no' => $student->reg_no,
        'name' => $student->name,
        'admission_date' => $student->admission_date,
        'course' => $student->course,
        'scheme' => $student->scheme,
        'total_fees' => $student->total_fees,
        'paid' => $paid,
        'balance' => $balance,
        'status' => $student->status,
        'fees_collections' => $fees_collections
    ]);
}
}
