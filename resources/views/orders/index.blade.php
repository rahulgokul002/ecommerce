@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4">My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Order #{{ $order->id }}</span>
                    <span>Status: <strong>{{ $order->status }}</strong></span>
                </div>
                <div class="card-body">
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $orderTotal = 0; @endphp
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" width="50" class="rounded">
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @php $orderTotal += $item->price * $item->quantity; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2 text-end">
                        <strong>Order Total: ₹{{ number_format($orderTotal, 2) }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted">You have no orders yet.</p>
    @endif
</div>
@endsection
