<?php

use App\Models\Service\ServiceSpecialText;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ZudaServicesTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::updateOrCreate([
            'id'           => 6,
            'name'         => 'Fitbox',
            'category'     => 'Zuda Class',
            'hide_in_home' => false,
            'pic'          => 'http://dev.gafa.codes/zuda/public/img/data/fitbox.png',
            'description'  => 'Exclusivo para mujeres, con rutinas diseñadas —por mujeres— para ejercitar todo el cuerpo. Nuestras sesiones duran una hora, en la que se integran técnicas de cardio, intervalos y tonificación. Música y coreografías complementan la experiencia.
',
            'companies_id' => 3,
            'brands_id'    => 5,
        ], [
            'status' => 'active',
        ]);

        Service::updateOrCreate([
            'id'           => 7,
            'name'         => 'Cubo',
            'category'     => 'Zuda Class',
            'hide_in_home' => false,
            'pic'          => 'http://dev.gafa.codes/zuda/public/img/data/cubo.png',
            'description'  => 'Las técnicas más innovadoras para entrenar en bicicleta y obtener los máximos beneficios para tu cuerpo. Contamos con los avances tecnológicos recientes en cuanto a sonido e iluminación para acompañar nuestras clases. 						
Nuestras rutinas, acompañadas por música y coreografías, son un ejercicio aeróbico ideal. Sesiones que incluyen intervalos de calentamiento, aceleración, resistencia, recuperación y estiramiento para lograr una práctica intensa y divertida. 					
Entre los resultados que obtendrás, se encuentran una mayor coordinación, resistencia, tonificación muscular, elasticidad, disminución del estrés y mayor capacidad de concentración, así como el fortalecimiento de tu sistema cardiovascular.
',
            'companies_id' => 3,
            'brands_id'    => 5,
        ], [
            'status' => 'active',
        ]);

        Service::updateOrCreate([
            'id'           => 8,
            'name'         => 'La Vieja Guardia',
            'category'     => 'Zuda Class',
            'hide_in_home' => false,
            'pic'          => 'http://dev.gafa.codes/zuda/public/img/data/vieja_escuela.png',
            'description'  => 'Aprende y practica box con instructores especializados. Uno de los deportes más tradicionales de México, el cual te permite obtener una forma físcia compacta en poco tiempo y de manera divertida. Cerrando la clase con los beneficios del Yoga, para ayudar a tu organismo, a estirar, reflexionar y recuperarse.
La práctica del box activa el sistema cardiovascular, potencia el tono muscular, mejora la flexibilidad, los reflejos y la velocidad mental, y aumenta la confianza en uno mismo. La práctica de Yoga activa tu fuerza, resistencia, flexibilidad y conectando tu cuerpo con la mente.
',
            'companies_id' => 3,
            'brands_id'    => 5,
        ], [
            'status' => 'active',
        ]);
    }
}
