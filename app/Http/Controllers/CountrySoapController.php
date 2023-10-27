<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Models\RequestLog;
use Illuminate\Http\Request;

class CountrySoapController extends Controller
{
    // Second Step
    public function get(Request $request)
    {
        $countries = Country::get();

        RequestLog::create([
            'request_payload'   => $request->getContent(),
            'callback_url'      => $request->get('callback_url')
        ]);

        return response()->json(['success' => true, 'data' => CountryResource::collection($countries)]);
    }
}
