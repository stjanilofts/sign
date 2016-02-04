<?php

namespace App\Traits;

use App\ProductOptions;

trait ProductOptionsTrait {
	public function options() {
		return new ProductOptions($this->options, $this);
	}
}