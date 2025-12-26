@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<h4 class="mb-3">Fees Collection</h4>

<form action="{{ route('fees.store') }}" method="POST">
@csrf

{{-- STUDENT --}}
<div class="mb-3">
<label class="form-label">Select Student</label>
<select id="student" class="form-control select2" name="student_id"></select>

</div>

{{-- STUDENT INFO --}}
<div class="row mb-3">
    <div class="col">Name: <b id="name"></b></div>
    <div class="col">Mobile: <b id="phone"></b></div>
    <div class="col">Course: <b id="course"></b></div>
    <div class="col">Scheme: <b id="scheme"></b></div>
</div>

{{-- FEES --}}
<div class="row mb-3">
    <div class="col">
        <label>Total Fees</label>
        <input id="total_fees" class="form-control" readonly>
    </div>
    <div class="col">
        <label>Paid Fees</label>
        <input id="paid_fees" class="form-control" readonly>
    </div>
    <div class="col">
        <label>Balance</label>
        <input id="balance" class="form-control" readonly>
    </div>
</div>

{{-- VOUCHER --}}
<div class="row mb-3">
     <div class="col">
<label>Voucher No</label>
 <input name="voucher_no" class="form-control" 
           value="V{{ str_pad($voucherNo, 6, '0', STR_PAD_LEFT) }}" readonly>
           </div>
            <div class="col">
                {{-- DATE --}}
<div class="mb-3">
<label>Date</label>
<input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
</div>
                </div>
</div>



{{-- AMOUNT --}}
<div class="mb-3">
<label>Amount</label>
<input type="number" id="amount" name="amount" class="form-control" min="0">
</div>

<button class="btn btn-primary">Submit</button>
<a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>

</form>

</div>
</div>

</div>
</div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function () {

    $('#student').select2({
        placeholder: "Search Student...",
        allowClear: true,
        width: '100%'
    });

    let totalFees = 0;
    let paidFees  = 0;

    // STUDENT CHANGE
    $('#student').on('change', function () {

        let opt = this.options[this.selectedIndex];
        let studentId = this.value;

        if (!studentId) return;

        totalFees = parseFloat(opt.dataset.total) || 0;

        $('#name').text(opt.dataset.name || '');
        $('#phone').text(opt.dataset.phone || '');
        $('#course').text(opt.dataset.course || '');
        $('#scheme').text(opt.dataset.scheme || '');
        $('#total_fees').val(totalFees);

        $('#amount').val('');
        $('#paid_fees').val('');
        $('#balance').val('');

        // AJAX → PAID FEES
        $.get("{{ route('fees.paid', '') }}/" + studentId, function (res) {
            paidFees = parseFloat(res.paid_fees) || 0;
            $('#paid_fees').val(paidFees);
            calculateBalance();
        });

    });

    // AMOUNT CHANGE
    $('#amount').on('input', calculateBalance);

    function calculateBalance() {
        let amt = parseFloat($('#amount').val()) || 0;
        let balance = totalFees - paidFees - amt;
        $('#balance').val(balance >= 0 ? balance : 0);
    }

});
</script>

<script>
$(document).ready(function () {

    let totalFees = 0;
    let paidFees  = 0;

    $('#student').select2({
        placeholder: "Search student (name / reg / phone)",
        minimumInputLength: 2,
        ajax: {
            url: "{{ route('students.search') }}",
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        }
    });

    // Student selected
    $('#student').on('select2:select', function (e) {
        let studentId = e.params.data.id;

        // Load student details
        $.get("{{ url('students') }}/" + studentId, function (s) {

            totalFees = parseFloat(s.total_fees) || 0;

            $('#name').text(s.name);
            $('#phone').text(s.phone);
            $('#course').text(s.course?.name || '');
            $('#scheme').text(s.scheme?.name || '');
            $('#total_fees').val(totalFees);

            $('#amount').val('');
            $('#paid_fees').val('');
            $('#balance').val('');

            // Paid fees
            $.get("{{ route('fees.paid', '') }}/" + studentId, function (res) {
                paidFees = parseFloat(res.paid_fees) || 0;
                $('#paid_fees').val(paidFees);
                calculateBalance();
            });
        });
    });

    $('#amount').on('input', calculateBalance);

    function calculateBalance() {
        let amt = parseFloat($('#amount').val()) || 0;
        $('#balance').val(Math.max(totalFees - paidFees - amt, 0));
    }

});
</script>

@endsection
