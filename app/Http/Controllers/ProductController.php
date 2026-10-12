<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; //今回のコントローラは ProductRequest を使うので、Request は使いません。使わない use が残っていても、エラーにはなりません。
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 一覧
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
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
    public function store(Request $request)
    {
         $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image']); // image は DB のカラムではないので除く

        Product::create($data);

        return redirect()->route('products.index')->with('success', '登録しました');
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
        return view('products.show', compact('product'));
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

        return redirect()->route('products.show', $product)->with('success', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     * --resource で自動生成されたメソッドは show(string $id) の形です。これを書き換えてください
     * 自動生成では、show 以外にも $id が付いているものがあります。すべて書き換えます。
     */
    // 削除
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', '削除しました');
    }
}
