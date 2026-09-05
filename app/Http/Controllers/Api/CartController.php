<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        return $this->success($this->cartPayload($request));
    }

    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cart($request);
        $cartItem = $cart->items()->firstOrNew(['book_id' => $book->id]);
        $cartItem->quantity = min(99, ($cartItem->quantity ?? 0) + ($data['quantity'] ?? 1));
        $cartItem->save();

        return $this->success($this->cartPayload($request), 'Libro agregado al carrito.');
    }

    public function update(Request $request)
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

        return $this->success($this->cartPayload($request), 'Carrito actualizado correctamente.');
    }

    public function destroy(Request $request, Book $book)
    {
        $this->cart($request)
            ->items()
            ->where('book_id', $book->id)
            ->delete();

        return $this->success($this->cartPayload($request), 'Libro retirado del carrito.');
    }

    public function clear(Request $request)
    {
        $this->cart($request)->items()->delete();

        return $this->success($this->cartPayload($request), 'Carrito vaciado correctamente.');
    }

    public function checkoutLink(Request $request)
    {
        $url = URL::temporarySignedRoute(
            'mobile.checkout',
            now()->addMinutes(10),
            ['user' => $request->user()->id]
        );

        return $this->success(['url' => $url], 'Enlace de pago generado correctamente.');
    }

    private function cartPayload(Request $request): array
    {
        $items = $this->cart($request)
            ->items()
            ->with('book')
            ->get()
            ->map(fn ($item) => [
                'book_id' => $item->book_id,
                'title' => $item->book->title,
                'price' => (float) $item->book->price,
                'quantity' => $item->quantity,
                'subtotal' => (float) $item->book->price * $item->quantity,
            ])
            ->values();

        return [
            'items' => $items,
            'count' => $items->sum('quantity'),
            'total' => $items->sum('subtotal'),
        ];
    }

    private function cart(Request $request): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);
    }
}
