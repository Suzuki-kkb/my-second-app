<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品編集</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1>商品編集</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="category_id">カテゴリ</label>
                <select name="category_id" id="category_id">
                    <option value="">選択してください</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id', $product->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name">商品名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}">
                @error('name') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sku">商品コード</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}">
                @error('sku') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price">単価</label>
                <input type="number" name="price" id="price" min="0" value="{{ old('price', $product->price) }}">
                @error('price') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>在庫数</label>
                {{ $product->stock_quantity }}
            </div>

            <div>
                <label for="image">商品画像（任意）</label>
                @if ($product->image_path)
                    <div>
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" width="150">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*">
                @error('image') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <button type="submit">更新する</button>
            <a href="{{ route('products.show', $product) }}">戻る</a>
        </form>
    </div>
</x-app-layout>