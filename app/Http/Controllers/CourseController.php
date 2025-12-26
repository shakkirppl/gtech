<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use DB;
use Exception;

class CourseController extends Controller
{
    // INDEX with pagination
    public function index()
    {
        $courses = Course::orderBy('id','desc')->paginate(10);
        return view('course.index', compact('courses'));
    }

    // CREATE
    public function create()
    {
        return view('course.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1'
        ]);

        DB::beginTransaction();
        try {
            Course::create($request->only('name','description','status'));

            DB::commit();
            return redirect()->route('course.index')
                ->with('success','Course created successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error','Course creation failed');
        }
    }

    // EDIT
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('course.edit', compact('course'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1'
        ]);

        DB::beginTransaction();
        try {
            Course::findOrFail($id)->update(
                $request->only('name','description','status')
            );

            DB::commit();
            return redirect()->route('course.index')
                ->with('success','Course updated successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error','Course update failed');
        }
    }

    // SOFT DELETE
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Course::findOrFail($id)->delete();

            DB::commit();
            return redirect()->route('course.index')
                ->with('success','Course deleted successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error','Course delete failed');
        }
    }
}
