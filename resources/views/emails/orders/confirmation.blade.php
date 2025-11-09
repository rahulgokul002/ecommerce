<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Thank you for your order, {{ $order->user->name }}!</h2>

    <p>Your order has been successfully placed.</p>

    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
    <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>

    <h3>Order Details:</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product_name }} — {{ $item->quantity }} × ₹{{ $item->price }} = ₹{{ $item->subtotal }}
            </li>
        @endforeach
    </ul>

    <p>We’ll notify you when your order ships. Thank you for shopping with us!</p>

    <p>– The {{ config('app.name') }} Team</p>
</body>
</html>
