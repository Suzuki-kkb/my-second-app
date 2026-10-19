<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">カテゴリ管理</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <h1>カテゴリ登録</h1>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <label for="name">カテゴリ名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            <button type="submit">登録する</button>
            @error('name') <p style="color: red;">{{ $message }}</p> @enderror
        </form>

        <h1>カテゴリ一覧</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>カテゴリ名</th>
                    <th>商品数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}">編集</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                  style="display: inline;"
                                  onsubmit="return confirm('「{{ $category->name }}」を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">カテゴリがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
</x-app-layout>