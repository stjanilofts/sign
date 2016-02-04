<?php

namespace App\Traits;

use App\FormableTranslate;

trait TranslationTrait {
	public function translations($locale = '') {
		return new FormableTranslate($this->translations, $this, $locale);
	}

	public function translation($attribute) {
		return $this->translations()->get($attribute);
	}
}