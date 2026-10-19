<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    // 入出庫フォーム表示
    public function create(Product $product)
    {
        return view('stock.create', compact('product'));
    }

    // 入出庫の記録
    public function store(StockMovementRequest $request, Product $product)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $product) {
            // 行をロックして最新の在庫数を取得（同時操作での食い違い防止）
            $product = Product::lockForUpdate()->findOrFail($product->id);

            if ($data['type'] === 'out' && $product->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "在庫が足りません（現在の在庫: {$product->stock_quantity}）",
                ]);
            }

            StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => $data['type'],
                'quantity'   => $data['quantity'],
                'memo'       => $data['memo'] ?? null,
            ]);

            if ($data['type'] === 'in') {
                $product->increment('stock_quantity', $data['quantity']);
            } else {
                $product->decrement('stock_quantity', $data['quantity']);
            }
        });

        return redirect()
            ->route('products.show', $product)
            ->with('success', '在庫を更新しました');
    }
}
