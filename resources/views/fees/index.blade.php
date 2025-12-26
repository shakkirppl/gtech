@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between">
<h4>Fees Collection</h4>
<a href="{{ route('fees.create') }}" class="btn btn-success btn-sm">
<i class="fa fa-plus"></i> Add
</a>
</div>

@if(session('success'))
<div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

<table class="table table-bordered" id="fees-table">
<thead>
<tr>
<th>#</th>
<th>Student</th>
<th>Voucher</th>
<th>Date</th>
<th>Amount</th>
<th>Action</th>
</tr>
</thead>
</table>


</div>
</div>

</div>
</div>
</div>

@endsection
@section('script')
<script>
$(function () {
    $('#fees-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('fees.index') }}",
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'student', name: 'student.name' },
            { data: 'voucher', name: 'voucher_no' },
            { data: 'date', name: 'date' },
            { data: 'amount', name: 'amount' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});
</script>
@endsection