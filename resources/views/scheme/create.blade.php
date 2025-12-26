@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3 text-center text-md-start">Create Scheme</h4>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('scheme.store') }}" method="POST">
@csrf

<div class="mb-3">
<label class="form-label required">Scheme Name</label>
<input type="text" name="name" class="form-control"
value="{{ old('name') }}" required>
@error('name')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-3">
<label class="form-label">Description</label>
<textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
</div>

<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-control">
<option value="1">Active</option>
<option value="0">Deactive</option>
</select>
</div>

<div class="d-flex flex-column flex-md-row gap-2">
<button class="btn btn-primary w-100 w-md-auto">Submit</button>
<a href="{{ route('scheme.index') }}" class="btn btn-secondary w-100 w-md-auto">Back</a>
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
