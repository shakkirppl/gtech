@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between">
<h4>Students</h4>
<a href="{{ route('students.create') }}" class="btn btn-success btn-sm">
<i class="fa fa-plus"></i> Add Student
</a>
</div>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="table-responsive mt-3">
<table class="table table-bordered" id="students-table">
    <thead>
        <tr>
            <th>#</th>
            <th>SL No</th>
            <th>Reg No</th>
            <th>Name</th>
            <th>Course</th>
            <th>Scheme</th>
            <th>Fees</th>
            <th>Action</th>
        </tr>
    </thead>
</table>
</div>



</div>
</div>

</div>
</div>
</div>

@endsection
@section('script')
<script>
$(function () {
$('#students-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('students.index') }}",
        type: "GET"
    },
    columns: [
        { data: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'sl_no' },
        { data: 'reg_no' },
        { data: 'name' },
        { data: 'course', name: 'course.name' },
        { data: 'scheme', name: 'scheme.name' },
        { data: 'total_fees' },
        { data: 'action', orderable:false, searchable:false }
    ]
});
});
</script>
@endsection