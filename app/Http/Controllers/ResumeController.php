<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Page;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeRequest;
use Mail;

class ResumeController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'starfsumsokn')->first();

        $data['page'] = $page;
        $data['banner'] = $page->banner;

        return view('frontend.starfsumsokn')->with($data);
    }

    public function post(ResumeRequest $request)
    {
        $fyrirspurn = [
            'su_nafn' => $request->get('su_nafn'),
            'su_netfang' => $request->get('su_netfang'),
            'su_kennitala' => $request->get('su_kennitala'),
            'su_heimilisfang' => $request->get('su_heimilisfang'),
            'su_stadur_pnr' => $request->get('su_stadur_pnr'),
            'su_simi' => $request->get('su_simi'),
            'su_starf' => $request->get('su_starf') ?: 'Ekki valið',
            'su_hafidstorf' => $request->get('su_hafidstorf'),
            'su_reasonquit' => $request->get('su_reasonquit'),
            'su_medmaelendur' => $request->get('su_medmaelendur'),
            'su_bilprof' => $request->has('su_bilprof') ? 'Já' : 'Ekki svarað',
            'su_reyklaus' => $request->has('su_reyklaus') ? 'Já' : 'Ekki svarað',
            'su_hreintsakavottord' => $request->has('su_hreintsakavottord') ? 'Já' : 'Ekki svarað',
            'su_annad' => $request->get('su_annad'),
        ];

        Mail::send('emails.starfsumsokn', $fyrirspurn, function ($m) use ($fyrirspurn) {
            $m->to('kristjan@netvistun.is', 'Sbarro.is')->subject('Starfsumsókn af Sbarro.is');
        });

        return response()->json('success', 200);
    }
}
