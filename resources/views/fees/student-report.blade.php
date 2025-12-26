@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Fees Report (Student Wise)</h4>

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

@if($student && isset($reports))
<div class="alert alert-info">
<strong>Student:</strong> {{ $student->name }} ({{ $student->reg_no }})
</div>
@endif

@if(isset($reports))
<div class="table-responsive">
<table class="table table-bordered">
<thead class="table-light">
<tr>
<th>#</th>
<th>Date</th>
<th>Voucher No</th>
<th class="text-end">Amount</th>
</tr>
</thead>
<tbody>
@forelse($reports as $key=>$r)
<tr>
<td>{{ $reports->firstItem() + $key }}</td>
<td>{{ $r->date }}</td>
<td>{{ $r->voucher_no }}</td>
<td class="text-end">{{ number_format($r->amount,2) }}</td>
</tr>
@empty
<tr>
<td colspan="4" class="text-center">No records found</td>
</tr>
@endforelse
</tbody>
<tfoot>
<tr>
<th colspan="3" class="text-end">Total</th>
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
