@extends('layouts.app')

@section('title', 'Carrito | Academia Update')

@section('content')
    <main class="panel-page cart-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Tienda</p>
                    <h1>Carrito de compras</h1>
                </div>
                <a href="{{ route('store.index') }}" class="btn btn-outline-secondary">Seguir comprando</a>
            </div>

            @if(empty($cartItems))
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h2 class="h4">Tu carrito esta vacio</h2>
                        <p class="text-muted mb-4">Agrega libros desde la tienda para verlos aqui.</p>
                        <a href="{{ route('store.index') }}" class="btn btn-primary">Ver libros</a>
                    </div>
                </div>
            @else
                <div class="row g-4 align-items-start">
                    <div class="col-lg-8">
                        <form method="POST" action="{{ route('cart.update') }}" class="card cart-table-card">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Libro</th>
                                            <th>Precio</th>
                                            <th class="cart-quantity-col">Cantidad</th>
                                            <th>Subtotal</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $item['title'] }}</div>
                                                </td>
                                                <td>S/ {{ number_format($item['price'], 2) }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="quantities[{{ $item['book_id'] }}]"
                                                        value="{{ $item['quantity'] }}"
                                                        min="1"
                                                        max="99"
                                                        class="form-control cart-quantity-input"
                                                        aria-label="Cantidad de {{ $item['title'] }}"
                                                    >
                                                </td>
                                                <td class="fw-semibold">S/ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                                <td class="text-end">
                                                    <button
                                                        type="submit"
                                                        form="remove-book-{{ $item['book_id'] }}"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Quitar
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="cart-actions">
                                <button class="btn btn-primary">Actualizar carrito</button>
                            </div>
                        </form>

                        @foreach($cartItems as $item)
                            <form id="remove-book-{{ $item['book_id'] }}" method="POST" action="{{ route('cart.destroy', $item['book_id']) }}" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    </div>

                    <div class="col-lg-4">
                        <aside class="card cart-summary">
                            <div class="card-body">
                                <h2 class="h5">Resumen</h2>
                                <div class="cart-summary-row">
                                    <span>Productos</span>
                                    <strong>{{ collect($cartItems)->sum('quantity') }}</strong>
                                </div>
                                <div class="cart-summary-row">
                                    <span>Total</span>
                                    <strong>S/ {{ number_format($total, 2) }}</strong>
                                </div>

                                <div
                                    id="paypal-button-container"
                                    class="mt-3"
                                    data-total="{{ number_format($total, 2, '.', '') }}"
                                ></div>
                                <p id="paypal-error" class="text-danger small mt-2 mb-0 d-none">
                                    No se pudo cargar PayPal. Intenta nuevamente en unos minutos.
                                </p>

                                <form method="POST" action="{{ route('cart.clear') }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger w-100">Vaciar carrito</button>
                                </form>
                            </div>
                        </aside>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id=BAAbqiRgauMexQkFRL7HBhf2WR1dnrHDfkJ0JRX0r_ZynPc5CBHcEAhKFZioI7NnDSPDVtPvG52tVt-81g"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paypalContainer = document.getElementById('paypal-button-container');
            const paypalError = document.getElementById('paypal-error');

            if (!paypalContainer || typeof paypal === 'undefined') {
                paypalError?.classList.remove('d-none');
                return;
            }

            const total = Number(paypalContainer.dataset.total || 0).toFixed(2);

            paypal.Buttons({
                style: {
                    color: 'gold',
                    shape: 'rect',
                    label: 'pay',
                    height: 45,
                },
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total,
                            },
                        }],
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function () {
                        window.location.href = "{{ route('cart.index') }}";
                    });
                },
                onError: function () {
                    paypalError?.classList.remove('d-none');
                },
            }).render('#paypal-button-container');
        });
    </script>
@endpush
