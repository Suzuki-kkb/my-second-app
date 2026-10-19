<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品詳細</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1>商品詳細</h1>

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

        <p>
            <a href="{{ route('products.stock.create', $product) }}">在庫調整（入庫・出庫）</a>
        </p>

        <h2>入出庫履歴</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>種別</th>
                    <th>数量</th>
                    <th>備考</th>
                    <th>担当者</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $movement->type === 'in' ? '入庫' : '出庫' }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->memo }}</td>
                        <td>{{ $movement->user->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">履歴はまだありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $movements->links() }}
    </div>
</x-app-layout>