<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;

class Category extends Formable
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'categories';

    public $parent_key = 'parent_id';

    protected $fillable = ['id', 'title', 'subtitle', 'content', 'extras', 'slug', 'hlutur', 'fillimage', 'shell', 'skirt', 'parent_id', 'images', 'translations', 'order', 'status', 'files'];

    public function scopeNorsk($query)
    {
        return $query->where('parent_id', 2);
    }

    public $fillableExtras = [
        'frontpaged' => 'Birta á forsíðu<br><small>(Skrifa númer í hvaða röð ef svo er)</small>',
        'size' => 'Stærð á boxi<br><small>(1 eða 2)</small>',
        'titill' => 'Titill í boxi<br><small>(ef annar en venjulegur titill)</small>',
    ];
    
    public function scopeIcelandic($query)
    {
        return $query->where('parent_id', 1);
    }

    public $translatable = [
        'title',
        'content',
    ];

    protected $modelName = 'Category';

    public function modelName() {
        return $this->modelName;
    }

    protected $pluralName = 'Flokkar';

    public function pluralName() {
        return $this->pluralName;
    }

    public function fields() {
        return $this->fields;
    }

    public function listables() {
        return $this->listables;
    }
    
    protected $fields = [
        [
            'title' => 'Titill',
            'type' => 'text',
            'name' => 'title'
        ],
        [
            'title' => 'Láta mynd filla út box í yfirliti (kroppast)',
            'type' => 'checkbox',
            'name' => 'fillimage'
        ],
        [
            'title' => 'Undirtitill',
            'type' => 'text',
            'name' => 'subtitle'
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
                'ckeditor' => true
            ],
        ],
    ];

    protected $listables = [
        'Titill' => 'title',
        'Slug' => 'slug',
    ];


    public function products()
    {
        return $this->hasMany(\App\Product::class);
    }


}