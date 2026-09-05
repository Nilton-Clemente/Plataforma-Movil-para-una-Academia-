@extends('layouts.app')

@section('title', 'Blog | Academia Update')

@section('content')
    <main class="panel-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Administrador</p>
                    <h1>Publicaciones del blog</h1>
                </div>
                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Nueva publicacion</a>
            </div>

            <div class="table-responsive card">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>Contenido</th>
                            <th>Fecha</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->user?->name ?? 'Usuario eliminado' }}</td>
                                <td>{{ Str::limit($post->content, 90) }}</td>
                                <td>{{ $post->published_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar esta publicacion?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay publicaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        </div>
    </main>
@endsection
