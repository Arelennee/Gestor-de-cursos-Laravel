# Guía para la Creación de Datos de Prueba (Seeders)

Los "seeders" en Laravel son clases que se utilizan para poblar la base de datos con datos de prueba. Esto es extremadamente útil para probar la funcionalidad de la aplicación sin tener que crear manualmente usuarios, cursos, etc., cada vez que reinicias la base de datos.

## Paso 1: Crear los Archivos Seeder

Vamos a crear dos seeders: uno para los usuarios (`UserSeeder`) y otro para los cursos y lecciones (`CursoSeeder`). Abre tu terminal y ejecuta los siguientes comandos:

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder CursoSeeder
```

Estos comandos crearán dos nuevos archivos en `database/seeders/`.

## Paso 2: Escribir la Lógica del UserSeeder

Abre el archivo `database/seeders/UserSeeder.php` y reemplaza su contenido con el siguiente código. Este código creará tres usuarios: un administrador, un profesor y un estudiante.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un Administrador
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_ADMIN,
        ]);

        // Crear un Profesor
        User::create([
            'name' => 'Profesor User',
            'email' => 'profesor@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_PROFESOR,
        ]);

        // Crear un Estudiante
        User::create([
            'name' => 'Estudiante User',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_ESTUDIANTE,
        ]);
    }
}
```

## Paso 3: Escribir la Lógica del CursoSeeder

Abre el archivo `database/seeders/CursoSeeder.php`. Este seeder creará un par de cursos y los asignará al profesor que creamos en el paso anterior. También creará algunas lecciones para esos cursos.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encontrar al usuario profesor
        $profesor = User::where('rol', User::ROL_PROFESOR)->first();

        if ($profesor) {
            // Crear un curso para el profesor
            $cursoLaravel = Curso::create([
                'user_id' => $profesor->id,
                'nombre' => 'Curso de Laravel desde Cero',
                'descripcion' => 'Aprende los fundamentos de Laravel para crear aplicaciones web modernas.',
            ]);

            // Añadir algunas lecciones al curso
            $cursoLaravel->lecciones()->createMany([
                ['titulo' => 'Introducción a Laravel', 'contenido' => 'Contenido de la lección 1...'],
                ['titulo' => 'Rutas y Controladores', 'contenido' => 'Contenido de la lección 2...'],
                ['titulo' => 'Vistas con Blade', 'contenido' => 'Contenido de la lección 3...'],
            ]);

            // Crear otro curso
            $cursoVue = Curso::create([
                'user_id' => $profesor->id,
                'nombre' => 'Curso de Vue.js',
                'descripcion' => 'Domina el framework progresivo de JavaScript.',
            ]);
             // Añadir algunas lecciones al curso
             $cursoVue->lecciones()->createMany([
                ['titulo' => 'Introducción a Vue', 'contenido' => 'Contenido de la lección 1...'],
                ['titulo' => 'Componentes y Props', 'contenido' => 'Contenido de la lección 2...'],
            ]);
        }
    }
}
```

## Paso 4: Llamar a los Seeders desde DatabaseSeeder

Ahora, necesitamos decirle a Laravel que ejecute nuestros nuevos seeders. Abre el archivo `database/seeders/DatabaseSeeder.php` y modifica el método `run()` para que llame a los seeders que creamos.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CursoSeeder::class,
        ]);
    }
}
```

## Paso 5: Ejecutar los Seeders

Finalmente, para poblar tu base de datos, puedes usar uno de los siguientes comandos:

**Opción A: Solo ejecutar los seeders (si ya tienes la base de datos migrada)**
```bash
php artisan db:seed
```

**Opción B: Reiniciar la base de datos y ejecutar los seeders (¡OJO: borra todos los datos!)**
Este comando es muy útil durante el desarrollo. Borra todas las tablas, las vuelve a crear y luego ejecuta los seeders.

```bash
php artisan migrate:fresh --seed
```

Después de ejecutar el comando, tu base de datos tendrá un administrador, un profesor y un estudiante, además de dos cursos con lecciones, listos para que pruebes toda la funcionalidad del backend y las vistas.
