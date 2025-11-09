@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Product</h2>

    {{-- Success or error messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Product Name</label>
                    <input type="text" name="name" id="name"
                           class="form-control"
                           value="{{ old('name', $product->name) }}"
                           required>
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label fw-bold">Price (₹)</label>
                    <input type="number" name="price" id="price"
                           class="form-control"
                           value="{{ old('price', $product->price) }}"
                           step="0.01"
                           required>
                </div>

                {{-- Quantity --}}
                <div class="mb-3">
                    <label for="quantity" class="form-label fw-bold">Quantity</label>
                    <input type="number" name="quantity" id="quantity"
                           class="form-control"
                           value="{{ old('quantity', $product->quantity) }}"
                           required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Current Image Preview --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Current Image</label><br>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100" class="rounded mb-2 border">
                    @else
                        <p class="text-muted">No image uploaded</p>
                    @endif
                </div>

                {{-- New Image Upload --}}
                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Change Product Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave blank to keep existing image.</small>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        ← Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
 