<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class slidesetFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->fit(400, 400, function ($constraint) {
		    //$constraint->aspectRatio();
		    //$constraint->upsize();
		})->encode('jpg', 70);
    }
}