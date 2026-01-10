@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
<h4 class="mb-0">Users</h4>
@if(auth()->user()->user_type=='Admin')
<a href="{{ route('users.create') }}" 
class="btn btn-success btn-sm px-3">
<i class="fa fa-plus"></i> Add User
</a>
@endif
</div>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif



<table class="table">
@foreach($users as $user)
<tr>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->user_type }}</td>
<td>
<a href="{{ route('users.edit',$user->id) }}" class="btn btn-warning btn-sm">Edit</a>
</td>
</tr>
@endforeach
</table>

</div>



</div>
</div>

</div>
</div>
</div>

@endsection
