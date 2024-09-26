<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SizesController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('can:edit-tags', only: ['update']),
            new Middleware('can:delete-tags', only: ['destroy']),
            new Middleware('can:create-tags', only: ['store']),
            new Middleware('can:view-tags', only: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {

        $sortField = $request->input('sort_by', 'id'); // Default sort by 'id'
        $sortOrder = $request->input('sort_order', 'asc'); // Default order 'asc'

        $query = Size::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $Sizes = $query->orderBy($sortField, $sortOrder)->paginate(10);
        return SizeResource::collection($Sizes);
    }

    public function store(StoreSizeRequest $request)
    {
        $size = Size::create($request->validated());
        return new SizeResource($size);
    }

    public function show(Size $size)
    {
        return new SizeResource($size->load('products'));
    }

    public function update(UpdateSizeRequest $request, Size $size)
    {
        $size->update($request->validated());
        return new SizeResource($size);
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return response()->noContent();
    }
}
