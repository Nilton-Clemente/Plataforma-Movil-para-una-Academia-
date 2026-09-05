<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBlogPostController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $posts = BlogPost::with('user')->latest('published_at')->latest()->paginate(10);

        return view('admin.blog.index', compact('posts'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $request->user()->blogPosts()->create([
            ...$this->validatedData($request),
            'published_at' => now(),
        ]);

        return redirect()->route('admin.blog.index')->with('status', 'Publicacion creada correctamente.');
    }

    public function edit(Request $request, BlogPost $blogPost): View
    {
        $this->authorizeAdmin($request);

        return view('admin.blog.edit', compact('blogPost'));
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $blogPost->update($this->validatedData($request));

        return redirect()->route('admin.blog.index')->with('status', 'Publicacion actualizada correctamente.');
    }

    public function destroy(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $blogPost->delete();

        return back()->with('status', 'Publicacion eliminada correctamente.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);
    }
}
