@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Fees Report (Date Wise)</h4>

<form method="GET" action="{{ route('fees.report') }}" class="row g-2 mb-4">

<div class="col-md-4">
<label>From Date</label>
<input type="date" name="from_date" class="form-control"
       value="{{ $from_date }}">
</div>

<div class="col-md-4">
<label>To Date</label>
<input type="date" name="to_date" class="form-control"
       value="{{ $to_date }}">
</div>

<div class="col-md-2 align-self-end">
<button class="btn btn-primary btn-sm w-100">
<i class="fa fa-search"></i> Search
</button>
</div>

<div class="col-md-2 align-self-end">
<a href="{{ route('fees.report.student') }}" class="btn btn-success btn-sm w-100">
<i class="fa fa-user"></i> Student Report
</a>
</div>

</form>

@if(isset($reports))
<div class="table-responsive">
<table class="table table-bordered">
<thead class="table-light">
<tr>
<th>#</th>
<th>Date</th>
<th>Student</th>
<th>Voucher No</th>
<th class="text-end">Amount</th>
</tr>
</thead>
<tbody>
@forelse($reports as $key=>$r)
<tr>
<td>{{ $reports->firstItem() + $key }}</td>
<td>{{ $r->date }}</td>
<td>{{ $r->student->name ?? '-' }}</td>
<td>{{ $r->voucher_no }}</td>
<td class="text-end">{{ number_format($r->amount,2) }}</td>
</tr>
@empty
<tr>
<td colspan="5" class="text-center">No records found</td>
</tr>
@endforelse
</tbody>
<tfoot>
<tr>
<th colspan="4" class="text-end">Total</th>
<th class="text-end">{{ number_format($total,2) }}</th>
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
