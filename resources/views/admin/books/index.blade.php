@extends('layouts.app')

@section('title', 'Libros | Academia Update')

@section('content')
    <main class="panel-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Administrador</p>
                    <h1>Libros de la tienda</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('store.index') }}" class="btn btn-outline-secondary">Ver tienda</a>
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Nuevo libro</a>
                </div>
            </div>

            <div class="table-responsive card">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Descripcion</th>
                            <th>Precio</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ Str::limit($book->description, 90) }}</td>
                                <td>S/ {{ number_format($book->price, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar este libro?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay libros registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $books->links() }}
            </div>
        </div>
    </main>
@endsection
