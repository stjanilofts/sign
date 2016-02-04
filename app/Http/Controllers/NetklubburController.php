<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Page;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetklubburRequest;


class NetklubburController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'netklubbur')->first();

        $data['page'] = $page;
        $data['banner'] = $page->banner;

        return view('frontend.netklubbur')->with($data);
    }

    public function post(NetklubburRequest $request)
    {
		$details = array(
			'FirstName' => $request->get('FirstName'),
			'LastName' => $request->get('LastName'),
			'Mobile' => $request->get('Mobile'),
			'StoreCode' => $request->get('StoreCode'),
			'Birthdate' => $request->get('Birthdate'),
			'EmailAddress' => $request->get('EmailAddress'),
			'Address' => $request->get('Address'),
			'City' => $request->get('City'),
			'Zip' => $request->get('Zip'),
		);

		$res = $this->subscribe($details);

		if(!$res) {
			return response()->json(['error', 'Óþekkt villa kom upp við skráningu!'], 200);
		}

		if($res['NewMember'] == 'False')
		{
			// Ekki nýr meðlimur
		}
		if($res['NewMember'] == 'True')
		{
			// Nýr meðlimur
		}

		// Todo: vista í grunn...............
		$eclub_sub = array(
			'nafn' => $request->get('FirstName').' '.$request->get('LastName'),
			'netfang' => strtolower(trim($request->get('EmailAddress'))),
			'heimilisfang' => $request->get('Address'),
			'pnr_og_stadur' => $request->get('Zip').' '.$request->get('City'),
			'simi' => $request->get('Mobile'),
			'afmaelisdagur' => $request->get('Birthdate'),
		);
		
		return response()->json('success', 200);
	}

	public function subscribe($details)
	{
		$fields['ReturnURL'] = \Request::root().'/netklubbur?finish=true';
		$fields['SiteGUID'] = ('408573ED-7A5C-46BB-BDC8-A5E5C7904D96');
		$fields['ListID'] = ('2147484962');
		$fields['InputSource'] = ('WebsiteIceland');
		$fields['SuppressConfirmation'] = ('false');
		$fields['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
		$fields['StoreCode'] = $details['StoreCode'] ? $details['StoreCode'] : '4649';

		foreach($details as $k => $v)
		{
			$fields[$k] = trim($v);
		}

		$curl = curl_init();

		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://sbarro.fbmta.com/members/subscribe.aspx',
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request',
		    CURLOPT_POST => 1,
		    CURLOPT_VERBOSE => 1,
		    CURLOPT_HEADER => 1,
		    CURLOPT_POSTFIELDS => $fields,
		));

		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		//list($a, $b) = explode("\r\n\r\n", $resp, 2);

		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($resp, 0, $header_size);


		$headers = array();
		$arrRequests = explode("\r\n\r\n", $header);

		//$parsed = array_map(function($x) { return array_map("trim", explode(":", $x, 2)); }, array_filter(array_map("trim", explode("\n", $header))));
		for ($index = 0; $index < count($arrRequests) -1; $index++) {

	        foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
	        {
	            if ($i === 0)
	                $headers[$index]['http_code'] = $line;
	            else
	            {
	                list ($key, $value) = explode(': ', $line);
	                $headers[$index][$key] = $value;
	            }
	        }
	    }
		
		// Close request to clear up some resources
		curl_close($curl);


		if(array_key_exists(1, $headers)) {
			return $headers[1];
		} else {
			return false;
		}
	}
}
