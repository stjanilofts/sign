<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formable;

class FormableImages
{
    protected $formable;
    protected $field;
    protected $images = [];

    public function __construct($images = array(), Formable $formable, $field = false)
    {
        $this->images = $images;
        $this->formable = $formable;
        $this->field = $field ?: false;
    }

    public function add($image = array())
    {
        $size = getimagesize(public_path().'/uploads/'.$image['name']);

        $image['width'] = $size[0];
        $image['height'] = $size[1];

        $this->images[] = $image;
        $this->persist();
    }

    public function remove($idx = -1)
    {
        $images = [];

        foreach($this->images as $k => $image) {
            if($k == $idx) {
                \File::delete(public_path().'/uploads/'.$image['name']);
            } else {
                $images[] = $image;
            }
        }

        $this->images = $images;

        $this->persist();
    }

    public function first()
    {
        if(is_array($this->images)) {
            if(array_key_exists(0, $this->images)) {
                foreach(config('imagecache.paths') as $p) {
                    if(file_exists($p.'/'.$this->images[0]['name'])) {
                        return $this->images[0]['name'];
                    }
                }
            }
        }

        return 'spurningamerki.jpg';
    }

    public function title()
    {
        if(is_array($this->images)) {
            if(array_key_exists(0, $this->images)) {
                foreach(config('imagecache.paths') as $p) {
                    if(file_exists($p.'/'.$this->images[0]['name'])) {
                        if(array_key_exists('title', $this->images[0])) {
                            return $this->images[0]['title'];
                        }
                    }
                }
            }
        }

        return '';
    }

    public function nr($nr = 1)
    {
        if($nr >= 1) {
            $idx = $nr - 1;
            return array_key_exists($idx, $this->images) ? $this->images[$idx]['name'] : 'spurningamerki.jpg';
        }

        return $this->first();
    }

    public function exists() {
        if(is_array($this->images)) {
            return array_key_exists(0, $this->images) ? true : false;
        }

        return false;
    }

    public function all($skip_first = false)
    {
        if($skip_first) {
            array_shift($this->images);
        }

        return $this->images;
    }

    public function count()
    {
        return count($this->images) ?: false;
    }

    protected function persist()
    {
        $this->formable->{$this->field} = $this->images;
        $this->formable->save();
    }
}