@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
<h4 class="mb-0">Scheme</h4>
<a href="{{ route('scheme.create') }}" 
class="btn btn-success btn-sm px-3">
<i class="fa fa-plus"></i> Add Scheme
</a>
</div>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="table-responsive mt-3">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr>
<th>#</th>
<th>Name</th>
<th>Status</th>
<th class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($schemes as $key=>$scheme)
<tr>
<td>{{ $schemes->firstItem() + $key }}</td>
<td>{{ $scheme->name }}</td>
<td>
<span class="badge {{ $scheme->status ? 'bg-success' : 'bg-danger' }}">
{{ $scheme->status ? 'Active' : 'Deactive' }}
</span>
</td>
<td class="text-center">

<div class="d-flex justify-content-center gap-1 flex-wrap">
<a href="{{ route('scheme.edit',$scheme->id) }}"
class="btn btn-warning btn-sm px-2">
<i class="fa fa-edit"></i>
</a>

<form action="{{ route('scheme.destroy',$scheme->id) }}" method="POST">
@csrf
@method('DELETE')
<button class="btn btn-danger btn-sm px-2"
onclick="return confirm('Are you sure?')">
<i class="fa fa-trash"></i>
</button>
</form>
</div>

</td>
</tr>
@empty
<tr>
<td colspan="4" class="text-center">No records found</td>
</tr>
@endforelse

</tbody>
</table>
</div>

<div class="d-flex justify-content-center mt-3">
{{ $schemes->links() }}
</div>

</div>
</div>

</div>
</div>
</div>

@endsection
