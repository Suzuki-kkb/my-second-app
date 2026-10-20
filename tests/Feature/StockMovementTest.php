<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    private function product(int $stock = 10): Product
    {
        $category = Category::create(['name' => 'テスト']);

        return Product::create([
            'category_id'    => $category->id,
            'name'           => 'テスト商品',
            'sku'            => 'TEST-001',
            'price'          => 100,
            'stock_quantity' => $stock,
        ]);
    }

    private function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    public function test_入庫すると在庫が増える(): void
    {
        $product = $this->product(10);

        $this->actingAs($this->staff())
            ->post(route('products.stock.store', $product), [
                'type'     => 'in',
                'quantity' => 5,
            ])
            ->assertRedirect(route('products.show', $product));

        $this->assertSame(15, $product->fresh()->stock_quantity);
    }

    public function test_出庫すると在庫が減る(): void
    {
        $product = $this->product(10);

        $this->actingAs($this->staff())
            ->post(route('products.stock.store', $product), [
                'type'     => 'out',
                'quantity' => 3,
            ]);

        $this->assertSame(7, $product->fresh()->stock_quantity);
    }

    public function test_在庫を超える出庫はエラーになり記録されない(): void
    {
        $product = $this->product(2);

        $this->actingAs($this->staff())
            ->post(route('products.stock.store', $product), [
                'type'     => 'out',
                'quantity' => 5,
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertSame(2, $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_数量が0以下だとエラーになる(): void
    {
        $product = $this->product(10);

        $this->actingAs($this->staff())
            ->post(route('products.stock.store', $product), [
                'type'     => 'in',
                'quantity' => 0,
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertSame(10, $product->fresh()->stock_quantity);
    }

    public function test_入出庫の履歴が記録される(): void
    {
        $product = $this->product(10);
        $staff = $this->staff();

        $this->actingAs($staff)
            ->post(route('products.stock.store', $product), [
                'type'     => 'in',
                'quantity' => 4,
                'memo'     => '仕入れ',
            ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id'    => $staff->id,
            'type'       => 'in',
            'quantity'   => 4,
            'memo'       => '仕入れ',
        ]);
    }
}