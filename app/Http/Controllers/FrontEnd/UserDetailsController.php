<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfileRequest;
use App\Http\Resources\FrontEnd\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserDetailsController extends Controller
{
    public function profile()
    {
       $user = new UserResource(Auth::user());
       return $user;
    }

    // Get all orders with their associated products
    public function userOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('products')->paginate(10);
        return OrderResource::collection($orders);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->all();
        unset($data['role']);

        // Add Password to data
        trim($request->password) != '' ? $data['password'] = bcrypt($request->password):'';
        $user->update($data);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User Updated',
        ], 201);
    }

    // Get a specific order with its products
    public function showOrder($id)
    {
        $user = Auth::user();

        // Find the order that belongs to the authenticated user
        $order = Order::with('products')
                      ->where('id', $id)
                      ->where('user_id', $user->id)
                      ->firstOrFail();

        return new OrderResource($order);
    }
}
