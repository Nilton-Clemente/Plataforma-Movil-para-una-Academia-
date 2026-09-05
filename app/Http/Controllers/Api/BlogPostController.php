<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $posts = BlogPost::with('user')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate((int) $request->integer('per_page', 20))
            ->through(fn (BlogPost $post) => $this->payload($post));

        return $this->success($posts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $post = $request->user()->blogPosts()->create([
            ...$data,
            'published_at' => now(),
        ]);

        return $this->success($this->payload($post->load('user')), 'Publicacion creada correctamente.', 201);
    }

    private function payload(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'image_url' => $post->image_url,
            'published_at' => $post->published_at?->toISOString(),
            'author' => [
                'id' => $post->user?->id,
                'name' => $post->user?->name,
            ],
        ];
    }
}
