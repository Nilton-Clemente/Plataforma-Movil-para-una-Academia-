@extends('layouts.app')

@section('title', 'Inicio | Academia Update')

@section('content')
    <div id="homeCarousel" class="carousel slide page-hero" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach (['banner1.png', 'banner2.png', 'banner3.png'] as $banner)
                <div class="carousel-item @if($loop->first) active @endif">
                    <img src="{{ asset('img/' . $banner) }}" class="d-block w-100" alt="Banner de Academia Update">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <header class="my-5">
        <div class="container">
            <h2 class="text-center">NUESTRA EMPRESA</h2>
            <p>Somos una academia enfocada en acompanar a cada estudiante con recursos claros, clases dinamicas y seguimiento constante para mejorar sus resultados.</p>
            <img src="{{ asset('img/img00.png') }}" alt="Estudiantes aprendiendo" width="400" class="d-block m-auto img-fluid">
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <h2>Aprende con nosotros</h2>
                    <p>Accede a contenidos, actividades y una experiencia pensada para que puedas avanzar a tu ritmo con apoyo de la academia.</p>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir a mi panel</a>
                    @endguest
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('img/img01.png') }}" alt="Aprendizaje virtual" class="w-100">
                </div>
            </div>
        </div>
    </main>
@endsection
