<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class articleFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
		return $image->resize(1130, null, function ($constraint) {
		    $constraint->aspectRatio();
		});
    }
}