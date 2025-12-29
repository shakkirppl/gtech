@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Wise Fees Report</h4>

{{-- Search Form --}}
<form method="GET" action="{{ route('fees.report.student') }}" class="row g-2 mb-4">

<div class="col-md-6">
    <label>Student Name / Reg No</label>
    <input type="text"
           name="student"
           class="form-control"
           placeholder="Type student name or reg no"
           value="{{ $search ?? '' }}"
           required>
</div>

<div class="col-md-2 align-self-end">
    <button class="btn btn-primary btn-sm w-100">
        <i class="fa fa-search"></i> Search
    </button>
</div>

<div class="col-md-2 align-self-end">
    <a href="{{ route('fees.report') }}" class="btn btn-secondary btn-sm w-100">
        <i class="fa fa-calendar"></i> Date Report
    </a>
</div>

</form>

@if($student)
{{-- Student Details --}}
<div class="mb-3">
    <h5>Student Details</h5>
    <table class="table table-bordered w-50">
        <tr>
            <th>Sl No</th>
            <td>{{ $student->id }}</td>
        </tr>
        <tr>
            <th>Reg No</th>
            <td>{{ $student->reg_no }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $student->name }}</td>
        </tr>
        <tr>
            <th>DOJ</th>
            <td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : '-' }}</td>
        </tr>
        <tr>
            <th>Course</th>
            <td>{{ $student->course->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Scheme</th>
            <td>{{ $student->scheme->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Total Fees</th>
            <td>{{ number_format($student->total_fees,2) }}</td>
        </tr>
        <tr>
            <th>Paid</th>
            <td>{{ number_format($student->fees_collections()->sum('amount'),2) }}</td>
        </tr>
        <tr>
            <th>Balance</th>
            <td>{{ number_format($student->total_fees - $student->fees_collections()->sum('amount'),2) }}</td>
        </tr>
    </table>
</div>

{{-- Fees Collection Table --}}
@if($reports && $reports->count())
<div class="table-responsive">
    <h5>Fees Collection Details</h5>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Voucher No</th>
                <th>Date</th>
                <th>Fees Type</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $key => $r)
            <tr>
                <td>{{ $reports->firstItem() + $key }}</td>
                <td>{{ $r->voucher_no }}</td>
                <td>{{ $r->date }}</td>
                <td>{{ str_replace('_',' ', ucfirst($r->fees_type)) }}</td>
                <td class="text-end">{{ number_format($r->amount,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total</th>
                <th class="text-end">{{ number_format($total,2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="mt-3">
        {{ $reports->withQueryString()->links() }}
    </div>
</div>
@endif
@endif

</div>
</div>

</div>
</div>
</div>

@endsection
