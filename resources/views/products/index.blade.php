@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<h1>商品一覧</h1>

<a href="{{ route('products.create') }}">新規登録</a>
<form action="{{ route('products.index') }}" method="GET">
    <label for="keyword">商品名</label>
    <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}">

    <label for="category_id">カテゴリ</label>
    <select name="category_id" id="category_id">
        <option value="">すべて</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label>
        <input type="checkbox" name="low_stock" value="1" @checked(request()->boolean('low_stock'))>
        在庫少数のみ（{{ $lowStockThreshold }}以下）
    </label>

    <button type="submit">検索</button>
    <a href="{{ route('products.index') }}">リセット</a>
    <input type="hidden" name="sort" value="{{ $sort }}">
<input type="hidden" name="direction" value="{{ $direction }}">
</form>
@php
    // 列見出しのリンクを作る。同じ列をもう一度押すと昇順・降順が入れ替わる
    $sortUrl = function (string $column) use ($sort, $direction) {
        $next = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';

        return route('products.index', array_merge(request()->query(), [
            'sort'      => $column,
            'direction' => $next,
            'page'      => null, // 並び替えたら1ページ目に戻す
        ]));
    };
    $arrow = fn (string $column) => $sort === $column ? ($direction === 'asc' ? ' ▲' : ' ▼') : '';
@endphp
<table border="1">
    <thead>
        <tr>
            <th><a href="{{ $sortUrl('id') }}">ID{{ $arrow('id') }}</a></th>
            <th><a href="{{ $sortUrl('name') }}">商品名{{ $arrow('name') }}</a></th>
            <th>カテゴリ</th>
            <th><a href="{{ $sortUrl('price') }}">価格{{ $arrow('price') }}</a></th>
            <th><a href="{{ $sortUrl('stock_quantity') }}">在庫数{{ $arrow('stock_quantity') }}</a></th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
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
                @empty
            <tr>
                <td colspan="6">該当する商品がありません</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}
@endsection