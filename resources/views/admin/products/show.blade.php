@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Product Details</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h4 class="card-title">{{ $product->name }}</h4>
            <h6 class="text-muted">Product ID: {{ $product->id }}</h6>
            <hr>

            <p><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            @if($product->description)
                <p><strong>Description:</strong><br>{{ $product->description }}</p>
            @else
                <p class="text-muted fst-italic">No description available.</p>
            @endif

            @if($product->image)
                <div class="mt-3">
                    <strong>Image:</strong><br>
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow-sm mt-2" 
                         style="max-width: 300px;">
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    Back to List
                </a>

                <div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                          method="POST" 
                          style="display:inline;">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
