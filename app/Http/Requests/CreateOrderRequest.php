<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOrderRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nafn'              => 'required',
            'netfang'           => 'email|required',
            'simi'              => 'required',
            'heimilisfang'      => 'required',
            'pnr'               => 'required',
            'stadur'            => 'required',
            'greidslumati'      => 'required',
            'afhendingarmati'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nafn.required'             => 'Nafn vantar',
            'netfang.email'             => 'Netfang er líklega rangt skrifað',
            'netfang.required'          => 'Netfang vantar',
            'simi.required'             => 'Símanúmer vantar',
            'heimilisfang.required'     => 'Heimilisfang vantar',
            'pnr.required'              => 'Póstnúmer vantar',
            'stadur.required'           => 'Vantar að velja stað',
            'greidslumati.required'     => 'Vantar að velja greiðslumáta',
            'afhendingarmati.required'  => 'Vantar að velja afhendingaráta',
        ];
    }
}
