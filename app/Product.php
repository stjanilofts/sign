<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Traits\ProductOptionsTrait;

use Illuminate\Database\Eloquent\Model;

class Product extends Formable
{
    use SluggableTrait, ProductOptionsTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];


    protected $table = 'products';

    protected $fillable = ['id', 'title', 'collection', 'features', 'extras', 'options', 'tech', 'sizes', 'fillimage', 'hlutur', 'price', 'vnr', 'content', 'slug', 'category_id', 'images', 'shell', 'skirt', 'translations', 'order', 'status', 'files'];

    public $translatable = [
        'title',
        'content',
        'sizes',
        'tech',
    ];


    public $fillableExtras = [
        'frontpaged' => 'Birta á forsíðu<br><small>(Skrifa númer í hvaða röð ef svo er)</small>',
        'size' => 'Stærð á boxi<br><small>(1 eða 2)</small>',
        'titill' => 'Titill í boxi<br><small>(ef annar en venjulegur titill)</small>',
    ];

    public function getFeatures()
    {
        $ret = [
            0 => [
                'file' => '',
                'text' => '',
            ],
            1 => [
                'file' => '',
                'text' => '',
            ],
            2 => [
                'file' => '',
                'text' => '',
            ],
        ];

        if(isset($this->features)) { 
            $features = explode("|", $this->features);

            foreach($features as $k => $feature) {
                $f = explode("#", $feature);

                $ret[$k]['file'] = array_key_exists(0, $f) ? $f[0] : '';
                $ret[$k]['text'] = array_key_exists(1, $f) ? $f[1] : '';
            }
        }

        return $ret;
    }

    public static function collections()
    {
        return [
            'collection-eldur-og-is' => 'Eldur og ís',
            'collection-mystic' => 'Dulúð',
            'collection-straumur' => 'Straumur',
            'collection-piece-of-iceland' => 'Piece of Iceland',
            'collection-rock.jpg' => 'Rock',
            'collection-signs-by-sign' => 'Signs by sign',
        ];
    }


    public function parent()
    {
        return $this->belongsTo(\App\Category::class, $this->parent_key, 'id');
    }

    protected $modelName = 'Product';

    public function modelName() {
        return $this->modelName;
    }

    protected $pluralName = 'Vörur';

    public function pluralName() {
        return $this->pluralName;
    }

    public function fields() {
        return $this->fields;
    }

    public function listables() {
        return $this->listables;
    }
    
    private $fields = [
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
            'title' => 'Verð',
            'type' => 'text',
            'name' => 'price'
        ],
        [
            'title' => 'Vörunúmer',
            'type' => 'text',
            'name' => 'vnr'
        ],
        [
            'title' => 'Features',
            'type' => 'text',
            'name' => 'features'
        ],
        [
            'title' => 'Efni',
            'type' => 'textarea',
            'name' => 'content',
            'args' => [
                'ckeditor' => true
            ],
        ],
        [
            'title' => 'Tæknilegar upplýsingar',
            'type' => 'textarea',
            'name' => 'tech',
            'args' => [
                'ckeditor' => false
            ],
        ],
        [
            'title' => 'Stærðir',
            'type' => 'textarea',
            'name' => 'sizes',
            'args' => [
                'ckeditor' => false
            ],
        ],
        [
            'title' => 'Slug',
            'type' => 'text',
            'name' => 'slug',
            'args' => [
                'disabled' => false,
            ]
        ],
    ];

    private $listables = [
        'Titill' => 'title',
        'ID' => 'id',
        'Slug' => 'slug',
        'Verð' => 'price',
    ];

    public $disable_parent_listing = true;

    public $parent_key = 'category_id';


    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    public function getPriceFormattedAttribute()
    {
        return $this->formatPrice($this->price);
    }

    public function formatPrice($price)
    {
        return number_format($price, 0, ',', '.').',- kr.';
    }

    public function getSiblings()
    {
        if($this->category_id > 0)
            return \App\Product::where('category_id', $this->category_id)->where('id', '!=', $this->id)->get();

        return false;
    }

    public function optionsArray($selected_options)
    {
        $opts = [];

        $options = $this->options()->get();

        if(!empty($selected_options)) {
            foreach($selected_options as $k => $v) {
                $opts[$options[$k]['text']] = $options[$k]['values'][$v]['text'];
            }
        }

        return $opts;
    }
}