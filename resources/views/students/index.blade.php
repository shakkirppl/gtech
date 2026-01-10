@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Students</h4>
    <a href="{{ route('students.create') }}" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i> Add Student
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- 🔍 Search --}}
<form method="GET" action="{{ route('students.index') }}" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search Name / Reg No / Course">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('students.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>SlNo</th>
            <th>Reg No</th>
            <th>Name</th>
            <th>Course</th>
            <th>Scheme</th>
            <th>Total Fees</th>
            <th width="120">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->reg_no }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->course->name ?? '-' }}</td>
            <td>{{ $student->scheme->name ?? '-' }}</td>
            <td>{{ number_format($student->total_fees, 2) }}</td>
            <td>
                <a href="{{ route('students.edit', $student->id) }}"
                   class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
@if(auth()->user()->user_type=='Admin')
                <form action="{{ route('students.destroy', $student->id) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $students->withQueryString()->links() }}
</div>

</div>
</div>

</div>
</div>
</div>

@endsection
