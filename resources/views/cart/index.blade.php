@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4">My Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($cartItems->count() > 0)
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" width="70" class="rounded">
                            @endif
                        </td>
                        <td>₹{{ number_format($item->product->price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm me-1" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>₹{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        <td>
                            <a href="{{ route('cart.remove', $item->id) }}" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    @php $grandTotal += $item->product->price * $item->quantity; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <strong>Total: ₹{{ number_format($grandTotal, 2) }}</strong>

            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Continue Shopping</a>

                @if($cartItems->count() > 0)
                    <form action="{{ route('order.place') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Place Order</button>
                    </form>
                @endif
            </div>
        </div>



    @else
        <p class="text-muted">Your cart is empty.</p>
    @endif
</div>
@endsection
