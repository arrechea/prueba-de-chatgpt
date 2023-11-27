Instalación de imágenes en Azure

- Correr el comando:

    ``composer require matthewbdaly/laravel-azure-storage``
    
- Si se tiene una versión de Laravel anteroir a la 5.5, en el archivo ``config/app.php`` 
hay que añadir dentro del array de providers:

    ``    Matthewbdaly\LaravelAzureStorage\AzureStorageServiceProvider::class,``       
    
- En el archivo ``config/filesystems.php`` en el array ``disks`` hay que añadir:

    ``` php
    'azure' => [
                'driver'    => 'azure',
                'name'      => env('AZURE_STORAGE_NAME'),
                'key'       => env('AZURE_STORAGE_KEY'),
                'container' => env('AZURE_STORAGE_CONTAINER'),
            ],
    ```

- En el .env hay que agregar las variables ``AZURE_STORAGE_NAME`` , `AZURE_STORAGE_KEY` , `AZURE_STORAGE_CONTAINER`
 , si es que no se han agregado todavía.
