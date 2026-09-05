@extends('layouts.app')

@section('title', 'Galeria | Academia Update')

@section('content')
    <main class="my-5 pt-5">
        <h3 class="text-center">Nuestra galeria</h3>
        <section class="gallery-grid">
            @for ($i = 0; $i <= 7; $i++)
                <img src="{{ asset('img/galeria/img0' . $i . '.jpg') }}" alt="Galeria {{ $i + 1 }}" class="img-galeria">
            @endfor
        </section>
    </main>

    <div class="social bg-dark py-5">
        <div class="redes text-white text-center fs-2">
            <i class="bi bi-facebook m-3"></i>
            <i class="bi bi-instagram m-3"></i>
            <i class="bi bi-youtube m-3"></i>
            <i class="bi bi-twitter m-3"></i>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.img-galeria').on('click', function () {
            const modal = '<div class="modelo"><img src="' + this.src + '" alt="" class="img-modelo"><div class="cerrar">x</div></div>';
            $('body').append(modal);
            $('.cerrar, .modelo').on('click', function () {
                $('.modelo').remove();
            });
        });

        $(window).on('keyup', function (event) {
            if (event.which === 27) {
                $('.modelo').remove();
            }
        });
    </script>
@endpush
