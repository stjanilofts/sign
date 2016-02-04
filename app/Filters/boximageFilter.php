<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class boximageFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->contrast(-35)->brightness(35)->greyscale()->fit(400, 400);
    }
}