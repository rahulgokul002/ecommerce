@extends('layouts.app')
@section('content')
<div class="text-center">
    <h1 class="fw-bold">Admin Dashboard</h1>
    <p class="text-muted">Welcome, {{ Auth::user()->name }}</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary w-100">Manage Products</a>
        </div>
        <div class="col-md-4">
            <a href="{{ url('/admin/reports') }}" class="btn btn-success w-100">View Reports</a>
        </div>
        <div class="col-md-4">
            <a href="{{ url('/') }}" class="btn btn-secondary w-100">Go to Shop</a>
        </div>
    </div>
</div>
@endsection
