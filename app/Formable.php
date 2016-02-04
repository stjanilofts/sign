<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormableImageTrait;
use App\Traits\FormableFileTrait;
use App\Traits\ParentableTrait;
use App\Traits\TranslationTrait;
use App\Traits\FormableExtrasTrait;


class Formable extends Model
{
	use FormableImageTrait, ParentableTrait, TranslationTrait, FormableFileTrait, FormableExtrasTrait;

	protected $casts = [
		'images' => 'json',
		'translations' => 'json',
		'shell' => 'json',
		'skirt' => 'json',
		'files' => 'json',
		'options' => 'json',
		'extras' => 'json'
	];

	protected $fillableExtras = [];

	public function hasExtra($extra = '')
	{
		return array_key_exists($extra, $this->fillableExtras);
	}

	public function getFillables()
	{
		return $this->fillable;
	}

	public $parent_key = 'parent_id';

	public function scopeActive($query)
	{
		return $query->where('status', 1);
	}

	public function scopeDisabled($query)
	{
		return $query->where('status', 0);
	}

	public function fullPath($path = '')
	{
		$path = $this->slug . '/'. $path;

		if( ! $this->{$this->parent_key} || $this->{$this->parent_key} < 1) {
			return $path;
		}

		return $this->parent->fullPath($path);
	}

	public $disable_parent_listing = false;
}
