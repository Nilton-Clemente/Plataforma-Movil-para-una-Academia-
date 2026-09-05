<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBlogPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_blog_admin(): void
    {
        $user = User::create([
            'name' => 'Usuario',
            'email' => 'usuario@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get(route('admin.blog.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_blog_posts(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.blog.store'), [
                'title' => 'Publicacion admin',
                'content' => 'Contenido creado desde el panel.',
                'image_url' => 'https://example.com/admin.jpg',
            ])
            ->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::firstOrFail();

        $this->assertSame($admin->id, $post->user_id);

        $this->actingAs($admin)
            ->put(route('admin.blog.update', $post), [
                'title' => 'Publicacion editada',
                'content' => 'Contenido actualizado por el administrador.',
                'image_url' => 'https://example.com/editada.jpg',
            ])
            ->assertRedirect(route('admin.blog.index'));

        $this->assertDatabaseHas('blog_posts', [
            'id' => $post->id,
            'title' => 'Publicacion editada',
            'image_url' => 'https://example.com/editada.jpg',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.blog.destroy', $post))
            ->assertRedirect();

        $this->assertDatabaseMissing('blog_posts', [
            'id' => $post->id,
        ]);
    }
}
