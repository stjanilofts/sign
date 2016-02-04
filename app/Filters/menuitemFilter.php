<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class menuitemFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
		return $image->fit(491, 293);
    }
}