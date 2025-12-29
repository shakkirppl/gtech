@extends('layouts.layout')

@section('content')

<style>
/* Desktop only – remove body scroll */
@media (min-width: 992px) {
    body {
        overflow: hidden;
    }

    .content-wrapper {
        height: calc(100vh - 70px); /* adjust header height if needed */
        overflow-y: auto;
    }
}
</style>

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
    <input class="form-control" name="reg_no" value="{{ old('reg_no') }}">
    @error('reg_no')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Student Name</label>
    <input class="form-control" name="name" value="{{ old('name') }}">
    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Address</label>
    <textarea class="form-control" name="address">{{ old('address') }}</textarea>
</div>

<div class="mb-2">
    <label class="form-label">Phone</label>
    <input class="form-control" name="phone" value="{{ old('phone') }}">
    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Qualification</label>
    <input class="form-control" name="qualification" value="{{ old('qualification') }}">
</div>

<div class="mb-2">
    <label class="form-label">Admission Date</label>
    <input type="date" class="form-control" name="admission_date" value="{{ old('admission_date') }}">
    @error('admission_date')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<div class="mb-2">
    <label class="form-label">Course</label>
    <select class="form-control" name="course_id">
        <option value="">Select Course</option>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ old('course_id')==$course->id?'selected':'' }}>
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
            <option value="{{ $scheme->id }}" {{ old('scheme_id')==$scheme->id?'selected':'' }}>
                {{ $scheme->name }}
            </option>
        @endforeach
    </select>
    @error('scheme_id')<small class="text-danger">{{ $message }}</small>@enderror
</div>

<hr>

{{-- Fees --}}
<div class="mb-2">
    <label class="form-label">Course Fees</label>
    <input class="form-control fee-input" id="course_fee" name="course_fee" value="{{ old('course_fee') }}">
</div>

<div class="mb-2">
    <label class="form-label">Exam Fees</label>
    <input class="form-control fee-input" id="exam_fees" name="exam_fees" value="{{ old('exam_fees') }}">
</div>

<div class="mb-2">
    <label class="form-label">Material Fees</label>
    <input class="form-control fee-input" id="material_fee" name="material_fee" value="{{ old('material_fee') }}">
</div>

<div class="mb-2">
    <label class="form-label">Voucher Fees</label>
    <input class="form-control fee-input" id="voucher_fee" name="voucher_fee" value="{{ old('voucher_fee') }}">
</div>

<div class="mb-2">
    <label class="form-label">Other Fees</label>
    <input class="form-control fee-input" id="others_fees" name="others_fees" value="{{ old('others_fees') }}">
</div>

<div class="mb-3">
    <label class="form-label">Total Fees</label>
    <input class="form-control" id="total_fees" name="total_fees" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select class="form-control" name="status">
        <option value="">Select Status</option>
        <option value="Present" {{ old('status')=='Present'?'selected':'' }}>Present</option>
        <option value="Leave" {{ old('status')=='Leave'?'selected':'' }}>Leave</option>
        <option value="Completed" {{ old('status')=='Completed'?'selected':'' }}>Completed</option>
    </select>
    @error('status')<small class="text-danger">{{ $message }}</small>@enderror
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

{{-- Auto Calculate Total Fees --}}
<script>
    function calculateTotalFees() {
        let total = 0;
        document.querySelectorAll('.fee-input').forEach(input => {
            let value = parseFloat(input.value);
            if (!isNaN(value)) total += value;
        });
        document.getElementById('total_fees').value = total.toFixed(2);
    }

    document.querySelectorAll('.fee-input').forEach(input => {
        input.addEventListener('input', calculateTotalFees);
    });

    // Calculate on load (old values)
    calculateTotalFees();
</script>

@endsection
