<?php

namespace App\Traits;

use App\FormableImages;

trait FormableImageTrait {
	public function img() {
		return new FormableImages($this->images, $this, 'images');
	}

	public function shell() {
		return new FormableImages($this->shell, $this, 'shell');
	}

	public function skirt() {
		return new FormableImages($this->skirt, $this, 'skirt');
	}
}