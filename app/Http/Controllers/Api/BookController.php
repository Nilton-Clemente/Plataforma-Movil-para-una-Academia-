<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $books = Book::latest()
            ->paginate((int) $request->integer('per_page', 20))
            ->through(fn (Book $book) => [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'price' => (float) $book->price,
            ]);

        return $this->success($books);
    }
}
