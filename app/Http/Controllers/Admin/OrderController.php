<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('can:edit-orders', only: ['update']),
            new Middleware('can:delete-orders', only: ['destroy']),
            new Middleware('can:create-orders', only: ['store']),
            new Middleware('can:view-orders', only: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $sortField = $request->input('sort_by', 'id'); // Default sort by 'id'
        $sortOrder = $request->input('sort_order', 'asc'); // Default order 'asc'

        $query = Order::query();

        $order = $query->orderBy($sortField, $sortOrder)->paginate(10);
        return OrderResource::collection($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_price = 0; // This will be calculated
        $order->status = 'pending'; // Example status
        $order->save();

        $totalPrice = 0;
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $quantity = $productData['quantity'];
            $order->products()->attach($product->id, ['quantity' => $quantity]);
            $totalPrice += $product->price * $quantity;
        }

        // Update total price
        $order->total_price = $totalPrice;

        // Apply coupon if provided
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            $order->applyCoupon($coupon);
        }

        $order->save();
        return new OrderResource($order->load('products', 'coupon'));
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return new OrderResource($order);
    }

    public function destroy(string $id)
    {
        //
    }
}
