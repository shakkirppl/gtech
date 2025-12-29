@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Fees Collection</h4>
    <a href="{{ route('fees.create') }}" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i> Add
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- 🔍 Search --}}
<form method="GET" action="{{ route('fees.index') }}" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="form-control"
                   placeholder="Search Student / Voucher">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('fees.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>#</th>
    <th>Student</th>
    <th>Voucher</th>
    <th>Date</th>
    <th>Amount</th>
    <th width="100">Action</th>
</tr>
</thead>
<tbody>
@forelse($collections as $index => $row)
<tr>
    <td>{{ $collections->firstItem() + $index }}</td>
    <td>{{ $row->student->name ?? '-' }}</td>
    <td>{{ $row->voucher_no }}</td>
    <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
    <td>{{ number_format($row->amount, 2) }}</td>
    <td>
        <form action="{{ route('fees.destroy', $row->id) }}"
              method="POST"
              style="display:inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete?')">
                <i class="fa fa-trash"></i>
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

{{-- Pagination --}}
<div class="mt-3">
    {{ $collections->withQueryString()->links() }}
</div>

</div>
</div>

</div>
</div>
</div>

@endsection
