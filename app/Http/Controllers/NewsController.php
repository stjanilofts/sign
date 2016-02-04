<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewsController extends ItemableController
{
    public $model = 'News';

    public function index()
    {
        $type = \Request::get('show') ?: 'news';

        $data['items'] = $this->currentModel->where('type', $type)->orderBy('published_at', 'DESC')->orderBy('order')->get();
        
        $data['modelName'] = $this->currentModel->modelName();
        $data['model'] = $this->currentModel;
        $data['breadcrumbs'] = $this->breadcrumbs;

        return view('admin.formable.table')->with($data);
    }

    public function overview()
    {
        $data['items'] = $this->currentModel->orderBy('published_at', 'DESC')->orderBy('order')->get();
        
        $data['modelName'] = $this->currentModel->modelName();
        $data['model'] = $this->currentModel;
        $data['breadcrumbs'] = $this->breadcrumbs;

        return view('admin.formable.table')->with($data);
    }

    public function show($slug)
    {
        $paths = array_filter(explode('/', $slug));
        $count = count($paths);

        foreach($paths as $k => $slug) {
            $k++;

            if($k == $count)
            {
                $news = \App\News::where('slug', $slug)->first();

                $data['news'] = $news;
                $data['banner'] = $news->banner;

                return view('frontend.news')->with($data);
            }
        }
    }

    public function create($id = 0, $extra = array())
    {
        $extra['types'] = array(
            'news' => 'Frétt',
            /*'article' => 'Grein',
            'thought' => 'Hugleiðing',
            'seminar' => 'Námskeið'*/
        );

        return parent::create($id, $extra);
    }

    public function edit($id, $extra = array())
    {
        $news = \App\News::find($id);
        
        $extra['types'] = array(
            'news' => 'Frétt',
            /*'article' => 'Grein',
            'thought' => 'Hugleiðing',
            'seminar' => 'Námskeið'*/
        );

        $extra['images'] = ['' => ' - Veldu mynd - '];

        if($news->img()->exists()) {
            foreach($news->img()->all() as $k => $img) {
                $extra['images'][$img['name']] = $img['name'];
            }
        }

        return parent::edit($id, $extra);
    }

    










    public function show_item($slug)
    {
        $data['title'] = 'Fréttir';

        $s1 = \Request::segment(1);

        $type = 'news';

        switch($s1) {
            case('frettir'):
                $type = 'news';
                break;
            case('namskeid'):
                $type = 'seminar';
                break;
            case('greinar'):
                $type = 'article';
                break;
            case('hugleidingar'):
                $type = 'thought';
                break;
            default:
                break;
        }

        $_f = \App\Page::where('slug', 'frettir')->first();

        $crumbs[] = [
            'id' => $_f->id,
            'title' => $_f->title,
            'path' => $_f->path
        ];

        $_p = \App\News::where('slug', $slug)->first();

        $crumbs[] = [
            'id' => $_p->id,
            'title' => $_p->title,
            'path' => 'frettir/'.$_p->slug
        ];

        $data['page'] = $_p;
        $data['banner'] = $data['page']->banner;
        $data['crumbs'] = $crumbs;

        return view('frontend.page')->with($data);
    }

    public function frettir()
    {

        $_f = \App\Page::where('slug', 'frettir')->first();

        $crumbs[] = [
            'id' => $_f->id,
            'title' => $_f->title,
            'path' => $_f->path
        ];

        $data['crumbs'] = $crumbs;

        $data['title'] = 'Fréttir';
        $data['items'] = \App\News::where('status', '>', 0)->where('type', 'news')->orderBy('published_at', 'DESC')->orderBy('order')->get();
        return view('frontend.products')->with($data);
    }
}