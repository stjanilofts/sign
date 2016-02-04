<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class lFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
		return $image->resize(null, 500, function ($constraint) {
		    $constraint->aspectRatio();
		});
    }
}