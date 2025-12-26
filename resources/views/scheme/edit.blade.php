@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3 text-center text-md-start">Edit Scheme</h4>

<form action="{{ route('scheme.update',$scheme->id) }}" method="POST">
@csrf

<div class="mb-3">
<label class="form-label required">Scheme Name</label>
<input type="text" name="name" class="form-control"
value="{{ old('name',$scheme->name) }}">
</div>

<div class="mb-3">
<label class="form-label">Description</label>
<textarea name="description" class="form-control" rows="3">{{ old('description',$scheme->description) }}</textarea>
</div>

<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-control">
<option value="1" {{ $scheme->status==1?'selected':'' }}>Active</option>
<option value="0" {{ $scheme->status==0?'selected':'' }}>Deactive</option>
</select>
</div>

<div class="d-flex flex-column flex-md-row gap-2">
<button class="btn btn-primary w-100 w-md-auto">Update</button>
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
