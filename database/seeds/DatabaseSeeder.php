<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //  eliminamos los directorios de imagenes
        Storage::deleteDirectory('courses');    //eliminar un directorio determinado
        Storage::deleteDirectory('users');

        //  creamos los directorios de imagenes
        Storage::makeDirectory('courses');
        Storage::makeDirectory('users');

        //  creamos nuestros roles

        factory(App\Role::class, 1)->create(['name' => 'admin']);
        factory(App\Role::class, 1)->create(['name' => 'teacher']);
        factory(App\Role::class, 1)->create(['name' => 'student']);

        //  usuarios

        factory(App\User::class, 1)->create([
            'name'      =>  'admin',
            'email'     =>  'admin@gmail.com',
            'password'  =>  bcrypt('test1234'),
            'role_id'   =>  \App\Role::ADMIN,
        ])
        ->each( function ($user) {
            factory(App\Student::class, 1)->create(['user_id' => $user->id]); //  agregamos un nuevo id para un estudiante
        });

        //  creamos 50 usuarios y los relacionamos con la tabla estudiantes mediante su id
        factory(App\User::class, 50)->create()
            ->each(function ($user) {
                factory(App\Student::class, 1)->create(['user_id' => $user->id]);
            });

        //  creamos 10 usuarios los cuales seran profesores y los relacionamos con la tabla profesores por su id
        //  de igual manera creamos su respectiva relacion con la tabla student.
        factory(App\User::class, 10)->create()
            ->each(function ($user) {
                factory(App\Student::class, 1)->create(['user_id' => $user->id]);
                factory(App\Teacher::class, 1)->create(['user_id' => $user->id]);
            });

        //  creamos los niveles

        factory(App\Level::class, 1)->create(['name' => 'Beginner']);
        factory(App\Level::class, 1)->create(['name' => 'Intermediate']);
        factory(App\Level::class, 1)->create(['name' => 'Advanced']);

        //  creamos la factoria para las categorias
        factory(App\Category::class, 5)->create();

        //  creamos las factorias para los cursos
        //  la funcion each es un callback
        factory(App\Course::class, 50)->create()
            ->each(function ($course) {
                $course->goals()->saveMany( factory(App\Goal::class, 2)->create() );
                $course->requirements()->saveMany( factory(App\Requirement::class, 5)->create() );
            });
        
    }
}
