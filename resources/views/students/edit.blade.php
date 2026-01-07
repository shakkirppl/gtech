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

<form action="{{ route('students.update', $student->id) }}" method="POST">
@csrf
@method('PUT')

<label class="form-label">Sl No</label>
<input class="form-control mb-2" name="sl_no"
value="{{ old('sl_no', $student->id) }}" readonly>

<label class="form-label">Registration No</label>
<input class="form-control mb-2" name="reg_no"
value="{{ old('reg_no', $student->reg_no) }}">

<label class="form-label">Student Name</label>
<input class="form-control mb-2" name="name"
value="{{ old('name', $student->name) }}">

<label class="form-label">Address</label>
<textarea class="form-control mb-2" name="address">{{ old('address', $student->address) }}</textarea>

<label class="form-label">Phone</label>
<input class="form-control mb-2" name="phone"
value="{{ old('phone', $student->phone) }}">

<label class="form-label">Qualification</label>
<input class="form-control mb-2" name="qualification"
value="{{ old('qualification', $student->qualification) }}">

<label class="form-label">Admission Date</label>
<input type="date"
       class="form-control mb-2"
       name="admission_date"
       value="{{ old('admission_date', optional($student->admission_date)->format('Y-m-d')) }}">

<label class="form-label">Course</label>
<select class="form-control mb-2" name="course_id">
@foreach($courses as $course)
<option value="{{ $course->id }}"
{{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
{{ $course->name }}
</option>
@endforeach
</select>

<label class="form-label">Scheme</label>
<select class="form-control mb-3" name="scheme_id">
@foreach($schemes as $scheme)
<option value="{{ $scheme->id }}"
{{ old('scheme_id', $student->scheme_id) == $scheme->id ? 'selected' : '' }}>
{{ $scheme->name }}
</option>
@endforeach
</select>

<hr>

{{-- Fees Section --}}
<label class="form-label">Course Fees</label>
<input class="form-control mb-2 fee-input" id="course_fee" name="course_fee"
value="{{ old('course_fee', $student->course_fee) }}">

<label class="form-label">Exam Fees</label>
<input class="form-control mb-2 fee-input" id="exam_fees" name="exam_fees"
value="{{ old('exam_fees', $student->exam_fee) }}">

<label class="form-label">Material Fees</label>
<input class="form-control mb-2 fee-input" id="material_fee" name="material_fee"
value="{{ old('material_fee', $student->material_fee) }}">

<label class="form-label">Voucher Fees</label>
<input class="form-control mb-2 fee-input" id="voucher_fee" name="voucher_fee"
value="{{ old('voucher_fee', $student->voucher_fee) }}">

<label class="form-label">Other Fees</label>
<input class="form-control mb-3 fee-input" id="others_fees" name="others_fees"
value="{{ old('others_fees', $student->others_fee) }}">

<label class="form-label">Total Fees</label>
<input class="form-control mb-3" id="total_fees" name="total_fees"
value="{{ old('total_fees', $student->total_fees) }}" readonly>

<div class="mb-2">
<label class="form-label">Status</label>
<select class="form-control" name="status">
<option value="">Select Status</option>
<option value="Present" {{ old('status', $student->status) == 'Present' ? 'selected' : '' }}>Present</option>
<option value="Leave" {{ old('status', $student->status) == 'Leave' ? 'selected' : '' }}>Leave</option>
<option value="Completed" {{ old('status', $student->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
<option value="Cancelled" {{ old('status', $student->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
</select>
@error('status')
<small class="text-danger">{{ $message }}</small>
@enderror
</div>
<div class="mb-3">
    <label class="form-label">Narration</label>
    <textarea 
        class="form-control" 
        id="narration" 
        name="narration" 
        rows="3"
    >{{ old('narration', $student->narration) }}</textarea>
</div>

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

{{-- Auto Calculate Total Fees --}}
<script>
function calculateTotalFees() {
    let total = 0;
    document.querySelectorAll('.fee-input').forEach(input => {
        let value = parseFloat(input.value);
        if (!isNaN(value)) {
            total += value;
        }
    });
    document.getElementById('total_fees').value = total.toFixed(2);
}

document.querySelectorAll('.fee-input').forEach(input => {
    input.addEventListener('input', calculateTotalFees);
});

// calculate on page load (important for edit)
calculateTotalFees();
</script>

@endsection
