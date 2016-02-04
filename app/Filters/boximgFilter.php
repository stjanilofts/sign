<?php namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class boximgFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
   		return $image->fit(480, 480)->brightness(-35);
    }
}