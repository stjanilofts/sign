<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formable;

class ProductOptions
{
    protected $product;

    protected $options = [];

    public function __construct($options = array(), Product $product)
    {
        $this->options = $options;
        $this->product = $product;
    }

    public function add($option = array())
    {
        $this->options[] = $option;
        $this->persist();
    }

    public function remove($idx = -1)
    {
        if($idx >= 0) {
	        unset($this->options[$idx]);
	    }

        $this->persist();
    }

    public function get()
    {
        return $this->options;
    }

    public function count()
    {
        return count($this->options) ?: false;
    }

    protected function persist()
    {
        return $this->product->update(['options' => $this->options]);
    }

    public function exists() {
        if(is_array($this->options)) {
            return array_key_exists(0, $this->options) ? true : false;
        }

        return false;
    }
}