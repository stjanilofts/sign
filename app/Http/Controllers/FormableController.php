<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormableController extends Controller
{
    private $formable = false;
    private $field;

    public function __construct(Request $request)
    {
        $this->formable = $this->getItem($request->get('model'), $request->get('id'));
        $this->field = $request->get('field') ?: false;
    }

    public function getItem($model, $id)
    {
        $class_name = "\App\\".ucfirst($model);
        $class = new $class_name;
        return $class->find($id);
    }

    public function getModel($model)
    {
        $class_name = "\App\\".ucfirst($model);
        return new $class_name;
    }

    public function reorderImages(Request $request)
    {
        if( ! $this->field) return false;

        $images = $request->get($this->field);
        
        if(!$this->formable || !$images) return false;

        $this->formable->{$this->field} = $images;

        $this->formable->save();
    }

    public function reorderFiles(Request $request)
    {
        $files = $request->get('files');
        
        if(!$this->formable || !$files) return false;

        $this->formable->files = $files;

        $this->formable->save();
    }

    public function reorder(Request $request)
    {
        $order = $request->get('order');
        $model = $request->get('model');

        if( ! ($m = $this->getModel($model))) return false;

        foreach($order as $order => $itemId)
        {
            $item = $m->find($itemId);

            if($item)
            {
                $item->order = $order + 1;
                $item->save();
            }
        }
    }

    public function images(Request $request)
    {
        if($this->field) {
            $field = $this->field;
            
            // ósamræmi á nöfnum óvart
            if($field == 'images') $field = 'img';

            return $this->formable->{$field}()->all();
        }
    }

    public function files(Request $request)
    {
        return $this->formable->file()->all();
    }

    public function toggle(Request $request)
    {
        $this->formable->status = !$this->formable->status;
        
        if($this->formable->save())
        {
            return response()->json(['success' => true, 'status' => ($this->formable->status ? '1' : '0')], 200);
        }
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|mimes:jpg,jpeg,png,gif'
        ]);

        if($this->formable) {
            $file = $request->file('photo');

            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            
            $name = rtrim(sha1_file($file).'-'.md5(time().uniqid()).'-'.str_slug($file->getClientOriginalName()), $ext).'.'.strtolower($ext);

            $file->move('uploads', $name);

            $field = $this->field;
            
            // ósamræmi á nöfnum óvart
            if($field == 'images') $field = 'img';

            $this->formable->{$field}()->add([
                'name' => $name,
                'title' => $file->getClientOriginalName(),
                'order' => (count($this->formable->{$field}()->all())),
            ]);

            return response()->json(['success' => true], 200);
        }
    }


    public function deleteImage(Request $request)
    {
        $idx = $request->get('idx');

        //dd($this->field);
        //dd($idx);

        if($this->field && $idx >= 1) {
            $field = $this->field;
            
            // ósamræmi á nöfnum óvart
            if($field == 'images') $field = 'img';
            
            $idx -= 1;

            $this->formable->{$field}()->remove($idx);

            return response()->json(['success' => true], 200);
        }
        
        return response()->json(['success' => false], 200);
    }


    public function uploadFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:pdf,msword,plain'
        ]);

        if($this->formable) {
            $file = $request->file('file');

            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            
            $name = rtrim(sha1_file($file).'-'.str_slug($file->getClientOriginalName()), $ext).'.'.strtolower($ext);

            $file->move('files', $name);

            $this->formable->file()->add([
                'name' => $name,
                'title' => $file->getClientOriginalName(),
                'order' => (count($this->formable->file()->all())),
                'mime' => $file->getClientMimeType(),
            ]);

            return response()->json(['success' => true], 200);
        }
    }
}
