<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_管理者はカテゴリを登録できる(): void
    {
        $this->actingAs($this->admin())
            ->post(route('categories.store'), ['name' => '文房具'])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => '文房具']);
    }

    public function test_同じ名前のカテゴリは登録できない(): void
    {
        Category::create(['name' => '文房具']);

        $this->actingAs($this->admin())
            ->post(route('categories.store'), ['name' => '文房具'])
            ->assertSessionHasErrors('name');
    }

    public function test_管理者はカテゴリ名を更新できる(): void
    {
        $category = Category::create(['name' => '旧名']);

        $this->actingAs($this->admin())
            ->put(route('categories.update', $category), ['name' => '新名'])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => '新名']);
    }

    public function test_商品があるカテゴリは削除できない(): void
    {
        $category = Category::create(['name' => '文房具']);
        Product::create([
            'category_id'    => $category->id,
            'name'           => 'ペン',
            'sku'            => 'PEN-001',
            'price'          => 100,
            'stock_quantity' => 1,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_商品がないカテゴリは削除できる(): void
    {
        $category = Category::create(['name' => '空カテゴリ']);

        $this->actingAs($this->admin())
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}