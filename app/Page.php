<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Page extends Formable
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'pages';

    protected $fillable = ['banner', 'blade_view', 'topmenu', 'accordion', 'path', 'hlutur', 'title', 'subtitle', 'content', 'slug', 'parent_id', 'images', 'translations', 'order', 'status', 'url', 'files'];

    public $translatable = [
        'title',
        'slug',
        'content',
    ];

    protected $modelName = 'Page';
    
    public function modelName() {
        return $this->modelName;
    }

    protected $pluralName = 'Síður';

    public function pluralName() {
        return $this->pluralName;
    }

    public function fields() {
        return $this->fields;
    }

    public function listables() {
        return $this->listables;
    }



    public function getAccordionSubs() {
        return \App\Page::where('parent_id', $this->id)->where('accordion', 1)->where('status', 1)->orderBy('order')->get();
    }



    




    public function rebuildPath($item = false, &$path = array()) {
        $path[] = $item->slug;

        if(! $item->parent_id) {
            return $path;
        }

        $parent = \App\Page::find($item->parent_id);
        
        return $this->rebuildPath($parent, $path);
    }

    public function parentPath() {
        if(!$this->parent_id) {
            return false;
        }

        $parent = \App\Page::find($this->parent_id)->first();

        return $parent->path;
    }

    public function updatePath() {
        $this->path = $this->parentPath() ? $this->parentPath().'/'.$this->slug : $this->slug;
        $this->save();
    }

    private $fields = [
        [
            'title' => 'Titill',
            'type' => 'text',
            'name' => 'title'
        ],
        /*[
            'title' => 'Birta í "accordion" í efni foreldris',
            'type' => 'checkbox',
            'name' => 'accordion'
        ],*/
        [
            'title' => 'Linkur',
            'type' => 'text',
            'name' => 'url'
        ],
        [
            'title' => 'Blade view (ef annað en venjulega)',
            'type' => 'text',
            'name' => 'blade_view'
        ],
        [
            'title' => 'Sýna í topp menu (ef þetta er í rótinni)',
            'type' => 'checkbox',
            'name' => 'topmenu'
        ],
        [
            'title' => 'Slug',
            'type' => 'text',
            'name' => 'slug',
            'args' => [
                'disabled' => false,
            ]
        ],
        [
            'title' => 'Efni',
            'type' => 'textarea',
            'name' => 'content',
            'args' => [
                'ckeditor' => true,
            ],
        ],
    ];

    private $listables = [
        'Titill' => 'title',
        'Slug' => 'slug',
    ];

    public $parent_key = 'parent_id';
}