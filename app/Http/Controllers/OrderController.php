<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with('items.product')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
       return view('orders.index', compact('orders'));
    }

    public function placeOrder()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return redirect()->back()->with('error', "Not enough stock for {$item->product->name}");
            }
            $totalAmount += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'Pending'
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'subtotal' => $item->product->price * $item->quantity,
                
            ]);

            $item->product->quantity -= $item->quantity;
            $item->product->save();
        }

        CartItem::where('user_id', $user->id)->delete();
        Mail::to($user->email)->send(new OrderConfirmationMail($order));

        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }
    public function apiPlace()
    {
        $user = auth()->user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty.'], 400);
        }

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return response()->json([
                    'error' => "Not enough stock for {$item->product->name}"
                ], 400);
            }
            $totalAmount += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'Pending'
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'subtotal' => $item->product->price * $item->quantity,
            ]);

            $item->product->quantity -= $item->quantity;
            $item->product->save();
        }

        CartItem::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => [
                'id' => $order->id,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at,
            ]
        ], 201);
    }


}
