<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class bannerFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->fit(1130, 430);
   		  //->greyscale()
   		  //->colorize(0, 10, 30)
   		  //->contrast(-25)
   		  //->brightness(-25)
   		  //->blur(50);
   		  //->gamma(3.5);
    }
}