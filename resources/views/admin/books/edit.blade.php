@extends('layouts.app')

@section('title', 'Editar libro | Academia Update')

@section('content')
    <main class="panel-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Administrador</p>
                    <h1>Editar libro</h1>
                </div>
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.books.update', $book) }}" class="row g-3">
                        @csrf
                        @method('PUT')

                        @include('admin.books.form', ['book' => $book])

                        <div class="col-12">
                            <button class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
