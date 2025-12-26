@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Create Student</h4>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Error Message --}}
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('students.store') }}" method="POST">
@csrf

<div class="mb-2">
    <label class="form-label">Registration No</label>
<input class="form-control" name="reg_no" placeholder="Reg No"
value="{{ old('reg_no') }}">
@error('reg_no')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Student Name</label>
<input class="form-control" name="name" placeholder="Name"
value="{{ old('name') }}">
@error('name')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Address</label>
<textarea class="form-control" name="address" placeholder="Address">{{ old('address') }}</textarea>
</div>

<div class="mb-2">
    <label class="form-label">Phone</label>
<input class="form-control" name="phone" placeholder="Phone"
value="{{ old('phone') }}">
@error('phone')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Qualification</label>
<input class="form-control" name="qualification" placeholder="Qualification"
value="{{ old('qualification') }}">
</div>

<div class="mb-2">
    <label class="form-label">Admission Date</label>
<input type="date" class="form-control" name="admission_date"
value="{{ old('admission_date') }}">
@error('admission_date')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Course</label>
<select class="form-control" name="course_id">
<option value="">Select Course</option>
@foreach($courses as $course)
<option value="{{ $course->id }}"
{{ old('course_id')==$course->id?'selected':'' }}>
{{ $course->name }}
</option>
@endforeach
</select>
@error('course_id')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Scheme</label>
<select class="form-control" name="scheme_id">
<option value="">Select Scheme</option>
@foreach($schemes as $scheme)
<option value="{{ $scheme->id }}"
{{ old('scheme_id')==$scheme->id?'selected':'' }}>
{{ $scheme->name }}
</option>
@endforeach
</select>
@error('scheme_id')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Total Fees</label>
<input class="form-control" name="total_fees" placeholder="Total Fees"
value="{{ old('total_fees') }}">
@error('total_fees')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="d-flex gap-2">
<button class="btn btn-primary">Submit</button>
<a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
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
