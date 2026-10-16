<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; //今回のコントローラは ProductRequest を使うので、Request は使いません。使わない use が残っていても、エラーにはなりません。
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    // 在庫少数とみなす基準（この数以下）
    private const LOW_STOCK_THRESHOLD = 10;
    
    /**
     * Display a listing of the resource.
     */
   /**
     * with('category') はカテゴリを事前に取得します（Eager Loading）。これがないと、商品ごとにカテゴリ取得のSQLが走るN+1問題が起きます。
     * when() は、条件が true のときだけ絞り込みを追加します。
     * latest() は新しい順に並べます。
     * paginate(10) は10件ごとにページ分割し、withQueryString() で検索条件をページ送りに引き継ぎます。
     */
    // 一覧
    public function index(Request $request)
    {
        $products = Product::with('category')
            // 商品名の部分一致
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            })
            // カテゴリの絞り込み
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            // 在庫少数のみ
            ->when($request->boolean('low_stock'), function ($query) {
                $query->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', [
            'products'          => $products,
            'categories'        => $categories,
            'lowStockThreshold' => self::LOW_STOCK_THRESHOLD,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    // 登録フォーム
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // 登録処理
    public function store(ProductRequest $request)
    {
         $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image']); // image は DB のカラムではないので除く

        Product::create($data);

        return redirect()->route('products.index')->with('success', '登録しました'); //with('success', ...) はフラッシュメッセージです。
    }

    /**
     * Display the specified resource.
     * --resource で自動生成されたメソッドは show(string $id) の形です。これを書き換えてください
     * なぜ書き換えが必要か変数名は $product に合わせます。 Route::resource('products', ...) が作るURLは /products/{product} です。
     * メソッドの引数名がこの {product} と同じで、型が Product モデルなら、Laravelが ID から商品を自動で取得して渡してくれます(ルートモデルバインディング)。
     * string $id のままだと、渡されるのはただのID文字列です。 その場合は Product::findOrFail($id) で自分で取得する必要があります。
     */
    // 詳細
    public function show(Product $product)
    {
        $movements = $product->stockMovements()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('products.show', compact('product', 'movements'));
    }

    /**
     * Show the form for editing the specified resource.
     * --resource で自動生成されたメソッドは show(string $id) の形です。これを書き換えてください
     * 自動生成では、show 以外にも $id が付いているものがあります。すべて書き換えます。
     */
    // 編集フォーム
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * --resource で自動生成されたメソッドは show(string $id) の形です。これを書き換えてください
     * 自動生成では、show 以外にも $id が付いているものがあります。すべて書き換えます。
     */
    // 更新処理
     public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path); // 古い画像を削除
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image']);

        $product->update($data);

        return redirect()->route('products.show', $product)->with('success', '更新しました'); //with('success', ...) はフラッシュメッセージです。
    }

    /**
     * Remove the specified resource from storage.
     * --resource で自動生成されたメソッドは show(string $id) の形です。これを書き換えてください
     * 自動生成では、show 以外にも $id が付いているものがあります。すべて書き換えます。
     */
    // 削除
    public function destroy(Product $product)
    {
    $product->delete(); // 論理削除。画像は残す

    return redirect()->route('products.index')->with('success', '削除しました'); //with('success', ...) はフラッシュメッセージです。
    }
}    
