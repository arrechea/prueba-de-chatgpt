<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 22/11/2018
 * Time: 13:28
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function size(Request $request)
    {
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $url=$request->get('url',null);
        if($url) {
            $data = get_headers($url, 1);
            $size = $data['Content-Length'];

            return $size;
        }

        return '';
    }
}
