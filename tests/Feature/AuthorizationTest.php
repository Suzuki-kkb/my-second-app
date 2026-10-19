<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    private function product(): Product
    {
        $category = Category::create(['name' => 'テスト']);

        return Product::create([
            'category_id'    => $category->id,
            'name'           => 'テスト商品',
            'sku'            => 'TEST-001',
            'price'          => 100,
            'stock_quantity' => 10,
        ]);
    }

    public function test_未ログインはログイン画面に飛ばされる(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_一般ユーザーは商品一覧を見られる(): void
    {
        $this->actingAs($this->staff())
            ->get('/products')
            ->assertOk();
    }

    public function test_一般ユーザーは商品を削除できない(): void
    {
        $product = $this->product();

        $this->actingAs($this->staff())
            ->delete(route('products.destroy', $product))
            ->assertForbidden();
    }

    public function test_管理者は商品を削除できる(): void
    {
        $product = $this->product();

        $this->actingAs($this->admin())
            ->delete(route('products.destroy', $product))
            ->assertRedirect();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_一般ユーザーはカテゴリ管理に入れない(): void
    {
        $this->actingAs($this->staff())
            ->get('/categories')
            ->assertForbidden();
    }

    public function test_入出庫の記録者はログインユーザーになる(): void
    {
        $product = $this->product();
        $staff = $this->staff();

        $this->actingAs($staff)
            ->post(route('products.stock.store', $product), [
                'type'     => 'in',
                'quantity' => 5,
            ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id'    => $staff->id,
            'type'       => 'in',
            'quantity'   => 5,
        ]);
    }
}