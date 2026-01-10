@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3 text-center text-md-start">Edit User</h4>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('users.update',$user->id) }}" method="POST" autocomplete="off">
@csrf
@method('PUT')

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
</div>

@if(auth()->user()->user_type=='Admin')

<div class="mb-3">
<label>User Type</label>
<select name="user_type" class="form-control">
<option value="Admin" {{ $user->user_type=='Admin'?'selected':'' }}>Admin</option>
<option value="Subadmin" {{ $user->user_type=='Subadmin'?'selected':'' }}>Subadmin</option>
</select>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="changePasswordCheck">
    <label class="form-check-label" for="changePasswordCheck">
        Change Password
    </label>
</div>

<div id="passwordBox" style="display:none;">
    <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="password" class="form-control" autocomplete="new-password">
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
    </div>
</div>

@else
<input type="hidden" name="user_type" value="{{ $user->user_type }}">
@endif

<div class="d-flex gap-2">
<button class="btn btn-primary">Update</button>
<a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>

</form>

</div>
</div>

</div>
</div>

</div>
</div>
</div>

@endsection

@section('script')
<script>
document.getElementById('changePasswordCheck')?.addEventListener('change', function() {
    document.getElementById('passwordBox').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
