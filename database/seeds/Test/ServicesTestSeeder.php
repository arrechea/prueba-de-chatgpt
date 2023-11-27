<?php

use App\Models\Service\ServiceSpecialText;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::updateOrCreate([
            'id'           => 1,
            'name'         => 'BRAVO TEAM',
            'category'     => 'T3MPLO TRAINING',
            'hide_in_home' => false,
            'pic'          => 'http://dev.gafa.codes/elt3mplo/public/img/t-bravo.jpg',
            'description'  => 'En El T3mplo adventures buscamos reconectarte con el espíritu aventurero que todos llevamos, y despertar una conciencia hacia el ambiente Natural que nos rodea. Queremos hacerte reconocer que el cuerpo y el espíritu pueden llegar mucho más lejos de lo que uno piensa. Pero además, queremos ser una herramienta para que te conectes contigo mismo en un entorno natural.

Eso puede ser desde dormir por primera vez al aire libre, en un bosque, o una montaña, poner a prueba tu estado físico y mental al llegar a las cumbres más altas de México, vencer tus miedos a la altura rapeleando o tu miedo al agua con bajar los rápidos de Veracruz.

Nuestras aventuras se basan en ética y valores con principios. Si queremos cuidar nuestro entorno natural, lo necesitamos conocer y tener una relación con el. Solo así lo podemos querer, lo podemos llegar a extrañar y entonces lo querremos proteger.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ], [
            'status' => 'active',
        ]);

        ServiceSpecialText::updateOrCreate([
            'services_id'  => 1,
            'tag'          => 'salidas',
            'title'        => 'Salidas',
            'order'        => 1,
            'description'  => 'Primeros miércoles de cada mes.
* Sujeto a cambio.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ]);

        ServiceSpecialText::updateOrCreate([
            'services_id'  => 1,
            'tag'          => 'incluye',
            'title'        => 'Incluye',
            'order'        => 2,
            'description'  => '• Transporte
• Entrada parque nacional
• Seguridad
• Equipo para dormir
• Alimentos y bebidas
• Kit',
            'companies_id' => 1,
            'brands_id'    => 1,
        ]);

        Service::updateOrCreate([
            'id'           => 2,
            'name'         => 'BOOTCAMP',
            'category'     => 'FIGTH3R',
            'hide_in_home' => false,
            'pic'          => 'http://dev.gafa.codes/elt3mplo/public/img/t-bootcamp.jpg',
            'description'  => 'Entrenamiento de alto rendimiento en playa o montaña, con dos a tres sesiones al día, acompañado de la mejor alimentación, descanso y sesiones de meditación.
Objetivo Crear conciencia sobre nosotros mismos y tomar mejores decisiones en nuestro estilo de vida.

Un fin de semana de desconectar, restaurar y conectar con lo más esencial de ti: tu cuerpo, mente y espíritu.

Apto para todos los niveles.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ], [
            'status' => 'active',
        ]);

        ServiceSpecialText::updateOrCreate([
            'services_id'  => 2,
            'tag'          => 'incluye',
            'title'        => 'Incluye',
            'order'        => 1,
            'description'  => '• Hospedaje
• Dieta detox alcalina
• Entrenamientos
• Kit',
            'companies_id' => 1,
            'brands_id'    => 1,
        ]);

        Service::updateOrCreate([
            'id'           => 3,
            'name'         => 'MUA THAI',
            'category'     => 'FIGTH3R',
            'hide_in_home' => true,
            'pic'          => 'http://dev.gafa.codes/elt3mplo/public/img/t-fighter.jpg',
            'description'  => 'Es un arte marcial y deporte de contacto tailandés, el cual se desarrolla de pie por medio de golpes con técnicas combinadas de piernas, brazos, pies y codos.

Un programa diseñado para todos los niveles, avanzados, intermedios y principiantes; como para peleadores a nivel profesional y amateur.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ], [
            'status' => 'active',
        ]);

        Service::updateOrCreate([
            'id'           => 4,
            'name'         => 'ADVENTURES',
            'category'     => 'T3MPLO TRAINING',
            'hide_in_home' => true,
            'pic'          => 'http://elt3mplo.front.test/img/t-adventures.jpg',
            'description'  => 'En El T3mplo adventures buscamos reconectarte con el espíritu aventurero que todos llevamos, y despertar una conciencia hacia el ambiente Natural que nos rodea. Queremos hacerte reconocer que el cuerpo y el espíritu pueden llegar mucho más lejos de lo que uno piensa. Pero además, queremos ser una herramienta para que te conectes contigo mismo en un entorno natural.

Eso puede ser desde dormir por primera vez al aire libre, en un bosque, o una montaña, poner a prueba tu estado físico y mental al llegar a las cumbres más altas de México, vencer tus miedos a la altura rapeleando o tu miedo al agua con bajar los rápidos de Veracruz.

Nuestras aventuras se basan en ética y valores con principios. Si queremos cuidar nuestro entorno natural, lo necesitamos conocer y tener una relación con el. Solo así lo podemos querer, lo podemos llegar a extrañar y entonces lo querremos proteger.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ], [
            'status'  => 'active',
        ]);

        ServiceSpecialText::updateOrCreate([
            'services_id'  => 4,
            'tag'          => 'salidas',
            'title'        => 'Salidas',
            'order'        => 1,
            'description'  => 'Primeros miércoles de cada mes.
* Sujeto a cambio.',
            'companies_id' => 1,
            'brands_id'    => 1,
        ]);

        ServiceSpecialText::updateOrCreate([
            'services_id'  => 4,
            'tag'          => 'incluye',
            'title'        => 'Incluye',
            'order'        => 2,
            'description'  => '• Transporte
• Entrada parque nacional
• Seguridad
• Equipo para dormir
• Alimentos y bebidas
• Kit',
            'companies_id' => 1,
            'brands_id'    => 1,
        ]);
    }
}
