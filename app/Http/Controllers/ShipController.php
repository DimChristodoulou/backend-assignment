<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship;
use Carbon\Carbon;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\Log;

class ShipController extends Controller
{

    private function parseDateTime($input)
    {
        $carbonTimestamp = null;

        if (is_numeric($input)) {
            $carbonTimestamp = Carbon::createFromTimestamp($input);
        }
    
        // Attempt to parse as a datetime string if not a valid UNIX timestamp
        if ($carbonTimestamp === null || $carbonTimestamp->timestamp != $input) {
            $carbonDatetime = Carbon::parse($input);
    
            if ($carbonDatetime->isValid()) {
                // The input was successfully parsed as a datetime string
                return $carbonDatetime->timestamp;
            }
        }
    
        // If neither parsing method was successful, return null or handle the error as needed
        return $carbonTimestamp->timestamp;
    }


    public function getmmsi(Request $request, $mmsi)
    {
        $arrayOfMmsi = explode(',', trim($mmsi));
        $ships = Ship::whereIn('mmsi', $arrayOfMmsi)->get();
        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByLatRange(Request $request, $latStart, $latEnd){
        $ships = Ship::whereBetween('lat', [$latStart, $latEnd])->get();
        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByLonRange(Request $request, $lonStart, $lonEnd){
        $ships = Ship::whereBetween('lon', [$lonStart, $lonEnd])->get();
        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByTimeInterval(Request $request, $timeStart, $timeEnd){
        $timestampStart = $this->parseDateTime($timeStart);
        $timestampEnd = $this->parseDateTime($timeEnd);
        
        $ships = Ship::whereBetween('timestamp', [$timestampStart, $timestampEnd])->get();
        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    private function contentNegotiation(Request $request, $arrayable, $model){
        if ($request->accepts(['application/json', 'application/vnd.api+json'])) {
            return response()->json($arrayable);
        }
        else if($request->accepts(['application/xml'])){
            $result = ArrayToXml::convert(['__numeric' => $arrayable->toArray()]);
            return response()->xml($result);
        }
        else if($request->accepts(['text/csv'])){
            $filename = "output.csv";
            $csvFile = fopen($filename, 'w+');

            fputcsv($csvFile, $model->getCsvAttributes());

            foreach ($arrayable->toArray() as $item) {
                fputcsv($csvFile, $item);
            }

            fclose($csvFile);
            
            $headers = array('Content-Type' => 'text/csv');
            return response()->download($filename, 'output.csv', $headers);
        }
    }
}
