@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Report – Status Wise</h4>

<form method="GET" class="row g-2 mb-4">

<div class="col-md-4">
<label>Status</label>
<select name="status" class="form-control">
<option value="">All</option>
<option value="Present" {{ request('status')=='Present'?'selected':'' }}>Present</option>
<option value="Leave" {{ request('status')=='Leave'?'selected':'' }}>Leave</option>
<option value="Completed" {{ request('status')=='Completed'?'selected':'' }}>Completed</option>
</select>
</div>

<div class="col-md-2 align-self-end">
<button class="btn btn-primary btn-sm w-100">
<i class="fa fa-search"></i> Search
</button>
</div>

<div class="col-md-2 align-self-end">
<button type="button" onclick="exportExcel()" class="btn btn-success btn-sm w-100">
<i class="fa fa-file-excel-o"></i> Export
</button>
</div>

</form>

<div class="table-responsive">
<table class="table table-bordered" id="student-table">
<thead class="table-light">
<tr>
    <th>Sl No</th>
    <th>Reg No</th>
    <th>Name</th>
    <th>Course</th>
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
    <td>{{ $s->reg_no }}</td>
    <td>{{ $s->name }}</td>
    <td>{{ $s->course->name ?? '-' }}</td>
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
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportExcel() {
    let table = document.getElementById("student-table");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Students" });
    XLSX.writeFile(wb, "student_status_wise.xlsx");
}
</script>
@endpush
