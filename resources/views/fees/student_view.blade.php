@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Fee Details</h4>

<a href="{{ route('fees.report.student') }}" class="btn btn-secondary mb-3">
    ← Back
</a>

<div class="row">
    <div class="col-md-6">
         <p><strong>Sl No:</strong> {{ $student->id }}</p>
        <p><strong>Reg No:</strong> {{ $student->reg_no }}</p>
        <p><strong>Name:</strong> {{ $student->name }}</p>
        <p><strong>DOJ:</strong> {{ $student->admission_date }}</p>
        <p><strong>Course:</strong> {{ $student->course->name ?? '-' }}</p>
        <p><strong>Scheme:</strong> {{ $student->scheme->name ?? '-' }}</p>
         <p><strong>Mobile No:</strong> {{ $student->phone ?? '-' }}</p>
         <p><strong>Narration:</strong> {{ $student->narration ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <p><strong>Course Fee:</strong> {{ number_format($student->course_fee,2) }}</p>
        <p><strong>Exam Fee:</strong> {{ number_format($student->exam_fee,2) }}</p>
        <p><strong>Material Fee:</strong> {{ number_format($student->material_fee,2) }}</p>
        <p><strong>Voucher Fee:</strong> {{ number_format($student->voucher_fee,2) }}</p>
         <p><strong>Others Fee:</strong> {{ number_format($student->others_fee,2) }}</p>
        <p><strong>Total Fee:</strong> {{ number_format($student->total_fees,2) }}</p>
        <p><strong>Paid:</strong> {{ number_format($paid,2) }}</p>
        <p><strong>Balance:</strong> {{ number_format($balance,2) }}</p>
        <p><strong>Status:</strong> {{ $student->status }}</p>
    </div>
</div>

<hr>

<form method="GET" class="mb-3">
    <label>Filter Fees Type</label>
    <select name="fees_type" class="form-control w-25" onchange="this.form.submit()">
        @foreach(['All','course_fee','exam_fee','material_fee','voucher_fee','others_fee'] as $type)
            <option value="{{ $type }}" {{ $feesType==$type?'selected':'' }}>
                {{ ucfirst(str_replace('_',' ',$type)) }}
            </option>
        @endforeach
    </select>
</form>

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Voucher No</th>
            <th>Fees Type</th>
            <th class="text-end">Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse($feesCollections as $f)
        <tr>
            <td>{{ $f->date }}</td>
            <td>{{ $f->voucher_no }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$f->fees_type)) }}</td>
            <td class="text-end">{{ number_format($f->amount,2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">No records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

</div>
</div>

</div>
</div>
</div>

@endsection
