<h1>商品一覧</h1>
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>ID</th><th>商品名</th><th>カテゴリ</th><th>価格</th><th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ number_format($product->price) }}円</td>
                <td><a href="{{ route('products.show', $product) }}">詳細</a></td>
            </tr>
        @empty
            <tr><td colspan="5">商品がありません</td></tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}