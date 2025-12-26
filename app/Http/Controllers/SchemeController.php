<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Scheme;
use Illuminate\Http\Request;
use DB;
use Exception;

class SchemeController extends Controller
{
    // INDEX with pagination
    public function index()
    {
        $schemes = Scheme::orderBy('id','desc')->paginate(10);
        return view('scheme.index', compact('schemes'));
    }

    // CREATE
    public function create()
    {
        return view('scheme.create');
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
            Scheme::create($request->only('name','description','status'));

            DB::commit();
            return redirect()->route('scheme.index')
                ->with('success','Scheme created successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error','Scheme creation failed');
        }
    }

    // EDIT
    public function edit($id)
    {
        $scheme = Scheme::findOrFail($id);
        return view('scheme.edit', compact('scheme'));
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
            Scheme::findOrFail($id)->update(
                $request->only('name','description','status')
            );

            DB::commit();
            return redirect()->route('scheme.index')
                ->with('success','Scheme updated successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error','Scheme update failed');
        }
    }

    // SOFT DELETE
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Scheme::findOrFail($id)->delete();

            DB::commit();
            return redirect()->route('scheme.index')
                ->with('success','Scheme deleted successfully');

        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error','Scheme delete failed');
        }
    }
}
