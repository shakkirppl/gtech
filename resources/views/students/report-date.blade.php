@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Report – Date Wise</h4>

<form method="GET" class="row g-2 mb-4">

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

</form>
<div class="col-md-2 align-self-end">
<button type="button" onclick="exportExcel()" class="btn btn-success btn-sm w-100">
<i class="fa fa-file-excel-o"></i> Export
</button>
</div>
<div class="table-responsive">
<table class="table table-bordered" id="student-table">
<thead class="table-light">
<tr>
<th>SlNo</th>
<th>DOJ</th>
<th>Reg No</th>
<th>Name</th>
<th>Course</th>
<th>Scheme</th>
<th>Total Fee</th>
<th>Paid</th>
<th>Balance</th>
<th>Status</th>
</tr>
</thead>
<tbody>
@forelse($students as $key=>$s)
@php
    $paid = $s->paid_amount ?? 0;
    $balance = $s->total_fees - $paid;
@endphp
<tr>
<td>{{ $s->id }}</td>
<td>{{ $s->admission_date->format('d-m-Y') }}</td>
<td>{{ $s->reg_no }}</td>
<td>{{ $s->name }}</td>
<td>{{ $s->course->name }}</td>
 <td>{{ $s->scheme->name ?? '-' }}</td>
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
<td colspan="6" class="text-center">No records found</td>
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
    XLSX.writeFile(wb, "student_date_wise.xlsx");
}
</script>
@endsection

