@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Fees Report (Date Wise)</h4>

<form method="GET" action="{{ route('fees.report') }}" class="row g-2 mb-4">

    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control" value="{{ $from_date }}">
    </div>

    <div class="col-md-3">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control" value="{{ $to_date }}">
    </div>

    <div class="col-md-3">
        <label>Fees Type</label>
        <select name="fees_type" class="form-control">
            <option value="All" {{ $fees_type == 'All' ? 'selected' : '' }}>All</option>
            <option value="course_fee" {{ $fees_type == 'course_fee' ? 'selected' : '' }}>Course Fee</option>
            <option value="exam_fee" {{ $fees_type == 'exam_fee' ? 'selected' : '' }}>Exam Fee</option>
            <option value="material_fee" {{ $fees_type == 'material_fee' ? 'selected' : '' }}>Material Fee</option>
            <option value="voucher_fee" {{ $fees_type == 'voucher_fee' ? 'selected' : '' }}>Voucher Fee</option>
            <option value="others_fee" {{ $fees_type == 'others_fee' ? 'selected' : '' }}>Others Fee</option>
        </select>
    </div>

    <div class="col-md-3 align-self-end">
        <button class="btn btn-primary w-100"><i class="fa fa-search"></i> Search</button>
    </div>

</form>

@if(isset($reports))
<div class="table-responsive">
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>DOJ</th>
            <th>Course</th>
            <th>Scheme</th>
            <th>Fees Type</th>
            <th>Voucher No</th>
            <th class="text-end">Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $key => $r)
        <tr>
            <td>{{ $reports->firstItem() + $key }}</td>
            <td>{{ $r->student->name ?? '-' }}</td>
           <td>
    {{ $r->student->admission_date ? \Carbon\Carbon::parse($r->student->admission_date)->format('d M Y') : '-' }}
</td>
            <td>{{ $r->student->course->name ?? '-' }}</td>
            <td>{{ $r->student->scheme->name ?? '-' }}</td>
            <td>{{ str_replace('_',' ', ucfirst($r->fees_type)) }}</td>
            <td>{{ $r->voucher_no }}</td>
            <td class="text-end">{{ number_format($r->amount, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">No records found</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-end">Total</th>
            <th class="text-end">{{ number_format($total, 2) }}</th>
        </tr>
    </tfoot>
</table>
</div>

<div class="mt-3">
{{ $reports->withQueryString()->links() }}
</div>
@endif

</div>
</div>

</div>
</div>
</div>

@endsection
