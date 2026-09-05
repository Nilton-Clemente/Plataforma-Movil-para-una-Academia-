<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_and_logout_with_token(): void
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Cliente Movil',
            'email' => 'movil@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $register->assertCreated()
            ->assertJsonPath('message', 'Cuenta creada correctamente.')
            ->assertJsonStructure(['data' => ['user', 'token'], 'message', 'errors']);

        $login = $this->postJson('/api/login', [
            'email' => 'movil@example.com',
            'password' => 'password',
        ]);

        $token = $login->assertOk()
            ->assertJsonPath('data.user.email', 'movil@example.com')
            ->json('data.token');

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Sesion cerrada correctamente.');
    }

    public function test_protected_routes_require_a_token(): void
    {
        $this->getJson('/api/me')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'No autenticado.');
    }

    public function test_user_can_manage_cart_from_api(): void
    {
        $user = User::create([
            'name' => 'Cliente',
            'email' => 'cliente@example.com',
            'password' => 'password',
        ]);

        $book = Book::create([
            'title' => 'Libro API',
            'description' => 'Contenido para probar API.',
            'price' => 30,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/cart/books/{$book->id}", ['quantity' => 2])
            ->assertOk()
            ->assertJsonPath('data.count', 2)
            ->assertJsonPath('data.total', 60);

        $this->withToken($token)
            ->putJson('/api/cart', ['quantities' => [$book->id => 3]])
            ->assertOk()
            ->assertJsonPath('data.count', 3)
            ->assertJsonPath('data.total', 90);

        $this->withToken($token)
            ->deleteJson("/api/cart/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('data.count', 0);
    }

    public function test_checkout_link_returns_signed_web_url(): void
    {
        $user = User::create([
            'name' => 'Cliente',
            'email' => 'checkout@example.com',
            'password' => 'password',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/cart/checkout-link')
            ->assertOk()
            ->assertJsonPath('message', 'Enlace de pago generado correctamente.')
            ->assertJsonStructure(['data' => ['url'], 'message', 'errors']);
    }

    public function test_public_mobile_content_is_available(): void
    {
        $this->getJson('/api/mobile/home')
            ->assertOk()
            ->assertJsonPath('data.title', 'Academia Update');

        $this->getJson('/api/mobile/gallery')
            ->assertOk()
            ->assertJsonCount(8, 'data.images');

        $this->getJson('/api/mobile/contact')
            ->assertOk()
            ->assertJsonPath('data.title', 'Contactos')
            ->assertJsonPath('data.map_url', 'https://www.google.com/maps?q=Plaza%20Mayor%20de%20Lima&output=embed');
    }

    public function test_authenticated_user_can_create_blog_post_from_api(): void
    {
        $user = User::create([
            'name' => 'Autora Movil',
            'email' => 'autora@example.com',
            'password' => 'password',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/blog-posts', [
                'title' => 'Mi primera publicacion',
                'content' => 'Contenido del blog desde la app.',
                'image_url' => 'https://example.com/imagen.jpg',
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Mi primera publicacion')
            ->assertJsonPath('data.author.name', 'Autora Movil')
            ->assertJsonPath('data.image_url', 'https://example.com/imagen.jpg');

        $this->assertDatabaseHas('blog_posts', [
            'user_id' => $user->id,
            'title' => 'Mi primera publicacion',
        ]);
    }

    public function test_guest_cannot_create_blog_post_from_api(): void
    {
        $this->postJson('/api/blog-posts', [
            'title' => 'Publicacion sin sesion',
            'content' => 'Contenido.',
        ])->assertUnauthorized();
    }

    public function test_blog_post_list_returns_author_and_image(): void
    {
        $user = User::create([
            'name' => 'Autor Blog',
            'email' => 'autor-blog@example.com',
            'password' => 'password',
        ]);

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'Noticia de prueba',
            'content' => 'Contenido visible.',
            'image_url' => 'https://example.com/blog.jpg',
            'published_at' => now(),
        ]);

        $this->getJson('/api/blog-posts')
            ->assertOk()
            ->assertJsonPath('data.data.0.title', 'Noticia de prueba')
            ->assertJsonPath('data.data.0.author.name', 'Autor Blog')
            ->assertJsonPath('data.data.0.image_url', 'https://example.com/blog.jpg');
    }
}
