<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class cartimageFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->resize(null, 100, function ($constraint) {
		    $constraint->aspectRatio();
		    $constraint->upsize();
		});
    }
}