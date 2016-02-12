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

    /* Festa ákveðna optiona á ákveðnar tegundir af vörum */
    public function staticOptions()
    {
        $staticOptions = [];

        // Þetta fer á alltsaman

        // Efni
        $values = [];
        $values[] = [
            'text' => 'Silfur',
            'value' => '',
            'modifier' => 0,
        ];
        $values[] = [
            'text' => 'Gull',
            'value' => '',
            'modifier' => 0,
        ];
        $values[] = [
            'text' => 'Hvítagull',
            'value' => '',
            'modifier' => 0,
        ];
        $staticOptions[] = [
            'text' => 'Efni',
            'type' => 'select',
            'values' => $values,
        ];

        // Húð
        $values = [];
        $values[] = [
            'text' => 'Gylling',
            'value' => '',
            'modifier' => 0,
        ];
        $values[] = [
            'text' => 'Rósagylling',
            'value' => '',
            'modifier' => 0,
        ];
        $staticOptions[] = [
            'text' => 'Húð',
            'type' => 'select',
            'values' => $values,
        ];


        // Þetta er specific
        switch($this->product->product_type) {
            case("hringur"):
                $values = [];

                for($i = 50; $i <= 64; $i = $i + 2) {
                    $values[] = [
                        'text' => $i,
                        'value' => '',
                        'modifier' => 0,
                    ];
                }

                $staticOptions[] = [
                    'text' => 'Stærð',
                    'type' => 'select',
                    'values' => $values,
                ];

                break;
            case("halsmen"):
                $values = [];

                for($i = 45; $i <= 55; $i = $i + 5) {
                    $values[] = [
                        'text' => $i,
                        'value' => '',
                        'modifier' => 0,
                    ];
                }

                $staticOptions[] = [
                    'text' => 'Lengd keðju',
                    'type' => 'select',
                    'values' => $values,
                ];

                break;
            default:
                break;                
        }

        return $staticOptions;
    }

    public function all()
    {
        return array_merge($this->staticOptions(), $this->product->options()->get());
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