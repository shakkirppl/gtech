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

<div class="table-responsive">
<table class="table table-bordered" id="student-table">
<thead class="table-light">
<tr>
<th>#</th>
<th>Admission Date</th>
<th>Reg No</th>
<th>Name</th>
<th>Course</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@forelse($students as $key=>$s)
<tr>
<td>{{ $students->firstItem() + $key }}</td>
<td>{{ $s->admission_date->format('d-m-Y') }}</td>
<td>{{ $s->reg_no }}</td>
<td>{{ $s->name }}</td>
<td>{{ $s->course->name }}</td>
<td class="text-center">
    @if($s->status)
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
</td>

<td class="text-center">
<form action="{{ route('students.status', $s->id) }}"
      method="POST"
      onsubmit="return confirm('Change student status?')">
    @csrf
    @method('PATCH')

    <button class="btn btn-sm {{ $s->status ? 'btn-danger' : 'btn-success' }}">
        {{ $s->status ? 'Deactivate' : 'Activate' }}
    </button>
</form>
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
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportExcel() {
    let table = document.getElementById("student-table");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Students" });
    XLSX.writeFile(wb, "student_date_wise.xlsx");
}
</script>
@endpush
