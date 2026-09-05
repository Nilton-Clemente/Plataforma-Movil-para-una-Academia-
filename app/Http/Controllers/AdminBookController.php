<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $books = Book::latest()->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        Book::create($this->validatedData($request));

        return redirect()->route('admin.books.index')->with('status', 'Libro creado correctamente.');
    }

    public function edit(Request $request, Book $book): View
    {
        $this->authorizeAdmin($request);

        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $book->update($this->validatedData($request));

        return redirect()->route('admin.books.index')->with('status', 'Libro actualizado correctamente.');
    }

    public function destroy(Request $request, Book $book): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $book->delete();

        return back()->with('status', 'Libro eliminado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ]);
    }
}
