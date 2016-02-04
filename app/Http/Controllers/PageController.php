<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends ItemableController
{
    public $model = 'Page';

    public function show($slug)
    {
		$paths = array_filter(explode('/', $slug));
		$count = count($paths);



		foreach($paths as $k => $slug) {
			$k++;

            $_p = \App\Page::where('slug', $slug)->first();

            if(! $_p) {
                $pages = \App\Page::all();

                /*foreach($pages as $page) {
                    if($page->translations(lang())->has('slug')) {
                        if($page->translation('slug') == $slug) {
                            $_p = $page;
                        }
                    }
                }*/
            }

            if(! $_p) abort(404);

            /*echo $_p->translations(lang())->get('title').'<br>';
            
            $crumbs[] = [
                'id' => $_p->id,
                'title' => $_p->translations(lang())->get('title'),
                'path' => $_p->path
            ];*/

			if($k == $count)
			{
				$page = $_p;

				$data['page'] = $page;
                $data['seo'] = $page;
                if($page->hasSubs()) {
                    $data['subs'] = $page->getSubs();
                }
                

                $data['banner'] = $page['banner'] ?: false;
                
                //$data['crumbs'] = $crumbs;

                if($page->blade_view) {
                    return view('frontend.'.$page->blade_view)->with($data);
                }

				return view('frontend.page')->with($data);
			}
		}
    }

    public function update(Request $request, $id)
    {
        $item = $this->currentModel->find($id);

        $item->update($request->all());

        $this->defaultUpdate($request, $item);

        $item->accordion = $request->get('accordion') ? 1 : 0;
        $item->topmenu = $request->get('topmenu') ? 1 : 0;

        if(trim($item->slug)=='') $item->slug = str_slug($item->title);
        
        $item->save();

        if($item->parent_id) {
            return redirect()->to('/admin/page/'.$item->parent_id.'/subs');
        }

        return redirect()->action($item->modelName().'Controller@index');
    }

    public function store(Request $request)
    {
        $item = $this->currentModel->create($request->all());
        
        $item->translations = '';

        $this->defaultUpdate($request, $item);

        $item->resluggify();

        $item->save();

        $item->path = $this->makePath($item);

        $item->save();

        if($item->parent_id) {
            return redirect()->to('/admin/page/'.$item->parent_id.'/subs');
        }

        return redirect()->action($item->modelName().'Controller@index');
    }


    public function create($id = 0, $extra = array())
    {
        $extra['parents'] = array_filter($this->getParents());

        $extra['selectedParentId'] = $id ?: \Request::get('parent_id');

        return parent::create($id, $extra);
    }

    public function edit($id, $extra = array())
    {
        $page = \App\Page::find($id);

        $extra['parents'] = array_filter($this->getParents($page));
        $extra['selectedParentId'] = $id;

        $extra['images'] = [];

        $extra['images'] = ['' => ' - Veldu mynd - '];

        if($page->img()->exists()) {
            foreach($page->img()->all() as $k => $img) {
                $extra['images'][$img['name']] = $img['name'];
            }
        }

    	return parent::edit($id, $extra);
    }





    public function deleteSubs($page)
    {
        $subs = $page->getAllSubs();

        if( ! $subs->isEmpty()) {
            foreach($subs as $sub) {
                $this->deleteSubs($sub);
            }
        }

        $page->delete();
    }



    public function destroy($id)
    {
        $page = \App\Page::find($id);

        if(! is_null($page)) {
            if($page->hasSubs()) {
                $this->deleteSubs($page);
            } else {
                $page->delete();
            }
        }

        return redirect()->action('PageController@index');
    }
    
}