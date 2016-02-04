<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Formable;

class FormableFiles
{
    protected $formable;

    protected $files = [];

    public function __construct($files = array(), Formable $formable)
    {
        $this->files = $files;
        $this->formable = $formable;
    }

    public function add($file = array())
    {
        $this->files[] = $file;
        $this->persist();
    }

    public function first()
    {
        if(is_array($this->files)) {
            return array_key_exists(0, $this->files) ? $this->files[0]['name'] : '';
        }

        return false;
    }

    public function all()
    {
        return $this->files;
    }

    public function exists() {
        if(is_array($this->files)) {
            return array_key_exists(0, $this->files) ? true : false;
        }

        return false;
    }

    public function count()
    {
        return count($this->files) ?: false;
    }

    protected function persist()
    {
        return $this->formable->update(['files' => $this->files]);
    }
}