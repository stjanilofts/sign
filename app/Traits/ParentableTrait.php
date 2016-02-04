<?php

namespace App\Traits;

trait ParentableTrait {

	public function hasParent()
    {
        return $this->{$this->parent_key} > 0;
    }

    public function getSubIds(&$ids = array())
    {
        $Model = "\App\\".ucfirst($this->modelName);
        $m = new $Model;
        $subs = $m->where($this->parent_key, $this->id)->get();

        if ( ! $subs->isEmpty())
        {
            foreach ($subs as $sub) {
                
                $ids[] = $sub->id;

                $Model = "\App\\".ucfirst($this->modelName);
                $m = new $Model;
                $subsubs = $m->where($this->parent_key, $sub->id)->get();

                if (!$subsubs->isEmpty()) {
                    $sub->getSubIds($ids);
                }
            }
        }
        
        return $ids;
    }


    public function parent()
    {
        return $this->belongsTo('\App\\'.$this->modelName, $this->parent_key, 'id');
    }

    public function hasSubs()
    {
        $Model = "\App\\".ucfirst($this->hlutur);
        $m = new $Model;
        return $m->where($m->parent_key, $this->id)->where('status', '>', 0)->exists();
    }


    public function getAllSubs()
    {
        $Model = "\App\\".ucfirst($this->modelName);
        $m = new $Model;
        return $m->where($this->parent_key, $this->id)->get();
    }

    public function getSubs()
    {
        $Model = "\App\\".ucfirst($this->modelName);
        $m = new $Model;
        return $m->where($this->parent_key, $this->id)->where('status', '>', 0)->get();
    }

    public function getSubCount()
    {
        $Model = "\App\\".ucfirst($this->modelName);
        $m = new $Model;
        return $m->where($this->parent_key, $this->id)->count();
    }

    public function getRandomSub()
    {
        $Model = "\App\\".ucfirst($this->modelName);
        $m = new $Model;
        return $m->where($this->parent_key, $this->id)->where('status', '>', 0)->get()->random();
    }

    public function path($str = '') {
        if($this->{$this->parent_key})
        {
            $Model = "\App\\".ucfirst($this->modelName);
            $m = new $Model;
            $parent = $m->find($this->{$this->parent_key});

            $str = $parent->slug.'/'.$str;

            $parent->path($str);
        }

        return $str.$this->slug;
    }

    public function getRootPage()
    {
        if( ! $this->{$this->parent_key}) {
            return $this->id;
        }
        else
        {
            $Model = "\App\\".ucfirst($this->modelName);
            $m = new $Model;
            $parent = $m->find($this->{$this->parent_key});

            if($parent)
            {
                return $parent->getRootPage($parent);
            }
            else
            {
                return false;
            }
        }

        return false;
    }

    public function getBreadcrumb(&$str = '') {
        if ($this->{$this->parent_key} > 0) {
            if ($str) {
               $str = $this->title.' / '.$str;
            } else {
                $str = $this->title;
            }
            
            $Model = "\App\\".ucfirst($this->modelName);
            $m = new $Model;
            $parent = $m->find($this->{$this->parent_key});

            if($parent)
            {
                return $parent->getBreadcrumb($str);
            }
        } else {
            if ($str) {
                return $this->title.' / '.$str;
            } else {
                return $this->title;
            }
        }
    }



	public function tableName() {
		return $this->table;
	}

}