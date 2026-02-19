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


<div class="row mb-3">

    {{-- AMOUNT --}}
    <div class="col-md-6">
        <label class="form-label">Amount</label>
        <input type="number" id="amount" name="amount"
               class="form-control" min="0">
    </div>

    {{-- FEES TYPE --}}
    <div class="col-md-6">
        <label class="form-label">Fees Type</label>
        <select name="fees_type" id="fees_type"
                class="form-control" required>
            <option value="">Select Fees Type</option>
            <option value="course_fee">Course Fee</option>
            <option value="exam_fee">Exam Fee</option>
            <option value="material_fee">Material Fee</option>
            <option value="voucher_fee">Voucher Fee</option>
            <option value="others_fee">Others Fee</option>
        </select>
    </div>

</div>
<div class="row mb-3">
    <div class="col">
        <label>Total </label>
        <input id="total_fees_type" class="form-control" readonly>
    </div>
    <div class="col">
        <label>Paid </label>
        <input id="paid_fees_type" class="form-control" readonly>
    </div>
    <div class="col">
        <label>Balance</label>
        <input id="balance_type" class="form-control" readonly>
    </div>
</div>
<button class="btn btn-primary">Submit</button>
<a href="{{ route('fees.index') }}" class="btn btn-secondary">Back</a>

</form>
<hr>
<h5>Previous Fee Collections</h5>

<table class="table table-sm table-bordered" id="history-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Fees Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
</div>
</div>

</div>
</div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function () {

    let totalFees = 0;
    let paidFees  = 0;
    let studentId = null;

    /* ===============================
       Helper: Laravel route in JS
    ================================ */
    function routeUrl(url, params = {}) {
        Object.keys(params).forEach(k => {
            url = url.replace(':' + k, params[k]);
        });
        return url;
    }

    /* ===============================
       STUDENT SELECT (Select2)
    ================================ */
    $('#student').select2({
        placeholder: "Search student (name / reg / phone / course)",
        width: '100%',
        minimumInputLength: 2,
        ajax: {
            url: "{{ route('students.search') }}",
            dataType: 'json',
            delay: 300,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        }
    });

    /* ===============================
       ON STUDENT SELECT
    ================================ */
    $('#student').on('select2:select', function (e) {

        studentId = e.params.data.id;

        // Student basic details
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

            // Total paid (all types)
            $.get(
                "{{ route('fees.paid', ':id') }}".replace(':id', studentId),
                function (res) {
                    paidFees = parseFloat(res.paid_fees) || 0;
                    $('#paid_fees').val(paidFees);
                    calculateBalance();
                }
            );

            loadHistory();
        });
    });

    /* ===============================
       FEES TYPE CHANGE (SUMMARY)
    ================================ */
    $('#fees_type').on('change', function () {

        let type = $(this).val();
        if (!type || !studentId) return;

        $.get(
            routeUrl("{{ route('fees.summary', [':id', ':type']) }}", {
                id: studentId,
                type: type
            }),
            function (res) {
                $('#total_fees_type').val(res.total);
                $('#paid_fees_type').val(res.paid);
                $('#balance_type').val(res.balance);
            }
        );
    });

    /* ===============================
       AMOUNT INPUT
    ================================ */
    $('#amount').on('input', calculateBalance);

    function calculateBalance() {
        let total = parseFloat($('#total_fees').val()) || 0;
        let paid  = parseFloat($('#paid_fees').val()) || 0;
        let amt   = parseFloat($('#amount').val()) || 0;
        $('#balance').val(Math.max(total - paid - amt, 0));
    }

    /* ===============================
       FEES HISTORY
    ================================ */
    function loadHistory() {

        $.get(
            "{{ route('fees.history', ':id') }}".replace(':id', studentId),
            function (rows) {

                let html = '';

                if (!rows.length) {
                    html = `<tr><td colspan="3" class="text-center">No records</td></tr>`;
                } else {
                    rows.forEach(r => {
                        html += `
                            <tr>
                                <td>${r.date}</td>
                                <td>${r.fees_type.replace('_',' ')}</td>
                                <td>${parseFloat(r.amount).toFixed(2)}</td>
                            </tr>`;
                    });
                }

                $('#history-table tbody').html(html);
            }
        );
    }

});
</script>
@endsection
