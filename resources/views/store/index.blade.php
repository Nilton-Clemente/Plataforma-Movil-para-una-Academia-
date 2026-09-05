@extends('layouts.app')

@section('title', 'Tienda | Academia Update')

@section('content')
    <main class="panel-page store-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Tienda</p>
                    <h1>Libros disponibles</h1>
                </div>
                @if(auth()->user()?->is_admin)
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">Administrar libros</a>
                @endif
            </div>

            @if($books->isEmpty())
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h2 class="h4">Aun no hay libros publicados</h2>
                        <p class="text-muted mb-0">Cuando el administrador registre libros, apareceran aqui.</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($books as $book)
                        <div class="col-md-6 col-lg-4">
                            <article class="card book-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="book-icon mb-3">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <h2 class="h5">{{ $book->title }}</h2>
                                    <p class="text-muted flex-grow-1">{{ $book->description }}</p>
                                    <div class="book-purchase">
                                        <p class="book-price mb-0">S/ {{ number_format($book->price, 2) }}</p>
                                        <form method="POST" action="{{ route('cart.store', $book) }}">
                                            @csrf
                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-cart-plus"></i>
                                                Agregar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </main>
@endsection
