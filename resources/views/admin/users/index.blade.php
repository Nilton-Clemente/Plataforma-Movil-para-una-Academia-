@extends('layouts.app')

@section('title', 'Usuarios | Academia Update')

@section('content')
    <main class="panel-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Administrador</p>
                    <h1>Usuarios creados</h1>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Mi perfil</a>
            </div>

            <div class="table-responsive card">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th>Rol</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?: '-' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $user->is_admin ? 'dark' : 'secondary' }}">
                                        {{ $user->is_admin ? 'Administrador' : 'Usuario' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar este usuario?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </main>
@endsection
