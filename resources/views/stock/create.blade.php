@extends('layouts.app')

@section('title', '在庫調整')

@section('content')
<h1>在庫調整</h1>

<p>商品: {{ $product->name }}（{{ $product->sku }}）</p>
<p>現在の在庫数: {{ $product->stock_quantity }}</p>

<form action="{{ route('products.stock.store', $product) }}" method="POST">
    @csrf

    <div>
        <label>種別</label>
        <label>
            <input type="radio" name="type" value="in" @checked(old('type', 'in') === 'in')> 入庫
        </label>
        <label>
            <input type="radio" name="type" value="out" @checked(old('type') === 'out')> 出庫
        </label>
        @error('type') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="quantity">数量</label>
        <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}">
        @error('quantity') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="memo">備考（任意）</label>
        <textarea name="memo" id="memo" rows="3">{{ old('memo') }}</textarea>
        @error('memo') <p style="color: red;">{{ $message }}</p> @enderror
    </div>

    <button type="submit">記録する</button>
    <a href="{{ route('products.show', $product) }}">戻る</a>
</form>
@endsection