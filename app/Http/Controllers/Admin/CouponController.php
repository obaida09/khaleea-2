<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CouponController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('can:edit-coupons', only: ['update']),
            new Middleware('can:delete-coupons', only: ['destroy']),
            new Middleware('can:create-coupons', only: ['store']),
            new Middleware('can:view-coupons', only: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $sortField = $request->input('sort_by', 'id'); // Default sort by 'id'
        $sortOrder = $request->input('sort_order', 'asc'); // Default order 'asc'

        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $coupons = $query->orderBy($sortField, $sortOrder)->paginate(10);

        return response()->json([
            'data' => CouponResource::collection($coupons),
        ], 200);
    }

    public function store(StoreCouponRequest $request)
    {
        Coupon::create($request->all());

        return response()->json([
            'message' => 'Coupon Created',
        ], 201);
    }

    public function show(Coupon $coupon)
    {
        return response()->json([
            'data' =>  new CouponResource($coupon),
            'message' => 'Coupon Getted',
        ], 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($request->all());

        return response()->json([
            'data' => new CouponResource($coupon),
            'message' => 'Coupon Updated',
        ], 201);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(null, 204);
    }
}