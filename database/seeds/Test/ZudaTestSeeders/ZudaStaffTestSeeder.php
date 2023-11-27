<?php

use App\Models\Staff\StaffBrands;
use Illuminate\Database\Seeder;
use App\Models\Staff\Staff;

class ZudaStaffTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // -- ZUDA Coaches BEGIN

        Staff::updateOrCreate([
            'id' => '4',
        ], [
            'name'               => 'Nancy',
            'lastname'           => '',
            'slug'               => 'nancy',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Comenzó a dedicarse de manera profesional al ejercicio a partir de 2008. Ha trabajado en
diversos centros fitness de la Ciudad de México, donde se ha desempeñado como coach.
Posee una certificación en cardio fitness, que incluye cardio, escaladora, coreografía, alta
intensidad, combos y estaciones, tonificación, circuitos e intervalos, y en Total Master, que
comprende baile fitness, acondicionamiento físico, fuerza y tonificación. Es coach de
Climb, band en Fitbox.',
            'email'              => 'nancy@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status'             => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 4,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '5',
        ], [
            'name'               => 'Alma',
            'lastname'           => '',
            'slug'               => 'alma',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Desde que Alma llego a Fitbox, su clase de Step Coreográfico es todo un
éxito.Tiene más de 25 años de experiencia impartiendo clases. Actualmente cuenta con
certificaciones en body combat, body attack, body pump, body step, power jump otorgadas
por Body Systems Corporate Wellness, y con una certificación de la Athletics and Fitness
Association of America (AFFA) en step.',
            'email'              => 'alma@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 5,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '6',
        ], [
            'name'               => 'Pau',
            'lastname'           => '',
            'slug'               => 'pau',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Pau lleva más de la mitad de su vida involucrada en el deporte. Comenzó practicando
ritmos latinos y spinning. Cuenta con certificaciones en distintas disciplinas, entre las que
se encuentran zumba, ritmos latinos, box flow, jump, drums y step. Su creatividad hace que
sus clases sean únicas.',
            'email'              => 'pau@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 6,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '7',
        ], [
            'name'               => 'Ana Pau',
            'lastname'           => '',
            'slug'               => 'ana-pau',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Ana Pau practica box de manera regular desde hace dos años. Cuenta con una certificación
como entrenadora otorgada por La Vieja Guardia.',
            'email'              => 'anapau@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 7,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '8',
        ], [
            'name'               => 'Aldonza',
            'lastname'           => '',
            'slug'               => 'aldonza',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Mientras estudiaba la licenciatura en Relaciones Internacionales, Aldonza encontró en la
práctica deportiva una disciplina que la acompaña hasta el día de hoy. Obtuvo la
certificación en Body Pump y en Internacional GRIT Series © (Cardio, Strength and Plyo)
por Body Systems Corporate Wellness, y es Health Coach por el Institute for Integrative
Nutrition (IIN).',
            'email'              => 'aldonza@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 8,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '9',
        ], [
            'name'               => 'Jorge',
            'lastname'           => '',
            'slug'               => 'jorge',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Boxeador. Fue campeón del mundo en peso gallo, con cuatro defensas de su título; disputó
tres títulos mundiales en distintas divisiones. Fue Campeón Centroamericano. Su récord
profesional es de 59 peleas ganadas, 9 perdidas y 2 empates.',
            'email'              => 'jorge@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 9,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '10',
        ], [
            'name'               => 'Itziar',
            'lastname'           => '',
            'slug'               => 'itziar',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Nacida en la Ciudad de México, ha practicado deportes como natación, gimnasia olímpica,
futbol y, desde hace cinco años, box, su favorito. Actualmente reparte su tiempo entre la
carrera de Psicología y las clases de box que imparte en La Vieja Guardia, donde mezcla
box con cardio.',
            'email'              => 'itziar@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 10,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '11',
        ], [
            'name'               => 'Fer',
            'lastname'           => '',
            'slug'               => 'fer',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Practica deportes desde los cuatro años. Comenzó con gimnasia olímpica, y ése fue el punto
de partida para involucrarse en otras disciplinas. Ha participado en programas deportivos de
televisión en el área de conducción y como concursante.',
            'email'              => 'fer@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 11,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '12',
        ], [
            'name'               => 'Alberto',
            'lastname'           => '',
            'slug'               => 'alberto',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Boxeador. Tiene un récord de 10 peleas profesionales, con 7 victorias, 2 derrotas y 1
empate.',
            'email'              => 'alberto@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 12,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '13',
        ], [
            'name'               => 'Constanza',
            'lastname'           => '',
            'slug'               => 'constanza',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Comenzó en la práctica del box hace dos años; su involucramiento la hizo pasar de
aficionada a entrenadora en poco tiempo.',
            'email'              => 'constanza@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 13,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '14',
            'name'               => 'Alfredo',
            'lastname'           => '',
            'slug'               => 'alfredo',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Estudió Administración de Negocios y comenzó a entrenar bajo la guía de Jorge Lacierva.
Experto en box y deportes de contacto, su proyecto promueve la práctica del boxeo tradicional
entre toda una nueva generación de aficionados. Cuenta con gimnasios en Palmas y Zona
Esmeralda. En sus espacios, con instalaciones de primer nivel, conviven profesionales y
principiantes; hombres, mujeres y niños interesados en la práctica de esta disciplina.',
            'email'              => 'alfredo@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
        ], [
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 14,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '15',
        ], [
            'name'               => 'Marella',
            'lastname'           => '',
            'slug'               => 'marella',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Comenzó con la práctica del yoga hace dos años, primero como hobby y luego como estilo
de vida. Actualmente es su profesión. Cuenta con una certificación en Laiyla, Asociación de
Instructores de Yoga de Latinoamérica.',
            'email'              => 'marella@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 15,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '16',
        ], [
            'name'               => 'Edna',
            'lastname'           => '',
            'slug'               => 'edna',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Originaria de Torreón, Coahuila. Proviene de una familia de deportistas y ha practicado
diversas actividades desde la infancia. Comenzó practicando escalada en su ciudad natal, y
más tarde se inició en la práctica del yoga. Se mudó a vivir a la Ciudad de México, donde
continuó en esta actividad, y ha obtenido diversas certificaciones (con Marcos Jassan, con
Jorge Espinosa —Centro Kiai—,con Fabián Montes de Oca, y un certificado de boxing
yoga). En la actualidad está tomando una certificación en yoga prenatal y posparto. En
Zuda se encuentra al frente del área de yoga dentro de La Vieja Guardia.',
            'email'              => 'edna@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 16,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '17',
        ], [
            'name'               => 'Daniela',
            'lastname'           => 'Peña',
            'slug'               => 'daniela',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Daniela tiene una experiencia de más de cuatro años de entrenamiento deportivo, estudios
en entrenamiento y nutrición deportiva, y se ha desempeñado como coach en Cambio de
Hábitos y como health coach. Cuenta con un diplomado de la Universidad del Deporte en
Entrenamiento y Nutrición Deportiva.',
            'email'              => 'daniela@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 17,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '18',
        ], [
            'name'               => 'Silvana',
            'lastname'           => '',
            'slug'               => 'silvana',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Comenzó a practicar yoga hace siete años. Cuenta con certificaciones en vinyasa hatha y
yoga restaurativo en Green Yoga; vinyasa y yoga restaurativo con Mariana Emiko y Brigitte
Longueville, y vinyasa hatha e inversiones con Fabián Montes de Oca.',
            'email'              => 'silvana@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 18,
            'brands_id' => 5,
        ]);


        Staff::updateOrCreate([
            'id'                 => '19',
        ], [
            'name'               => 'Martha',
            'lastname'           => '',
            'slug'               => 'martha',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Desde muy pequeña, Martha practicó actividades como equitación, gimnasia y ballet, pero
no fue sino hasta los 15 años que asumió el deporte como un estilo de vida. Martha estudia
Ingeniería en Desarrollo Sustentable y desde hace tres años practica Indoor Cycling.',
            'email'              => 'martha@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 19,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '20',
        ], [
            'name'               => 'Luciana',
            'lastname'           => 'Amodio',
            'slug'               => 'luciana',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Luciana practica deportes desde que tiene cuatro años. Comenzó con danza y box, pero ha
incursionado en otras disciplinas a lo largo de su vida. Desde hace tres años se involucró
por completo en Spinning.',
            'email'              => 'luciana@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 20,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '21',
        ], [
            'name'               => 'Macarena',
            'lastname'           => '',
            'slug'               => 'macarena',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Practica bicicleta de ruta y spinning. Se integró de manera profesional a la enseñanza de
esta disciplina.',
            'email'              => 'macarena@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 21,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '22',
        ], [
            'name'               => 'Mario',
            'lastname'           => '',
            'slug'               => 'mario',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Mario practica desde hace tres años Indoor Cycling.',
            'email'              => 'mario@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 22,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '23',
        ], [
            'name'               => 'Andrea',
            'lastname'           => '',
            'slug'               => 'andrea',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Nacida en la Ciudad de México, ha impartido clases de baile, funcional, tumbling y body
barre —ejercicio que combina yoga, ballet y pilates—. Ha dado clases de spinning en
diversos gimnasios de la Ciudad de México.',
            'email'              => 'andrea@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/alama.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 23,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '24',
        ], [
            'name'               => 'Pau',
            'lastname'           => '',
            'slug'               => 'pau-1',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Tomó clases de natación y de gimnasia en la infancia, pero desde hace tres años se ha
enfocado en Indoor cycling, a la par que realiza sus estudios universitarios en
Mercadotecnia.',
            'email'              => 'pau1@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/pau.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 24,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '25',
        ], [
            'name'               => 'Paulina Laborie',
            'lastname'           => '',
            'slug'               => 'paulina',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Después de dedicar su vida a la danza y al deporte, Paulina encontró en el Indoor Cycling
una forma de mezclar ambas prácticas. Actualmente cuenta con certificaciones en Fit
Dance por Sport City y es Fitness Coach por la SEP.',
            'email'              => 'paulina@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 25,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '26',
        ], [
            'name'               => 'Vero',
            'lastname'           => '',
            'slug'               => 'vero',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Como practicante primero y como instructora después, Verónica ha estado inmersa en el
entrenamiento en bicicleta. Ha entrenado tanto en México como EU, lo que le ha permitido
trabajar con los coaches de los mejores estudios en el país vecino. Gracias a ello, conoce las
técnicas más recientes, tanto en spinning como en entrenamiento para competencias. Ahora
se encuentra al frente de Cubo, uno de los ejes del trabajo en Zuda.
Versión web
Desde muy pequeña ha practica deportes, pero no fue sino hasta los 15 años que hizo del
deporte un estilo de vida, y empezó a hacer Spinning. A los 20 años comenzó a entrenarse
como maratonista y corrió dos maratones. Poco después regresó a la bicicleta. Hoy, el
deporte es para Verónica la mejor terapia física y mental que existe. Está certificada por
Mad Dogg Athletics en Spinning, Tabata bootcamp, Barre above e Indoor cycling. Verónica
encabeza el área de CUBO, uno de los ejes del trabajo de Zuda.',
            'email'              => 'vero@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/vero.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 26,
            'brands_id' => 5,
        ]);

        Staff::updateOrCreate([
            'id'                 => '27',
        ], [
            'name'               => 'Javier',
            'lastname'           => '',
            'slug'               => 'javier',
            'job'                => 'Instructor',
            'quote'              => '',
            'description'        => 'Estudia Dirección de Empresas de Entretenimiento en la Universidad Anáhuac del Norte.
Participó en el medio maratón de San Diego. Imparte clases de spinning desde hace dos
años.',
            'email'              => 'javier@zuda.mx',
            'phone'              => '',
            'picture_web'        => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_web_list'   => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil'      => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'picture_movil_list' => 'http://dev.gafa.codes/zuda/public/img/data/man.png',
            'status' => 'active',
        ]);

        StaffBrands::updateOrCreate([
            'staff_id'  => 27,
            'brands_id' => 5,
        ]);
        // -- ZUDA Coaches END
    }
}
