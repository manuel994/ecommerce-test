<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\Product;

class ProductTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    /** @test */
    public function unauthenticated_user_try_create_new_product()
    {
        $product = factory(Product::class)->make();
        $response = $this->json('POST', '/api/products', [
               'description' => $product->description,
               'price' => $product->price,
               'name' => $product->name]);
        $response->assertStatus(200)
               ->assertExactJson([
                'error' => "Unauthorized",
            ]);
    }
    /** @test */
    public function authenticated_user_try_create_new_product()
    {
        $product = factory(Product::class)->make();

    $body = [
        'username' => 'admin',
        'password' => 'secret'
    ];

    $response = $this->json('POST','api/users/login',$body,['Accept' => 'application/json']);
    $response->assertStatus(200);
    $this->assertAuthenticatedAs(User::find(1));
    $content = $response->decodeResponseJson();
    $token = $content["success"]["token"];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('POST', '/api/products', [
               'description' => $product->description,
               'price' => $product->price,
               'name' => $product->name]);
        $response->assertStatus(200)
          ->assertSeeText("Product saved successfully");
    }
    /** @test */
    public function authenticated_user_try_create_new_product_string_price()
    {
        $product = factory(Product::class)->make();

    $body = [
        'username' => 'admin',
        'password' => 'secret'
    ];
    
    $response = $this->json('POST','api/users/login',$body,['Accept' => 'application/json']);
    $response->assertStatus(200);
    $this->assertAuthenticatedAs(User::find(1));
    $content = $response->decodeResponseJson();
    $token = $content["success"]["token"];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json('POST', '/api/products', [
               'description' => $product->description,
               'price' => "string",
               'name' => $product->name]);
        $response->assertStatus(422)
          ->assertSeeText("The price format is invalid.")
          ->assertSeeText("The price must be between 1 and 5 digits.");
    }

}
