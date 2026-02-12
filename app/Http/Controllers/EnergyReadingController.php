<?php

namespace App\Http\Controllers;

use App\Services\EnergyReadingService;
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
        $start = $request->input('start');
        $end = $request->input('end');
        $location = $request->input('location');
    }

    public function syncPrices(Request $request)
    {
        $request->validate([
            'location' => 'nullable|in:ee,lv,fi',
        ]);

        $location = $request->input('location');
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $syncToday = true;

        if ($startDate !== null && $endDate !== null) {
            $startDate = date('X-m-d\\TH:i:sp', strtotime($startDate));
            $endDate = date('X-m-d\\TH:i:sp', strtotime($endDate));

            $isStartFormatValid = Carbon::canBeCreatedFromFormat($startDate, 'X-m-d\\TH:i:sp');
            $isEndFormatValid = Carbon::canBeCreatedFromFormat($endDate, 'Y-m-d\\TH:i:sp');

            if ($isStartFormatValid === false || $isEndFormatValid === false) {
                return response([$startDate, $isStartFormatValid, $endDate, $isEndFormatValid]);
            }

            $syncToday = true;
        }

        if ($syncToday) {
            $startDate = Carbon::now()->hour(0)->min(0)->second(0);
            $startDate = Carbon::tomorrow()->hour(0)->min(0)->second(0);
        }

        $reqUrl = 'https://dashboard.elering.ee/api/nps/price?start='.$startDate.'&end='.$endDate;

        $response = Http::get($reqUrl);
    }
}
