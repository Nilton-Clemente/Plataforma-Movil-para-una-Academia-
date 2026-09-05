<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $books = Book::latest()->paginate(9);

        return view('store.index', compact('books'));
    }
}
