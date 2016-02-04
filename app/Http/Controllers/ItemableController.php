<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Formable;
use App\Http\Controllers\Controller;

class ItemableController extends Controller
{
    public $currentModel; 
    public $modelName;
    public $breadcrumbs;

    public function __construct($formable = false)
    {
        if($formable)
        {
            $this->modelName = $formable->modelName;
            $this->currentModel = $formable;
            //dd($formable->pluralName());
        }
        else
        {
            $this->modelName = $this->model;
            $Model = "\App\\".ucfirst($this->modelName);
            $this->currentModel = new $Model;
        }
        
        $this->breadcrumbs = new \Creitive\Breadcrumbs\Breadcrumbs;
        $this->breadcrumbs->setCssClasses('uk-breadcrumb');
        $this->breadcrumbs->setDivider('');
        $this->breadcrumbs->addCrumb('Home', '/admin');
        $this->breadcrumbs->addCrumb(ucfirst($this->currentModel->pluralName()), strtolower($this->modelName));
    }

    public function index()
    {
        if($this->currentModel->disable_parent_listing) {
            $data['items'] = $this->currentModel->orderBy('order')->get();
        } else {
            $data['items'] = $this->currentModel->where($this->currentModel->parent_key, 0)->orderBy('order')->get();
        }

        $data['modelName'] = $this->currentModel->modelName();
        $data['model'] = $this->currentModel;
        $data['breadcrumbs'] = $this->breadcrumbs;



        $this->rebuildPaths();

        return view('admin.formable.table')->with($data);
    }

    public function subsIndex($id)
    {
        $data['items'] = $this->currentModel->where($this->currentModel->parent_key, $id)->orderBy('order')->get();

        $data['modelName'] = $this->currentModel->modelName();
        $data['model'] = $this->currentModel;
        $data['currentItem'] = $this->currentModel->find($id);
        $data['breadcrumbs'] = $this->breadcrumbs;

        return view('admin.formable.table')->with($data);
    }

    public function edit($id, $extra = array())
    {
        $item = $this->currentModel->find($id);
        $data['item'] = $item;

        if(!empty($extra)) {
            foreach($extra as $k => $v) {
                $data[$k] = $v;
            }
        }

        $this->breadcrumbs->addCrumb($item->title, $item->id);
        $data['breadcrumbs'] = $this->breadcrumbs;
        return view('admin.formable.form')->with($data);
    }

    public function create($id = 0, $extra = array())
    {
        $data['model'] = $this->currentModel;

        if(!empty($extra)) {
            foreach($extra as $k => $v) {
                $data[$k] = $v;
            }
        }

        $data['model']->translations = '';
        $data['model']->extras = '';

        $this->breadcrumbs->addCrumb('Nýtt', 'create');
        $data['breadcrumbs'] = $this->breadcrumbs;
        return view('admin.formable.form')->with($data);
    }

    public function store(Request $request)
    {
        $item = $this->currentModel->create($request->all());
        
        $item->translations = '';
        $item->extras = '';

        $this->defaultUpdate($request, $item);

        $item->resluggify();

        $item->save();
        
        //return redirect()->action($item->modelName().'Controller@index');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $item = $this->currentModel->find($id);

        $item->update($request->all());

        $this->defaultUpdate($request, $item);

        foreach($item->fields() as $field) {
            if($field['type'] == 'checkbox') {
                $item->update([$field['name'] => ($request->has($field['name']) ? 1 : 0)]);
            }
        }

        $item->save();

        //return redirect()->action($item->modelName().'Controller@index');
        return redirect()->back();
    }

    public function defaultUpdate(Request $request, $item)
    {
        // Uppfæra öll "translatables"...
        foreach(config('formable.locales') as $locale) {
            foreach($request->all() as $k => $v) {
                if(stringEndsWith($k, '_'.$locale)) {
                    $keyName = substr($k, 0, -3);

                    // Uppfæra líka default attribute...
                    if($locale === config('app.fallback_locale')) {
                        $item->{$keyName} = $v;
                    }

                    if(in_array($keyName, $item->translatable)) {
                        $item->translations($locale)->add($keyName, $v);
                    }
                }
            }
        }

        //dd($request->all());
        // Uppfæra öll "extras"...
        foreach(config('formable.locales') as $locale) {
            foreach($request->all() as $k => $v) {
                if(stringStartsWith($k, 'extra_'.$locale)) {
                    $keyName = substr($k, 9);
                    
                    if($item->hasExtra($keyName)) {
                        $item->extras($locale)->add($keyName, $v);
                    } 
                }
            }

            if(property_exists($this, 'fillableExtras')) {
                foreach($item->fillableExtras as $fe_key => $fe_val) {  
                    $_k = 'extra_'.$locale.'_'.$fe_key;
                    if( ! $request->has($_k)) {
                        $item->extras($locale)->add($fe_key, '');
                    }
                }
            }
        }
    }

    public function destroy($id)
    {
        $item = $this->currentModel->find($id);

        $item->delete();

        return redirect()->action($item->modelName().'Controller@index');
    }


    public function getParents($item = array())
    {
        $subIds = !empty($item) ? $item->getSubIds() : array();

        $parents[0] = ' - Rót - ';

        foreach ($this->currentModel->all() as $p) {
            // taka allt nema þennan flokk sem er verið að skoða og undirflokkana hans
            if (empty($item) || ($p->id != $item->id && !in_array($p->id, $subIds))) {
                $parents[$p->id] = $p->getBreadcrumb();
            }
        }

        asort($parents);

        return $parents;
    }




    public function makePath($item = false) {
        if($item) {
            $path = '/';
            $count = (count($item));
            $c = 1;
            
            foreach($this->treeToRoot($item) as $i) {
                $path .= $i;

                if($c >= $count) {
                    $path .= '/';
                }

                $c++;
            }

            return $path;
        }
    }

    public function treeToRoot($item = false, &$tree = array()) {
        if(! $item->parent_id) {
            $tree[] = $item->slug;

            return array_reverse($tree);
        }

        $tree[] = $item->slug;

        $parent = \App\Page::find($item->parent_id);

        return $this->treeToRoot($parent, $tree);
    }

    public function rebuildPaths() {
        foreach(\App\Page::all() as $page) {
            $page->path = $this->makePath($page);
            $page->save();
        }
    }
}
