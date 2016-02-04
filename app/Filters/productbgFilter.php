<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class productbgFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->fit(1400, 700)
   		  ->greyscale()
   		  //->colorize(25, 0, 0)
   		  ->blur(30)
   		  ->contrast(-60)
   		  ->brightness(-30);
    }
}