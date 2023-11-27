<?php

namespace App\Traits;

use App\Librerias\Helpers\LibRoute;
use Illuminate\Support\Facades\Storage;

trait TraitConImagen
{
    /**
     * Recibe el folder de imagenes
     *
     * @return string
     */
    private function GetBaseUrl()
    {
        return $this::GetSlugFromStatic() . "/{$this->id}";
    }

    /**
     * Devuelve el slug de la clase en cuestion
     *
     * @return string
     */
    static private function GetSlugFromStatic()
    {
        $company = LibRoute::getCompany(request());
        $name = str_slug(static::class);

        if ($company) {
            return "{$company->id}/$name";
        }

        return "gafafit/$name";
    }

    /**
     * Guardar Imagen
     *
     * @param        $imagen
     *
     * @param string $destino
     *
     * @return string
     */
    public function UploadImage($imagen, $destino = 'principal')
    {
        $url = $this->GetBaseUrl();
        $info = $this->UploadsTo($url, $imagen, $destino);

        return config('filesystems.default', 'local') === 'azure' ?
            config('filesystems.disks.azure.base_url') . "/" . $info
            :
            config('app.url') . '/' . str_replace('public/', 'storage/', $info);
    }

    /**
     * @param $url
     * @param $imagen
     *
     * @return mixed
     */
    private function UploadsTo($url, $imagen, $destino)
    {
        $folder = env('STORAGE_FOLDER');

        return Storage::putFileAs("public/$folder/" . $url, $imagen, "$destino.{$imagen->getClientOriginalExtension()}");
    }
}
