@extends('layouts.app')

@section('title', '商品登録')

@section('content')
<h1>商品登録</h1>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="category_id">カテゴリ</label>
        <select name="category_id" id="category_id">
            <option value="">選択してください</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="name">商品名</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        @error('name') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sku">商品コード</label>
        <input type="text" name="sku" id="sku" value="{{ old('sku') }}">
        @error('sku') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="price">単価</label>
        <input type="number" name="price" id="price" min="0" value="{{ old('price') }}">
        @error('price') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="stock_quantity">在庫数</label>
        <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}">
        @error('stock_quantity') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="image">商品画像（任意）</label>
        <input type="file" name="image" id="image" accept="image/*">
        @error('image') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <button type="submit">登録する</button>
    <a href="{{ route('products.index') }}">戻る</a>
</form>
@endsection