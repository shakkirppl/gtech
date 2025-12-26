@extends('layouts.layout')

@section('content')
<div class="main-panel">
<div class="content-wrapper">

{{-- HEADER --}}
<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="font-weight-bold">Welcome {{ $name }}</h3>
        <p class="text-muted">Dashboard overview</p>
    </div>
    <div class="col-md-4 text-right">
        <button class="btn btn-sm btn-light bg-white">
            <i class="mdi mdi-calendar"></i> {{ $now }}
        </button>
    </div>
</div>

{{-- STUDENT COUNTS --}}
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card card-tale">
            <div class="card-body">
                <p>Total Students</p>
                <h3>{{ $totalStudents }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-dark-blue">
            <div class="card-body">
                <p>Active Students</p>
                <h3>{{ $activeStudents }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-light-blue">
            <div class="card-body">
                <p>Inactive Students</p>
                <h3>{{ $inactiveStudents }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-light-danger">
            <div class="card-body">
                <p>Due Amount</p>
                <h3>₹ {{ number_format($dueAmount,2) }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- ADMISSION COUNTS --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <p>Today Admissions</p>
                <h2>{{ $todayAdmissions }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <p>This Month Admissions</p>
                <h2>{{ $monthAdmissions }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- FEES COLLECTION --}}
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <p>Today Collection</p>
                <h3>₹ {{ number_format($todayCollection,2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <p>This Week</p>
                <h3>₹ {{ number_format($weekCollection,2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <p>This Month</p>
                <h3>₹ {{ number_format($monthCollection,2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <p>Overall Collection</p>
                <h3>₹ {{ number_format($overallCollection,2) }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- CHART --}}
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5>Last 7 Days Fees Collection</h5>
                <canvas id="feesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- RECENT COLLECTIONS --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Recent Fees Collection</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentCollections as $c)
                            <tr>
                                <td>{{ $c->student->name }}</td>
                                <td>{{ $c->date }}</td>
                                <td>₹ {{ number_format($c->amount,2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No records</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<footer class="footer mt-4">
    <div class="text-center text-muted">
        © {{ date('Y') }} Spanixo LLP. All rights reserved.
    </div>
</footer>

</div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = @json($chartData->pluck('day'));
const data = @json($chartData->pluck('total'));

new Chart(document.getElementById('feesChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Fees Collection',
            data: data,
            fill: true,
            tension: 0.4,
            borderWidth: 2
        }]
    }
});
</script>
@endsection
