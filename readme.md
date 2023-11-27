#Gafa fit

## Instalacion:
 - composer install

## Base de datos
### Para correr las migraciones y cargar los datos reales por defecto
 - php artisan migrate --seed
### Para correr las migraciones y cargar los datos de prueba
 - php artisan migrate
 - php artisan db:seed --class=DatabaseTestSeeder

 ## Para establecer los 'queues' para los 'jobs' del sistema
  - Para iniciar el supervisor:

      `bin/initServer.sh`
  - Reiniciar todos los trabajos de supervisor:

    `bin/initServer.sh`

         **Importante: estos comandos no se deben de correr con 'sudo'

   - Modificar el archivo '.env' para que tenga los datos:
  `QUEUE_DRIVER=database`

  ## Establecer los cron jobs
  - En el servidor usar el comando `crontab -e`

  - En el archivo, editar y agregar la línea:

        * * * * * php /home/vagrant/localhost/gafa.fit/artisan schedule:run

    *Cambiar la ruta del proyecto dependiendo

## Sistema de peticiones

### En administración
Cualquier petición del sistema se hará utilizando App\Http\Requests\AdminRequest o un FormRequest extendido y utilizado por este.
Este elemento se encargará de comprobar si un administrador tiene permiso para trabajar sobre la companía en cuestión.

Los demás request, por ejemplo, App\Http\Controllers\Admin\UserController, utilizarán RequestsForms de Laravel específicos que extenderán App\Http\Requests\GafaFitRequest.
### En API
Se respetará lo dicho en el punto anterior pero además deberá el controllador extender siempre a App\Http\Controllers\ApiController

## Sistema de controladores

### En administración
Cualquier controlador del sistema para uso del administrador deberá heredar de App\Http\Controllers\AdminController o un Controller extendido por este.
### En API
Cualquier controlador del sistema para uso de la API deberá heredar de App\Http\Controllers\ApiController o un Controller extendido por este.
### En Front
Cualquier controlador del sistema para el front deberá heredar de App\Http\Controllers\FrontController o un Controller extendido por este.

## Creación de vistas

Cualquier llamada a vistas del sistema deberá usar la biblioteca VistasGafaFit.

O sea, en lugar de usar:
- return view('admin.home', compact('data'));

Se debe usar:
- return VistasGafaFit::view('admin.home', compact('data'));

# Integración con Google reCAPTHCA

Para hacer uso del captcha de Google, se debe de incluir en el archivo .env dos nuevas variables:
- CAPTCHA_SITE_KEY=*Llave pública*
- CAPTCHA_SECRET_KEY=*Llave privada*

# Integración con Stripe
Para hacer uso de Stripe, se deben de incluir en el archivo .env tres nuevas variables:
- STRIPE_KEY=*Llave pública*
- STRIPE_SECRET=*Llave privada*
- STRIPE_PRODUCT=*ID del producto que contiene los planes de suscripción*

## Datos del plan en Stripe

Dentro del plan de pago en Stripe, definir como metadatos (`metadata`) las siguientes variables:
- `details`: texto con los detalles de pago, para mostrar al usuario en la interfaz
- `locations`: número máximo de ubicaciones que permite crear el plan; 0, para dejar sin límite
- `classrooms`: número máximo de tipos de clase que permite crear el plan; 0, para dejar sin límite
- `premium`: indica si es una suscripción PREMIUM, en la que el cliente dede se ser contactado personalmente.

