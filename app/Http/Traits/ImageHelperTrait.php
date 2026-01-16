<?php
/**
 * Created by PhpStorm.
 * User: Gourab
 * Date: 08/4/19
 */

namespace App\Http\Traits;


use Illuminate\Support\Facades\Log;

trait ImageHelperTrait
{
    public static function imgUpload($image, $path, $time)
    {
        try {
            $image_name = $image->getClientOriginalName();
            $image_ext = $image->getClientOriginalExtension();
            // $name = explode('.',$image_name);
            $image_new_name = $time.'.'.$image_ext;
            $image_dest = $path;
            $image->move($image_dest, $image_new_name);

            return $image_new_name;
        }
        catch ( \Exception $e ) {
            Log::error ( " :: EXCEPTION :: ".$e->getMessage()."\n".$e->getTraceAsString() );
            abort(500);
        }
        return 1;
    }


}
