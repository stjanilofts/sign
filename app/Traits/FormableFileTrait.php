<?php 

namespace App\Traits;

use App\FormableFiles;

trait FormableFileTrait {
	public function file() {
		return new FormableFiles($this->files, $this);
	}
}