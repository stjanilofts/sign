<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class News extends Formable
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'news';

    protected $fillable = ['banner', 'hlutur','type', 'title', 'subtitle', 'content', 'slug', 'fillimage', 'parent_id', 'images', 'published_at', 'translations', 'order', 'status', 'url', 'files'];

    public $translatable = [
        'title',
        'content',
    ];

    protected function getHumanTimestampAttribute($column)
    {
        if ($this->attributes[$column])
        {
            Carbon::setLocale('is');
            setlocale(LC_TIME, 'Icelandic');
            return Carbon::parse($this->attributes[$column])->diffForHumans();
        }

        return null;
    }

    static public function published($limit = 5, $type = 'news')
    {
        $n = new \App\News;
        return $n->where('type', $type)->where('published_at', '<', Carbon::now())->orderBy('published_at', 'DESC')->orderBy('order')->limit($limit)->get();
    }

    public function carbonPublishedAt()
    {
        Carbon::setLocale('is');
        setlocale(LC_TIME, 'Icelandic');
        return new Carbon($this->published_at);
    }

    public function getHumanPublishedAtAttribute()
    {
        return $this->getHumanTimestampAttribute("published_at");
    }

    public function setPublishedAtAttribute($value)
    {
        if(!$value) {
            $this->attributes['published_at'] = Carbon::now();
        } else {
            $this->attributes['published_at'] = $value;
        }
    }

    protected $modelName = 'News';
    
    public function modelName() {
        return $this->modelName;
    }

    protected $pluralName = 'Fréttir';

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
            'title' => 'Undirtitill',
            'type' => 'text',
            'name' => 'subtitle'
        ],
        [
            'title' => 'Sett í birtingu',
            'type' => 'date',
            'name' => 'published_at'
        ],
        /*[
            'title' => 'Slug',
            'type' => 'text',
            'name' => 'slug',
            'args' => [
                'disabled' => true,
            ]
        ],*/
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
        'Birt dags.' => 'human_published_at',
    ];

    public $parent_key = 'parent_id';
}