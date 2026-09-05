@extends('layouts.app')

@section('title', 'Editar usuario | Academia Update')

@section('content')
    <main class="panel-page">
        <div class="container">
            <div class="panel-heading">
                <div>
                    <p class="text-muted mb-1">Administrador</p>
                    <h1>Editar usuario</h1>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefono</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Direccion</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Biografia</label>
                            <textarea name="bio" rows="4" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva contrasena</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contrasena</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-check">
                                <input type="checkbox" name="is_admin" value="1" class="form-check-input" @checked(old('is_admin', $user->is_admin))>
                                <span class="form-check-label">Administrador</span>
                            </label>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary">Guardar usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
