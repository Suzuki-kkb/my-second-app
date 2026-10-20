<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $items = [
            ['category' => '文房具', 'name' => 'ボールペン(黒)', 'sku' => 'PEN-001', 'price' => 120, 'stock_quantity' => 50],
            ['category' => '文房具', 'name' => 'ノートA5', 'sku' => 'NOTE-001', 'price' => 250, 'stock_quantity' => 30],
            ['category' => '文房具', 'name' => 'ホチキス', 'sku' => 'STAP-001', 'price' => 680, 'stock_quantity' => 8],
            ['category' => '文房具', 'name' => 'クリアファイル', 'sku' => 'FILE-001', 'price' => 80, 'stock_quantity' => 5],
            ['category' => '文房具', 'name' => '付箋セット', 'sku' => 'MEMO-001', 'price' => 300, 'stock_quantity' => 20],
        ];

        foreach ($items as $item) {
            $category = Category::where('name', $item['category'])->firstOrFail();

            Product::create([
                'category_id'    => $category->id,
                'name'           => $item['name'],
                'sku'            => $item['sku'],
                'price'          => $item['price'],
                'stock_quantity' => $item['stock_quantity'],
            ]);
        }
    }
}
