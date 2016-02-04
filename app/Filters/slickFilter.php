<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class slickFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->fit(1400, 700, function ($constraint) {
		    //$constraint->aspectRatio();
		    //$constraint->upsize();
		})->encode('jpg', 70);
    }
}