<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends ItemableController
{
    public $model = 'Category';

    public function create($id = 0, $extra = array())
    {
        $extra['parents'] = array_filter($this->getParents());
        $extra['selectedParentId'] = $id;
        return parent::create($id, $extra);
    }

    public function edit($id, $extra = array())
    {
    	$category = \App\Category::find($id);
    	$extra['parents'] = array_filter($this->getParents($category));
        $extra['selectedParentId'] = $id;

    	return parent::edit($id, $extra);
    }

    public function subsIndex($id)
    {
        $data['items'] = \App\Category::where('parent_id', $id)->orderBy('order')->get();

        $data['modelName'] = $data['items']->first()->modelName();
        $data['model'] = new \App\Category;
        $data['currentItem'] = $this->currentModel->find($id);
        $data['breadcrumbs'] = $this->breadcrumbs;

        return view('admin.formable.table')->with($data);
    }

    public function prodsIndex($id)
    {
        $data['items'] = \App\Product::where('category_id', $id)->orderBy('order')->get();

        $data['modelName'] = $data['items']->first()->modelName();
        $data['model'] = new \App\Product;
        $data['currentItem'] = $this->currentModel->find($id);
        $data['breadcrumbs'] = $this->breadcrumbs;

        return view('admin.formable.table')->with($data);
    }

}