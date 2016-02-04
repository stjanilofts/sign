<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nafn' => 'required',
            'netfang' => 'required|email',
            'simi' => 'required',
            'titill' => 'required',
            'skilabod' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'nafn.required' => 'Nafn vantar',
            'netfang.required' => 'Netfang vantar',
            'netfang.email' => 'Netfang er líklega ekki rétt skrifað',
            'simi.required' => 'Símanúmer vantar',
            'titill.required' => 'Titilinn vantar',
            'skilabod.required' => 'Skilaboðin vantar',
        ];
    }
}
