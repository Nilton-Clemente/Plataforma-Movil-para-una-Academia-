<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@academia.test',
        ], [
            'name' => 'Administrador',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::updateOrCreate([
            'email' => 'usuario@academia.test',
        ], [
            'name' => 'Usuario Comun',
            'password' => 'password',
            'is_admin' => false,
        ]);

        Book::updateOrCreate([
            'title' => 'Matematica Basica para Estudiantes',
        ], [
            'description' => 'Libro introductorio con ejercicios resueltos para reforzar operaciones, algebra y razonamiento matematico.',
            'price' => 35.00,
        ]);

        Book::updateOrCreate([
            'title' => 'Comunicacion y Redaccion Practica',
        ], [
            'description' => 'Material de apoyo para mejorar comprension lectora, ortografia y redaccion academica.',
            'price' => 28.50,
        ]);

        Book::updateOrCreate([
            'title' => 'Guia de Ciencias Integradas',
        ], [
            'description' => 'Resumen claro de biologia, fisica y quimica con actividades para preparacion escolar.',
            'price' => 42.00,
        ]);
    }
}
