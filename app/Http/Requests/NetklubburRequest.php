<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class NetklubburRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'FirstName' => 'required',
            'LastName' => 'required',
            'StoreCode' => 'required',
            'Birthday' => ['required', 'regex:/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/'],
            'EmailAddress' => 'required|email',
            'Address' => 'required',
            'City' => 'required',
            'Zip' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'FirstName.required' => 'Nafn vantar',
            'LastName.required' => 'Eftirnafn vantar',
            'StoreCode.required' => 'Vantar að velja uppáhalds stað',
            'Birthday.required' => 'Afmælisdag vantar',
            'Birthday.regex' => 'Afmælisdagurinn þarf vera ÁR/MÁNUÐUR/DAGSETNING eða sem dæmi: 1984/10/23',
            'EmailAddress.required' => 'Netfang vantar',
            'EmailAddress.email' => 'Netfang er rangt skrifað',
            'Address.required' => 'Heimilisfang vantar',
            'City.required' => 'Stað vantar',
            'Zip.required' => 'Póstnúmer vantar',
        ];
    }
}
