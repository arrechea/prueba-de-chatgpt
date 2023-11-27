<?php

use App\Models\Staff\StaffBrands;
use Illuminate\Database\Seeder;
use App\Models\Staff\Staff;

class StaffTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::updateOrCreate([
            'id'         => '1',
            'name'         => 'Gabriel',
            'lastname'     => 'Rojo de la Vega',
            'slug'         => 'gabriel',
            'job'          => 'Fundador y CEO',
            'quote'     => 'Integrity is congruence between what you know, what you profess, and what you do.',
            'description'     => 'Fundador del T3mplo y creador del sistema de entrenamiento R.3.D (Resilience, Endurance, Dynamic.)

El deporte y desarrollo físico han estado presentes en mi vida desde muy joven. La oportunidad de practicar diferentes disciplinas desde el futbol hasta las artes marciales, calistenia, atletismo, gimnasia entre otras marcaron mis intereses y mi carácter hasta el día de hoy.

No existe una disciplina única que pueda llevarnos a la total funcionalidad y desarrollo de las aptitudes físicas, es así como nace en mi la inquietud de crear un sistema de entrenamiento que sin excluir, mezcle y sume de tal manera que el tiempo dedicado al entrenamiento sea integral y eficiente, obteniendo como resultado los beneficios de un atleta completo.

Así surge R3D, Resilience endurance dynamic…

Como sus siglas lo explican, R3D ofrece el fortalecimiento práctico de la capacidad de recuperación , sometiendo al cuerpo a una constante reto que obliga al crecimiento y desarrollo ( resilience), lo cual se logra a través de un sistema que genera resistencia tanto fisica como mental de manera progresiva (endurance), en una constante dinámica de cambio y adaptación ( dynamic) cuyo objetivo final el alcance total y completo de los potenciales físicos y mentales del atleta… y por supuesto del ser humano.',
            'email'     => 'gabriel@elt3mplo.mx',
            'phone'     => '5555555555',
            'picture_web'     => 'http://dev.gafa.codes/elt3mplo/public/img/Gabriel-2.jpg',
            'picture_web_list' => 'http://dev.gafa.codes/elt3mplo/public/img/Gabriel-2.jpg',
            'picture_movil'     => 'http://admin.siclo.com/uploads/instructores/instructor_121887_C.png',
            'picture_movil_list' => 'http://admin.siclo.com/uploads/instructores/instructor_121887_C.png',
        ], [
            'status' => 'active',
        ]);

        Staff::updateOrCreate([
            'id'         => '2',
            'name'         => 'Jon',
            'lastname'     => '',
            'slug'         => 'jon',
            'job'          => 'Coach',
            'quote'     => 'Integrity is congruence between what you know, what you profess, and what you do.',
            'description'     => 'Fundador del T3mplo y creador del sistema de entrenamiento R.3.D (Resilience, Endurance, Dynamic.)

El deporte y desarrollo físico han estado presentes en mi vida desde muy joven. La oportunidad de practicar diferentes disciplinas desde el futbol hasta las artes marciales, calistenia, atletismo, gimnasia entre otras marcaron mis intereses y mi carácter hasta el día de hoy.

No existe una disciplina única que pueda llevarnos a la total funcionalidad y desarrollo de las aptitudes físicas, es así como nace en mi la inquietud de crear un sistema de entrenamiento que sin excluir, mezcle y sume de tal manera que el tiempo dedicado al entrenamiento sea integral y eficiente, obteniendo como resultado los beneficios de un atleta completo.

Así surge R3D, Resilience endurance dynamic…

Como sus siglas lo explican, R3D ofrece el fortalecimiento práctico de la capacidad de recuperación , sometiendo al cuerpo a una constante reto que obliga al crecimiento y desarrollo ( resilience), lo cual se logra a través de un sistema que genera resistencia tanto fisica como mental de manera progresiva (endurance), en una constante dinámica de cambio y adaptación ( dynamic) cuyo objetivo final el alcance total y completo de los potenciales físicos y mentales del atleta… y por supuesto del ser humano.',
            'email'     => 'jon@elt3mplo.mx',
            'phone'     => '5555555555',
            'picture_web'     => 'http://dev.gafa.codes/elt3mplo/public/img/Jon-2.jpg',
            'picture_web_list' => 'http://dev.gafa.codes/elt3mplo/public/img/Jon-2.jpg',
            'picture_movil'     => 'https://siclo.com/wp-content/uploads/2017/05/Instructore_new.jpg',
            'picture_movil_list' => 'https://siclo.com/wp-content/uploads/2017/05/Instructore_new.jpg',
        ], [
            'status' => 'active',
        ]);

        Staff::updateOrCreate([
            'id'         => '3',
            'name'         => 'Mark',
            'lastname'     => '',
            'slug'         => 'mark',
            'job'          => 'Coach',
            'quote'     => 'Integrity is congruence between what you know, what you profess, and what you do.',
            'description'     => 'Fundador del T3mplo y creador del sistema de entrenamiento R.3.D (Resilience, Endurance, Dynamic.)

El deporte y desarrollo físico han estado presentes en mi vida desde muy joven. La oportunidad de practicar diferentes disciplinas desde el futbol hasta las artes marciales, calistenia, atletismo, gimnasia entre otras marcaron mis intereses y mi carácter hasta el día de hoy.

No existe una disciplina única que pueda llevarnos a la total funcionalidad y desarrollo de las aptitudes físicas, es así como nace en mi la inquietud de crear un sistema de entrenamiento que sin excluir, mezcle y sume de tal manera que el tiempo dedicado al entrenamiento sea integral y eficiente, obteniendo como resultado los beneficios de un atleta completo.

Así surge R3D, Resilience endurance dynamic…

Como sus siglas lo explican, R3D ofrece el fortalecimiento práctico de la capacidad de recuperación , sometiendo al cuerpo a una constante reto que obliga al crecimiento y desarrollo ( resilience), lo cual se logra a través de un sistema que genera resistencia tanto fisica como mental de manera progresiva (endurance), en una constante dinámica de cambio y adaptación ( dynamic) cuyo objetivo final el alcance total y completo de los potenciales físicos y mentales del atleta… y por supuesto del ser humano.',
            'email'     => 'mark@elt3mplo.mx',
            'phone'     => '5555555555',
            'picture_web'     => 'http://dev.gafa.codes/elt3mplo/public/img/Mark-2.jpg',
            'picture_web_list' => 'http://dev.gafa.codes/elt3mplo/public/img/Mark-2.jpg',
            'picture_movil'     => 'https://siclo.com/wp-content/uploads/2016/09/Interior-instructor_18.jpg',
            'picture_movil_list' => 'https://siclo.com/wp-content/uploads/2016/09/Interior-instructor_18.jpg',
        ], [
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 1,
            'brands_id' => 1,
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 2,
            'brands_id' => 1,
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 3,
            'brands_id' => 1,
        ]);
    }
}
