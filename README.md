# 商品・在庫管理システム

Laravel で実装した、商品マスタと在庫数を一元管理し、入出庫履歴を記録するCRUDシステムです。

## 機能

- 商品のCRUD(登録・一覧・詳細・編集・削除)
- カテゴリのCRUD(管理者のみ)
- 在庫調整(入庫・出庫)と入出庫履歴の閲覧
- 商品名・カテゴリでの検索、在庫少数の絞り込み、並び替え、ページネーション
- 商品画像のアップロード(任意)
- ログイン認証と権限(管理者 / 一般スタッフ)

## 権限

| 操作 | 管理者 | 一般スタッフ |
|---|---|---|
| 商品の閲覧・登録・編集 | ○ | ○ |
| 在庫調整 | ○ | ○ |
| 商品の削除 | ○ | × |
| カテゴリ管理 | ○ | × |

## 動作環境

- PHP 8.2 以上
- Composer
- Node.js(LTS)
- SQLite(開発環境)

## セットアップ

```bash
git clone <リポジトリURL>
cd my-second-app
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
```

## 起動

```bash
npm run dev          # ターミナル1
php artisan serve    # ターミナル2
```

http://127.0.0.1:8000 を開きます。

## テストアカウント(開発用)

シーダーで作成されます。アカウント情報は `database/seeders/UserSeeder.php` を参照してください。
**本番環境では必ずパスワードを変更してください。**

## テスト

```bash
php artisan test
```

## 設計

`設計書.pdf` を参照してください。
