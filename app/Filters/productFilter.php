<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class productFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->resize(800, 800, function ($constraint) {
		    $constraint->aspectRatio();
		    $constraint->upsize();
		});
    }
}