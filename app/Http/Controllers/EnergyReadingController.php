<?php

namespace App\Http\Controllers;

use App\Models\EnergyReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EnergyReadingController extends Controller
{
    public function jsonImport()
    {
        $jsonContent = Storage::json('energy_dump.json');
        $energyReadings = [];

        foreach ($jsonContent as $record) {
            $recordValidator = Validator::make($record, [
                'timestamp' => [
                    'required',
                    Rule::dateTime()->format('X-m-d\\TH:i:sp'),
                ],
                'location' => 'string',
                'price_eur_mwh' => 'required|numeric:strict',
            ]);
            if ($recordValidator->fails()) {
                return response([$recordValidator->errors()->all(), $record['timestamp']]);

                continue;
            }
            array_push($energyReadings, $record);
        }

        $validatedData = $recordValidator->safe()->only(['timestamp, location, price_eur_mwh']);
    }

    public function allReadings(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date|date_format:Y-m-d\TH:i:sp|before:endDate',
            'endDate' => 'nullable|date|date_format:Y-m-d\TH:i:sp|after:startDate',
            'location' => 'nullable|in:ee,lv,fi',
        ]);

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $location = $request->input('location');
        $today = ($startDate === null | $endDate === null);

        if ($today) {
            $startDate = Carbon::today()->hour(0)->minute(0)->second(0);
            $endDate = Carbon::tomorrow()->hour(0)->minute(0)->second(0);
        }
        if ($location === null) {
            $location = 'ee';
        }

        try {
            $readings = EnergyReading::where('location', $location)->whereBetween('timestamp', [$startDate, $endDate])->get();

            return response($readings);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteReadings(Request $request)
    {
        $request->validate(['source' => 'required|in:API,UPLOAD']);

        $deleteSource = $request->input('source');
        $matchingReadings = EnergyReading::where('source', $deleteSource);
        $amountOfMatches = count($matchingReadings->get());

        if ($amountOfMatches === 0) {
            return response(status: 404)->json(['response' => 'No readings were found']);
        }

        try {
            $matchingReadings->delete();
            return response()->json(['amount' => $amountOfMatches]);
        } catch (\Exception $e) {
            return response(status: 500)->json(['response' => 'Some kind of server error']);
        }
    }

    public function syncPrices(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date|date_format:Y-m-d\TH:i:sp|before:endDate',
            'endDate' => 'nullable|date|date_format:Y-m-d\TH:i:sp|after:startDate',
            'location' => 'nullable|in:ee,lv,fi',
        ]);

        $location = $request->input('location');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $syncToday = ($startDate === null | $endDate === null);

        if ($syncToday) {
            $startDate = Carbon::today()->hour(0)->minute(0)->second(0);
            $endDate = Carbon::tomorrow()->hour(0)->minute(0)->second(0);
            $startDate = date('Y-m-d\TH:i:sp', strtotime($startDate));
            $endDate = date('Y-m-d\TH:i:sp', strtotime($endDate));
        }

        $startDate = urlencode($startDate);
        $endDate = urlencode($endDate);

        $reqUrl = 'https://dashboard.elering.ee/api/nps/price?start='.$startDate.'&end='.$endDate;

        $response = Http::get($reqUrl)[data];
    }
}
