<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cartItems = $this->cartItems($request);
        $total = $this->cartTotal($cartItems);

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cart($request);
        $quantity = $data['quantity'] ?? 1;
        $cartItem = $cart->items()->firstOrNew([
            'book_id' => $book->id,
        ]);

        $cartItem->quantity = min(99, ($cartItem->quantity ?? 0) + $quantity);
        $cartItem->save();

        return back()->with('status', 'Libro agregado al carrito.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cart($request);

        foreach ($data['quantities'] as $bookId => $quantity) {
            $cart->items()
                ->where('book_id', $bookId)
                ->update(['quantity' => $quantity]);
        }

        return back()->with('status', 'Carrito actualizado correctamente.');
    }

    public function destroy(Request $request, string $bookId): RedirectResponse
    {
        $this->cart($request)
            ->items()
            ->where('book_id', $bookId)
            ->delete();

        return back()->with('status', 'Libro retirado del carrito.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $this->cart($request)->items()->delete();

        return back()->with('status', 'Carrito vaciado correctamente.');
    }

    private function cartItems(Request $request): array
    {
        return $this->cart($request)
            ->items()
            ->with('book')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    (string) $item->book_id => [
                        'book_id' => $item->book_id,
                        'title' => $item->book->title,
                        'price' => (float) $item->book->price,
                        'quantity' => $item->quantity,
                    ],
                ];
            })
            ->all();
    }

    private function cart(Request $request): Cart
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $this->moveSessionCartToDatabase($request, $cart);

        return $cart;
    }

    private function moveSessionCartToDatabase(Request $request, Cart $cart): void
    {
        $sessionItems = $request->session()->pull('cart.items', []);

        foreach ($sessionItems as $item) {
            if (! isset($item['book_id'], $item['quantity'])) {
                continue;
            }

            $cartItem = $cart->items()->firstOrNew([
                'book_id' => $item['book_id'],
            ]);

            $cartItem->quantity = min(99, ($cartItem->quantity ?? 0) + (int) $item['quantity']);
            $cartItem->save();
        }
    }

    private function cartTotal(array $cartItems): float
    {
        return array_reduce($cartItems, function (float $total, array $item): float {
            return $total + ($item['price'] * $item['quantity']);
        }, 0.0);
    }
}
