<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
