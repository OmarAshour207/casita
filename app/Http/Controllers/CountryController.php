<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Models\CountryLog;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::with('logs')->get();
        $countries = CountryResource::collection($countries);
        return response()->json(['data' => $countries, 'success' => true]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar'   => 'required|string',
            'name_en'   => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'data' => $validator->errors()->getMessages()]);

        $country = Country::create($validator->validated());

        // send Webhook to Callback URL if exist
        // Third Step
        $this->sendWebhook('create', ['new_values' => json_encode($country->toArray())]);

        return response()->json(['success' => true, 'data' => new CountryResource($country)]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_ar'   => 'required|string',
            'name_en'   => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['success' => false, 'data' => $validator->errors()->getMessages()]);

        $country = Country::whereId($id)->first();

        if (!$country)
            return response()->json(['success' => false, 'data' => []]);

        $oldValues = $country->toArray();
        $country->update($validator->validated());
        $newValues = $country->toArray();

        // Log the country old and new data
        CountryLog::create([
            'country_id'        => $country['id'],
            'old_values'        => json_encode($oldValues),
            'new_values'        => json_encode($newValues)
        ]);

        // send Webhook to Callback URL if exist
        // Third Step
        $this->sendWebhook('update', ['old_values' => $oldValues, 'new_values' => $newValues]);

        return response()->json(['success' => true, 'data' => new CountryResource($country)]);
    }

    public function sendWebhook($type, $data = array())
    {
        $webhookURL = '';
        // get webhook URL
        $lastRequestLog = RequestLog::whereNotNull('callback_url')->latest()->first();

        if ($lastRequestLog)
            $webhookURL = $lastRequestLog->callback_url;

        if (!empty($webhookURL)) {
            $fields['new_values'] = $data['new_values'];

            if ($type == 'update')
                $fields['old_values'] = $data['old_values'];

            $dataString = json_encode(['type' => $type, 'fields' => $fields]);

            $headers = array (
                'Content-Type: application/json'
            );

            // send the data to URL
            try {
                $ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, $webhookURL );
                curl_setopt ( $ch, CURLOPT_POST, true );
                curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

                curl_setopt ( $ch, CURLOPT_POSTFIELDS, $dataString );

                curl_exec ( $ch );
                curl_close ( $ch );
            } catch (\Exception $e) {
                Log::error("can't send " . $e->getMessage());
            }
        }
    }

    public function delete($id)
    {
        $country = Country::whereId($id)->first();

        if (!$country)
            return response()->json(['success' => false, 'data' => []]);

        $country->delete();

        return response()->json(['success' => true, 'data' => []]);
    }

}
