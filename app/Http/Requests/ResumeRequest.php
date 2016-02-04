<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ResumeRequest extends Request
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
            'su_nafn' => 'required',
            'su_netfang' => 'required|email',
            'su_simi' => 'required',
            'su_kennitala' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'su_nafn.required' => 'Nafn vantar',
            'su_netfang.required' => 'Netfang vantar',
            'su_netfang.email' => 'Netfant er líklega rangt skrifað',
            'su_simi.required' => 'Símanúmer vantar',
            'su_kennitala.required' => 'Kennitölu vantar',
        ];
    }
}
