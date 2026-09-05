@extends('layouts.app')

@section('title', 'Nosotros | Academia Update')

@section('content')
    <div id="aboutCarousel" class="carousel slide page-hero" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach (['banner4.png', 'banner3.png', 'banner2.png'] as $banner)
                <div class="carousel-item @if($loop->first) active @endif">
                    <img src="{{ asset('img/' . $banner) }}" class="d-block w-100" alt="Academia Update">
                </div>
            @endforeach
        </div>
    </div>

    <header class="my-5">
        <div class="container">
            <h2 class="text-center">NOSOTROS</h2>
            <p>Academia Update nace para acercar formacion accesible, practica y ordenada. Nuestro equipo combina orientacion personalizada con materiales actualizados para cada clase.</p>
            <img src="{{ asset('img/img01.png') }}" alt="Equipo de la academia" width="400" class="d-block m-auto img-fluid">
        </div>
    </header>

    <main class="my-5">
        <div class="container">
            <div class="accordion accordion-flush" id="faq">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-danger text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">De donde obtienen los materiales para la clase</button>
                    </h2>
                    <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#faq">
                        <div class="accordion-body">Los materiales se preparan desde el temario de cada curso y se actualizan segun las necesidades de los estudiantes.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">Cual es el curso de tu preferencia</button>
                    </h2>
                    <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faq">
                        <div class="accordion-body">Puedes elegir cursos introductorios o avanzados segun tu nivel y tus objetivos.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-warning text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">Como se realiza el seguimiento</button>
                    </h2>
                    <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faq">
                        <div class="accordion-body">Cada usuario puede mantener sus datos actualizados y recibir orientacion desde su perfil registrado.</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
