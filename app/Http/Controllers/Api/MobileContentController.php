<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class MobileContentController extends Controller
{
    use ApiResponse;

    public function home()
    {
        return $this->success([
            'title' => 'Academia Update',
            'summary' => 'Somos una academia enfocada en acompanar a cada estudiante con recursos claros, clases dinamicas y seguimiento constante.',
            'banners' => [
                asset('img/banner1.png'),
                asset('img/banner2.png'),
                asset('img/banner3.png'),
            ],
            'sections' => [
                [
                    'title' => 'Nuestra empresa',
                    'body' => 'Formacion accesible, practica y ordenada para avanzar a tu ritmo.',
                    'image' => asset('img/img00.png'),
                ],
                [
                    'title' => 'Aprende con nosotros',
                    'body' => 'Accede a contenidos, actividades y una experiencia pensada para mejorar tus resultados.',
                    'image' => asset('img/img01.png'),
                ],
            ],
        ]);
    }

    public function gallery()
    {
        return $this->success([
            'title' => 'Nuestra galeria',
            'images' => collect(range(0, 7))
                ->map(fn (int $index) => asset('img/galeria/img0'.$index.'.jpg'))
                ->all(),
            'social' => ['Facebook', 'Instagram', 'YouTube', 'Twitter'],
        ]);
    }

    public function contact()
    {
        return $this->success([
            'title' => 'Contactos',
            'map_url' => 'https://www.google.com/maps?q=Plaza%20Mayor%20de%20Lima&output=embed',
            'fields' => ['Apellidos y nombres', 'Direccion', 'Correo', 'Comentarios'],
        ]);
    }
}
