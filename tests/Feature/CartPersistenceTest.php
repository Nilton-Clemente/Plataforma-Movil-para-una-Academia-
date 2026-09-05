<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_items_persist_after_logout_and_login(): void
    {
        $user = User::create([
            'name' => 'Cliente',
            'email' => 'cliente@example.com',
            'password' => 'password',
        ]);

        $book = Book::create([
            'title' => 'Libro de prueba',
            'description' => 'Contenido para probar el carrito.',
            'price' => 25.50,
        ]);

        $this->actingAs($user)
            ->post(route('cart.store', $book), ['quantity' => 2])
            ->assertRedirect();

        $this->post(route('logout'))->assertRedirect(route('home'));

        $this->post(route('login.store'), [
            'email' => 'cliente@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Libro de prueba')
            ->assertSee('2');
    }
}
