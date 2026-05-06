<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;



class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: create authenticated user with Sanctum token
     */
    private function authUser()
    {
        $user = User::factory()->create();

        return $user->createToken('test-token')->plainTextToken;
    }

    /**
     * 1. Test user can get product list
     */
    public function test_user_can_get_products()
    {
        $token = $this->authUser();

        Product::factory()->count(2)->create();

        $response = $this->getJson('/api/products', [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
    }

    /**
     * 2. Test user can create product
     */
    public function test_user_can_create_product()
    {
        $token = $this->authUser();

        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Laptop',
            'price' => 2500,
            'stock' => 10,
            'category_id' => $category->id,
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(201);
    }

    /**
     * 3. Test user can view single product
     */
    public function test_user_can_view_single_product()
    {
        $token = $this->authUser();

        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}", [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
    }

    /**
     * 4. Test user can update product
     */
    public function test_user_can_update_product()
    {
        $token = $this->authUser();

        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'price' => 999,
        ], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
    }

    /**
     * 5. Test user can delete product
     */
    public function test_user_can_delete_product()
    {
        $token = $this->authUser();

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}", [], [
            'Authorization' => "Bearer $token"
        ]);

        $response->assertStatus(200);
    }
}
