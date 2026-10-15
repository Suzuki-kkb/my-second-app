@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<h1>商品一覧</h1>

<a href="{{ route('products.create') }}">新規登録</a>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>カテゴリ</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ number_format($product->price) }}円</td>
                <td>{{ $product->stock_quantity }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}">詳細</a>
                    <a href="{{ route('products.edit', $product) }}">編集</a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                          style="display: inline;"
                          onsubmit="return confirm('「{{ $product->name }}」を削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection