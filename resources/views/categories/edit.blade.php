<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">カテゴリ編集</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="name">カテゴリ名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}">
                @error('name') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <button type="submit">更新する</button>
            <a href="{{ route('categories.index') }}">戻る</a>
        </form>
    </div>
</x-app-layout>