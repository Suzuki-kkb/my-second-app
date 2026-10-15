<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '商品・在庫管理')</title>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('products.index') }}">商品一覧</a>
        </nav>
    </header>

    <main>
        {{-- 成功メッセージ（登録・更新・削除後に表示） --}}
        @if (session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

        {{-- 各ページの中身がここに入る --}}
        @yield('content')
    </main>
</body>
</html>