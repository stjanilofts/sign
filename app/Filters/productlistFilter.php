<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class productlistFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->resize(420, 420, function ($constraint) {
		    $constraint->aspectRatio();
		    $constraint->upsize();
		});
    }
}