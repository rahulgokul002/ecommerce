@extends('layouts.app')

@section('content')
<h2 class="mb-4 fw-bold">Available Products</h2>

<div class="row">
    @foreach($products as $product)
    <div class="col-md-3 mb-4">
        <div class="card p-3">
            <h5 class="fw-bold">{{ $product->name }}</h5>
            <p>{{ $product->description }}</p>
            <p><strong>₹{{ $product->price }}</strong></p>
            <p>Stock: {{ $product->stock }}</p>
            @auth
                @if($product->stock > 0)
                <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary w-100">Add to Cart</a>
                @else
                <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                @endif
            @else
                <a href="{{ url('/login') }}" class="btn btn-outline-primary w-100">Login to Buy</a>
            @endauth
        </div>
    </div>
    @endforeach
</div>
@endsection
