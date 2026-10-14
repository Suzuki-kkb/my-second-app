<h1>商品詳細</h1>
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
<dl>
    <dt>ID</dt>
    <dd>{{ $product->id }}</dd>

    <dt>商品名</dt>
    <dd>{{ $product->name }}</dd>

    <dt>商品コード</dt>
    <dd>{{ $product->sku }}</dd>

    <dt>カテゴリ</dt>
    <dd>{{ $product->category->name }}</dd>

    <dt>単価</dt>
    <dd>{{ number_format($product->price) }}円</dd>

    <dt>在庫数</dt>
    <dd>{{ $product->stock_quantity }}</dd>

    <dt>商品画像</dt>
    <dd>
        @if ($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="200">
        @else
            なし
        @endif
    </dd>
</dl>
<a href="{{ route('products.edit', $product) }}">編集</a>
<a href="{{ route('products.index') }}">一覧へ戻る</a>