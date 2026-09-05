@extends('layouts.app')

@section('title', 'Contactos | Academia Update')

@section('content')
    <div class="mapa pt-5 mt-3">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.9817127241167!2d-77.03283105562211!3d-12.046037501839221!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8b5d5aa7eb1%3A0x16061e0b481e22aa!2sPlaza%20Mayor%20de%20Lima!5e0!3m2!1ses-419!2spe!4v1776706662025!5m2!1ses-419!2spe" width="100%" height="500" style="border:0;" allowfullscreen loading="lazy"></iframe>
    </div>

    <div class="contactos">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('contact') }}">
                                @csrf
                                <p>Apellidos y nombres</p>
                                <input type="text" placeholder="Ingresa tus nombres" class="form-control" name="nombres">
                                <p>Direccion</p>
                                <input type="text" placeholder="Ingresa tu direccion" class="form-control" name="direccion">
                                <p>Correo</p>
                                <input type="email" placeholder="Ingresa tu correo" class="form-control" name="correo">
                                <p>Comentarios</p>
                                <textarea cols="30" class="form-control" name="comentarios"></textarea>
                                <button type="button" class="form-control bg-dark text-white mt-3">Enviar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
