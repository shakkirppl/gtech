@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3 text-center text-md-start">Create User</h4>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('users.store') }}" method="POST">
@csrf

<div class="mb-3">
<label class="form-label">Name</label>
<input type="text" name="name" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Confirm Password</label>
<input type="password" name="password_confirmation" class="form-control">
</div>

@if(auth()->user()->user_type=='Admin')
<div class="mb-3">
<label class="form-label">User Type</label>
<select name="user_type" class="form-control">
    <option value="Admin">Admin</option>
    <option value="Subadmin">Subadmin</option>
</select>
</div>
@endif

<button class="btn btn-primary">Save</button>
</form>
<a href="{{ route('users.index') }}" class="btn btn-secondary mt-2">Back</a>

</div>
</div>

</div>
</div>

</div>
</div>
</div>

@endsection
