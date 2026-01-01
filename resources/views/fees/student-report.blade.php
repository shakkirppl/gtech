@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Student Fees Report (Student Wise)</h4>

{{-- 🔍 Search --}}
<form method="GET" action="{{ route('fees.report.student') }}" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Search Name / Reg No / Course">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('fees.report.student') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>
<div class="col-md-2 align-self-end">
<button type="button" id="exportBtn" class="btn btn-success btn-sm w-100">
    <i class="fa fa-file-excel-o"></i> Export
</button>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped" id="student-table">
    <thead>
        <tr>
            <th>Sl No</th>
            <th>Reg No</th>
            <th>Name</th>
             <th>Mobile</th>
            <th>DOJ</th>
            <th>Course</th>
            <th>Scheme</th>
            <th>Total Fee</th>
            <th>Paid</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $student)
        @php
            $paid = $student->fees_collections()->sum('amount');
            $balance = $student->total_fees - $paid;
        @endphp
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->reg_no }}</td>
            <td>{{ $student->name }}</td>
              <td>{{ $student->phone }}</td>
            <td>{{ \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') }}</td>
            <td>{{ $student->course->name ?? '-' }}</td>
            <td>{{ $student->scheme->name ?? '-' }}</td>
            <td>{{ number_format($student->total_fees,2) }}</td>
            <td>{{ number_format($paid,2) }}</td>
            <td>{{ number_format($balance,2) }}</td>
            <td>{{ $student->status }}</td>
            <td>
               <a href="{{ route('fees.report.student.view', $student->id) }}"
   class="btn btn-info btn-sm">
    <i class="fa fa-eye"></i> View
</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" class="text-center">No records found</td>
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

<!-- Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Student Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="studentDetails"></div>
        <div class="mt-3">
            <label>Filter Fees Type</label>
            <select id="filterFeesType" class="form-control w-25 mb-2">
                <option value="All">All</option>
                <option value="course_fee">Course Fee</option>
                <option value="exam_fee">Exam Fee</option>
                <option value="material_fee">Material Fee</option>
                <option value="voucher_fee">Voucher Fee</option>
                <option value="others_fee">Others Fee</option>
            </select>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Voucher No</th>
                            <th>Fees Type</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="feesCollectionBody"></tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){

    function loadStudent(id, feesType='All'){
       $.get("{{ route('students.details', ':id') }}".replace(':id', id), {
    fees_type: feesType
}, function(res){
            $('#studentDetails').html(`
                <p><strong>Reg No:</strong> ${res.reg_no}</p>
                <p><strong>Name:</strong> ${res.name}</p>
                <p><strong>DOJ:</strong> ${res.admission_date}</p>
                <p><strong>Course:</strong> ${res.course?.name || '-'}</p>
                <p><strong>Scheme:</strong> ${res.scheme?.name || '-'}</p>
                <p><strong>Total Fee:</strong> ${parseFloat(res.total_fees).toFixed(2)}</p>
                <p><strong>Paid:</strong> ${parseFloat(res.paid).toFixed(2)}</p>
                <p><strong>Balance:</strong> ${parseFloat(res.balance).toFixed(2)}</p>
                <p><strong>Status:</strong> ${res.status}</p>
            `);

            let html = '';
            res.fees_collections.forEach(f => {
                html += `<tr>
                    <td>${f.date}</td>
                    <td>${f.voucher_no}</td>
                    <td>${f.fees_type.replace('_',' ')}</td>
                    <td class="text-end">${parseFloat(f.amount).toFixed(2)}</td>
                </tr>`;
            });
            $('#feesCollectionBody').html(html);
        });
    }

    $('.view-student').click(function(){
        let id = $(this).data('id');
        $('#filterFeesType').val('All');
        loadStudent(id);
        $('#studentModal').modal('show');

        $('#filterFeesType').off('change').on('change', function(){
            loadStudent(id, $(this).val());
        });
    });

});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const exportBtn = document.getElementById("exportBtn");
    if (!exportBtn) return;

    exportBtn.addEventListener("click", function () {

        const table = document.getElementById("student-table");
        if (!table) {
            alert("No data available to export");
            return;
        }

        const wb = XLSX.utils.table_to_book(table, { sheet: "Fees Report" });
        XLSX.writeFile(wb, "fees_report.xlsx");
    });

});
</script>
@endsection
