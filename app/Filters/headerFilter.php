<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class headerFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		//return $image->fit(1600, 400)->contrast(-25)->brightness(25)->greyscale()->blur(25);
   		return $image->fit(1600, 400)->brightness(-35);
    }
}