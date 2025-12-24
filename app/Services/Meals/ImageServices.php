<?php

namespace App\Services\Meals ;
use Illuminate\Support\Facades\Storage;

class ImageServices
{
    public function uploade($newImage)
    {
        $image = $newImage->getClientOriginalName() ; // مش هعمل if عشان كدة كدة الداتا عملتلها validate
        Storage::disk('meals')->put($image , file_get_contents($newImage));
        return $image ;
    }

    public function delete($image)
    {
        if(Storage::disk('meals')->exists($image))
        {
            Storage::disk('meals')->delete($image);
        }
    }

    public function update($oldImage , $newImage)
    {
        $this->delete($oldImage) ;
        return $this->uploade($newImage) ;
    }
}

