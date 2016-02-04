<?php

namespace App\Traits;

use App\FormableExtras;

trait FormableExtrasTrait {
	public function extras($locale = '') {
		return new FormableExtras($this->extras, $this, $locale);
	}
}