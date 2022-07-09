<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Auth;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function product(Product $product)
    {
        if (Auth::user()->cannot('api', Product::class)) {
            return abort(403);
        }

        $data = [
            'value' => $product->value,
            'name' => $product->name,
            'price' => $product->getRawOriginal('price'),
        ];

        return response()->json($data);
    }

    public function products(Request $request)
    {
        if (Auth::user()->cannot('api', Product::class)) {
            return abort(403);
        }

        $keyword = $request->get('q');

        $data['items'] = Product::active()->where('value', 'like', '%'.$keyword.'%')
        ->orWhere('name', 'like', '%'.$keyword.'%')
        ->select('id', 'value', 'name')->get();
        $data['total_count'] = count($data['items']);

        return response()->json($data);
    }
}
