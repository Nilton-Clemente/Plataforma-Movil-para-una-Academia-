@extends('layouts.app')

@section('title', 'Registro | Academia Update')

@section('content')
    <main class="auth-page">
        <div class="auth-card">
            <h1>Crear cuenta</h1>
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <label class="form-label">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>

                <label class="form-label mt-3">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>

                <label class="form-label mt-3">Telefono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">

                <label class="form-label mt-3">Direccion</label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-control">

                <label class="form-label mt-3">Contrasena</label>
                <input type="password" name="password" class="form-control" required>

                <label class="form-label mt-3">Confirmar contrasena</label>
                <input type="password" name="password_confirmation" class="form-control" required>

                <button class="btn btn-primary w-100 mt-4">Registrarme</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesion</a>
            </p>
        </div>
    </main>
@endsection
