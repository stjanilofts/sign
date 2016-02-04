<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class tinyFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
		// prevent possible upsizing
		return $image->fit(16, 16);
    }
}