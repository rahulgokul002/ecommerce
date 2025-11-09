@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4">Admin Dashboard</h2>

    <div class="row text-center mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="text-muted">Total Orders</h5>
                    <h2 class="fw-bold text-success">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="text-muted">Total Revenue</h5>
                    <h2 class="fw-bold text-primary">₹{{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    @if($revenueByDate->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Revenue Over Time</h5>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    @endif
</div>

{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($revenueByDate->pluck('date')),
        datasets: [{
            label: 'Revenue (₹)',
            data: @json($revenueByDate->pluck('revenue')),
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
