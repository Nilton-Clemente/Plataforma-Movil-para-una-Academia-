@extends('layouts.app')

@section('title', 'Login | Academia Update')

@section('content')
    <main class="auth-page">
        <div class="auth-card">
            <h1>Iniciar sesion</h1>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="form-label">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>

                <label class="form-label mt-3">Contrasena</label>
                <input type="password" name="password" class="form-control" required>

                <label class="form-check mt-3">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <span class="form-check-label">Recordarme</span>
                </label>

                <button class="btn btn-primary w-100 mt-4">Entrar</button>
            </form>

            <p class="text-center mt-3 mb-0">
                No tienes cuenta?
                <a href="{{ route('register') }}">Registrate aqui</a>
            </p>
        </div>
    </main>
@endsection
