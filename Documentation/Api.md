#Trabajar con API

Para poder trabajar con el api lo primero que deberá poseer el sistema son credenciales de la app que hará la solicitud.

Para ello:
1. correr el comando: `php artisan passport:client --password`
2. correr el comando: `php artisan passport:install`
3. Integrar en postman archivo collection.
4. Configurar postman con id de cliente en 1 y el token secreto correspondiente.

Es importante tener en cuenta que para consumir el api se deben enviar las credenciales de un usuario NO ADMINISTRADOR.

Esta API solamente se usará para consumirse desde front. Si se desea un endpoint administrativo se realizará en los mismos ruteos del admin.
