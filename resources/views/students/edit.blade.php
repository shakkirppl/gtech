@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Edit Student</h4>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('students.update',$student->id) }}" method="POST">
@csrf
@method('PUT')
    <label class="form-label">Registration No</label>
<input class="form-control mb-2" name="reg_no"
value="{{ old('reg_no',$student->reg_no) }}" placeholder="Reg No">
    <label class="form-label">Student Name</label>
<input class="form-control mb-2" name="name"
value="{{ old('name',$student->name) }}" placeholder="Name">

    <label class="form-label">Address</label>
<textarea class="form-control mb-2" name="address"
placeholder="Address">{{ old('address',$student->address) }}</textarea>

 <label class="form-label">Phone</label>
<input class="form-control mb-2" name="phone"
value="{{ old('phone',$student->phone) }}" placeholder="Phone">

    <label class="form-label">Qualification</label>
<input class="form-control mb-2" name="qualification"
value="{{ old('qualification',$student->qualification) }}" placeholder="Qualification">

    <label class="form-label">Admission Date</label>
<input type="date" class="form-control mb-2" name="admission_date"
value="{{ old('admission_date',$student->admission_date) }}">

    <label class="form-label">Course</label>
<select class="form-control mb-2" name="course_id">
@foreach($courses as $course)
<option value="{{ $course->id }}"
{{ $student->course_id==$course->id?'selected':'' }}>
{{ $course->name }}
</option>
@endforeach
</select>

    <label class="form-label">Scheme</label>
<select class="form-control mb-3" name="scheme_id">
@foreach($schemes as $scheme)
<option value="{{ $scheme->id }}"
{{ $student->scheme_id==$scheme->id?'selected':'' }}>
{{ $scheme->name }}
</option>
@endforeach
</select>

    <label class="form-label">Total Fees</label>
<input class="form-control mb-3" name="total_fees"
value="{{ old('total_fees',$student->total_fees) }}" placeholder="Total Fees">

<div class="d-flex gap-2">
<button class="btn btn-primary">Update</button>
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
