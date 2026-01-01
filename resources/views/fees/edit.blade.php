@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Edit Fees Collection</h4>

<form action="{{ route('fees.update', $fee->id) }}" method="POST">
@csrf
@method('PUT')

{{-- STUDENT INFO (READONLY) --}}
<div class="row mb-3">
    <div class="col">Name: <b>{{ $fee->student->name }}</b></div>
    <div class="col">Course: <b>{{ $fee->student->course->name ?? '-' }}</b></div>
    <div class="col">Scheme: <b>{{ $fee->student->scheme->name ?? '-' }}</b></div>
</div>

{{-- VOUCHER --}}
<div class="mb-3">
<label>Voucher No</label>
<input class="form-control" value="{{ $fee->voucher_no }}" readonly>
</div>

{{-- DATE --}}
<div class="mb-3">
<label>Date</label>
<input type="date" name="date" class="form-control"
       value="{{ $fee->date }}">
</div>

{{-- AMOUNT --}}
<div class="mb-3">
<label>Amount</label>
<input type="number" name="amount" class="form-control"
       value="{{ $fee->amount }}" min="1">
</div>

{{-- FEES TYPE --}}
<div class="mb-3">
<label>Fees Type</label>
<select name="fees_type" class="form-control">
    <option value="course_fee" {{ $fee->fees_type=='course_fee'?'selected':'' }}>Course Fee</option>
    <option value="exam_fee" {{ $fee->fees_type=='exam_fee'?'selected':'' }}>Exam Fee</option>
    <option value="material_fee" {{ $fee->fees_type=='material_fee'?'selected':'' }}>Material Fee</option>
    <option value="voucher_fee" {{ $fee->fees_type=='voucher_fee'?'selected':'' }}>Voucher Fee</option>
    <option value="others_fee" {{ $fee->fees_type=='others_fee'?'selected':'' }}>Others Fee</option>
</select>
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('fees.index') }}" class="btn btn-secondary">Cancel</a>

</form>

</div>
</div>

</div>
</div>
</div>

@endsection
