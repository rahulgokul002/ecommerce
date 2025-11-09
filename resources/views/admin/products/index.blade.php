@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            + Add New Product
        </a>
    </div>

    {{-- Products Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product List</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-striped">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-center">{{ $product->id }}</td>
                            <td class="text-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="60" height="60" class="rounded border">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td style="max-width: 250px; white-space: normal;">{{ Str::limit($product->description, 80, '...') }}</td>
                            <td class="text-end">₹{{ number_format($product->price, 2) }}</td>
                            <td class="text-center">{{ $product->quantity }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info text-white">View</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No products available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
