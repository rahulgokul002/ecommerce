@extends('layouts.app')

@section('content')
<h2 class="mb-4 fw-bold">Available Products</h2>

{{-- 🔍 Filter Form --}}
<form method="GET" action="{{ route('dashboard') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
    </div>
    <div class="col-md-3">
        <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
    </div>
    <div class="col-md-3">
        <select name="availability" class="form-select">
            <option value="">-- Availability --</option>
            <option value="in" {{ request('availability') == 'in' ? 'selected' : '' }}>In Stock</option>
            <option value="out" {{ request('availability') == 'out' ? 'selected' : '' }}>Out of Stock</option>
        </select>
    </div>
    <div class="col-md-3 d-flex">
        <button type="submit" class="btn btn-primary me-2 w-50">Filter</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary w-50">Reset</a>
    </div>
</form>


{{-- 🛍️ Product List --}}
<div class="row">
    @foreach($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card p-3 shadow-sm border-0">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="card-img-top rounded mb-2" 
                     style="height: 180px; object-fit: cover;">
            @endif

            <div class="card-body">
                <h5 class="fw-bold">{{ $product->name }}</h5>
                <p class="text-muted small">{{ $product->description }}</p>
                <p><strong>₹{{ number_format($product->price, 2) }}</strong></p>
                <p>Quantity: {{ $product->quantity }}</p>

                {{-- Show Add to Cart for regular users only --}}
                @auth
                    @if(auth()->user()->is_admin)
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm w-50 me-1">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="w-50 ms-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    @else
                        @if($product->quantity > 0)
                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary w-100 mt-2">Add to Cart</a>
                        @else
                            <button class="btn btn-secondary w-100 mt-2" disabled>Out of Stock</button>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Buy</a>
                @endauth
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
