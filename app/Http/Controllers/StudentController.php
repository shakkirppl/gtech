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
    if ($request->ajax()) {

        $students = Student::with(['course','scheme'])
            ->select('students.*');

        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('course', function ($row) {
                return $row->course->name ?? '-';
            })
            ->addColumn('scheme', function ($row) {
                return $row->scheme->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '
                <a href="'.route('students.edit',$row->id).'" class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="'.route('students.destroy',$row->id).'" method="POST" style="display:inline">
                    '.csrf_field().method_field("DELETE").'
                    <button class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('students.index');
}

    public function create()
    {
        try {
            $courses = Course::where('status',1)->get();
            $schemes = Scheme::where('status',1)->get();

            return view('students.create', compact('courses','schemes'));
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
            'total_fees'      => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Safe SL No generation
            $lastSl = Student::orderBy('id','desc')->lockForUpdate()->first();
            $nextNo = $lastSl ? intval(substr($lastSl->sl_no,2)) + 1 : 1;

            Student::create([
                'sl_no'           => 'SL'.str_pad($nextNo, 4, '0', STR_PAD_LEFT),
                'reg_no'          => $request->reg_no,
                'name'            => $request->name,
                'address'         => $request->address,
                'phone'           => $request->phone,
                'qualification'   => $request->qualification,
                'admission_date'  => $request->admission_date,
                'course_id'       => $request->course_id,
                'scheme_id'       => $request->scheme_id,
                'total_fees'      => $request->total_fees,
                'status'          => 1
            ]);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully');

        } catch (Exception $e) {
            DB::rollBack();

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
            'phone'  => 'required|string|max:20'
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
                'total_fees'    => $request->total_fees,
                'status'        => $request->status ?? $student->status
            ]);

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully');

        } catch (Exception $e) {
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

    public function search(Request $request)
    {
        $q = $request->q;

        return Student::select('id','sl_no','reg_no','name','phone')
            ->where('name', 'like', "%$q%")
            ->orWhere('reg_no', 'like', "%$q%")
            ->orWhere('phone', 'like', "%$q%")
            ->limit(20) // VERY IMPORTANT
            ->get()
            ->map(function ($s) {
                return [
                    'id'   => $s->id,
                    'text' => "{$s->sl_no} | {$s->reg_no} | {$s->name} | {$s->phone}"
                ];
            });
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
}
