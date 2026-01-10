<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type != 'Admin') {
            return redirect()->route('users.edit', Auth::id());
        }

        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (Auth::user()->user_type != 'Admin') {
            return back()->with('error','Access denied');
        }
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->user_type != 'Admin') {
            return back()->with('error','Access denied');
        }

        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
            'user_type'=>'required|in:Admin,Subadmin'
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'user_type'=>$request->user_type,
        ]);

        return redirect()->route('users.index')->with('success','User Created');
    }

    public function edit($id)
    {
        if (Auth::user()->user_type != 'Admin' && Auth::id() != $id) {
            return back()->with('error','Access denied');
        }

        $user = User::findOrFail($id);
        return view('users.edit',compact('user'));
    }

public function update(Request $request, $id)
{
    if (Auth::user()->user_type != 'Admin' && Auth::id() != $id) {
        return back()->with('error','Access denied');
    }

    $user = User::findOrFail($id);

    $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
        'user_type' => 'required|in:Admin,Subadmin',
    ];

    // Only admin can change password
    if (Auth::user()->user_type == 'Admin' && $request->password) {
        $rules['password'] = 'confirmed|min:6';
    }

    $request->validate($rules);

    $user->update($request->only('name','email','user_type'));

    if (Auth::user()->user_type == 'Admin' && $request->password) {
        $user->password = Hash::make($request->password);
        $user->save();
    }

    return redirect()->route('users.index')->with('success','User Updated');
}
}
