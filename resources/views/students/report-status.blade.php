@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Report – Status Wise</h4>

<form method="GET" class="row g-2 mb-4">

<div class="col-md-2">
<label>Status</label>
<select name="status" class="form-control">
<option value="">All</option>
<option value="Present" {{ request('status')=='Present'?'selected':'' }}>Present</option>
<option value="Leave" {{ request('status')=='Leave'?'selected':'' }}>Leave</option>
<option value="Completed" {{ request('status')=='Completed'?'selected':'' }}>Completed</option>
<option value="Cancelled" {{ request('status')=='Cancelled'?'selected':'' }}>Cancelled</option>
</select>
</div>

<div class="col-md-3">
<label>From Date</label>
<input type="date"
       name="from_date"
       class="form-control"
       value="{{ request('from_date') }}">
</div>

<div class="col-md-3">
<label>To Date</label>
<input type="date"
       name="to_date"
       class="form-control"
       value="{{ request('to_date') }}">
</div>
<div class="col-md-2 align-self-end">
<button class="btn btn-primary btn-sm w-100">
<i class="fa fa-search"></i> Search
</button>
</div>

<div class="col-md-2 align-self-end">
<a href="{{ route('students.status.export', request()->all()) }}"
   class="btn btn-success btn-sm w-100">
   <i class="fa fa-file-excel-o"></i> Export
</a>

</div>

</form>
@if($students->count())
<div class="row mb-3">

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Fees</h6>
                <h5 class="text-primary">
                    {{ number_format($total_fee, 2) }}
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Paid</h6>
                <h5 class="text-success">
                    {{ number_format($total_paid, 2) }}
                </h5>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Balance</h6>
                <h5 class="text-danger">
                    {{ number_format($total_balance, 2) }}
                </h5>
            </div>
        </div>
    </div>

</div>
@endif

<div class="table-responsive">
<table class="table table-bordered" id="student-table">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Sl No</th>
    <th>Reg No</th>
    <th>Name</th>
    <th>Course</th>
    <th>Scheme</th>
    <th>DOJ</th>
    <th class="text-end">Total</th>
    <th class="text-end">Paid</th>
    <th class="text-end">Balance</th>
    <th>Status</th>

</tr>
</thead>
<tbody>
@forelse($students as $key => $s)
@php
    $paid = $s->paid_amount ?? 0;
    $balance = $s->total_fees - $paid;
@endphp
<tr>
    <td>{{ $students->firstItem() + $key }}</td>
    <td>{{ $s->id }}</td>
    <td>{{ $s->reg_no }}</td>
    <td>{{ $s->name }}</td>
    <td>{{ $s->course->name ?? '-' }}</td>
     <td>{{ $s->scheme->name ?? '-' }}</td>
    <td>{{ $s->admission_date->format('d-m-Y') }}</td>

    <td class="text-end">{{ number_format($s->total_fees, 2) }}</td>
    <td class="text-end">{{ number_format($paid, 2) }}</td>
    <td class="text-end">{{ number_format($balance, 2) }}</td>

   <td class="text-center">
    <span class="badge bg-{{ $s->status_badge }}">
        {{ ucfirst($s->status) }}
    </span>
</td>
   
</tr>
@empty
<tr>
    <td colspan="10" class="text-center">No records found</td>
</tr>
@endforelse
</tbody>
</table>
</div>

{{ $students->links() }}

</div>
</div>

</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportExcel() {
    let table = document.getElementById("student-table");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Students" });
    XLSX.writeFile(wb, "student_status_wise.xlsx");
}
</script>
@endsection

