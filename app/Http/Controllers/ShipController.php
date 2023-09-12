<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ship;
use Carbon\Carbon;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    public function getPositions(Request $request){
        if($request->has('mmsi')){
            return $this->getmmsi($request, $request->input('mmsi'));
        }
        elseif ($request->has('latStart') && $request->has('latEnd')) {
            return $this->getByLatRange($request, $request->input('latStart'), $request->input('latEnd'));
        }
        elseif ($request->has('lonStart') && $request->has('lonEnd')) {
            return $this->getByLonRange($request, $request->input('lonStart'), $request->input('lonEnd'));
        }
        elseif ($request->has('from') && $request->has('to')) {
            return $this->getByTimeInterval($request, $request->input('from'), $request->input('to'));
        }
        else{
            throw new HttpResponseException(
                new Response(
                    [
                        'error' => "Invalid arguments"
                    ], 
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
    }


    public function getmmsi(Request $request, $mmsi)
    {
        $arrayOfMmsi = explode(',', trim($mmsi));

        if($this->doesRequestSupportPagination($request)){
            $ships = Ship::whereIn('mmsi', $arrayOfMmsi)->simplePaginate($request->input('resultsperpage'))->withQueryString();
        }
        else{
            $ships = Ship::whereIn('mmsi', $arrayOfMmsi)->get();
        }
        
        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByLatRange(Request $request, $latStart, $latEnd){

        if(!$this->validateLatitude($latStart) || !$this->validateLatitude($latEnd)){
            throw new HttpResponseException(
                new Response(
                    [
                        'error' => "Latitude should be between -90 and 90"
                    ], 
                    Response::HTTP_BAD_REQUEST
                )
            );
        }

        if($this->doesRequestSupportPagination($request)){
            $ships = Ship::whereBetween('lat', [$latStart, $latEnd])->simplePaginate($request->input('resultsperpage'))->withQueryString();
        }
        else{
            $ships = Ship::whereBetween('lat', [$latStart, $latEnd])->get();
        }

        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByLonRange(Request $request, $lonStart, $lonEnd){
        if(!$this->validateLongitude($lonStart) || !$this->validateLongitude($lonEnd)){
            throw new HttpResponseException(
                new Response(
                    [
                        'error' => "Longitude should be between -180 and 180"
                    ], 
                    Response::HTTP_BAD_REQUEST
                )
            );
        }

        if($this->doesRequestSupportPagination($request)){
            $ships = Ship::whereBetween('lon', [$lonStart, $lonEnd])->simplePaginate($request->input('resultsperpage'))->withQueryString();
        }
        else{
            $ships = Ship::whereBetween('lon', [$lonStart, $lonEnd])->get();
        }

        $model = new Ship();

        return $this->contentNegotiation($request, $ships, $model);
    }

    public function getByTimeInterval(Request $request, $timeStart, $timeEnd){
        $timestampStart = $this->parseDateTime($timeStart);
        $timestampEnd = $this->parseDateTime($timeEnd);

        if($this->doesRequestSupportPagination($request)){
            $ships = Ship::whereBetween('timestamp', [$timestampStart, $timestampEnd])->simplePaginate($request->input('resultsperpage'))->withQueryString();
        }
        else{
            $ships = Ship::whereBetween('timestamp', [$timestampStart, $timestampEnd])->get();
        }

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
            $csvFile = fopen($filename, 'w');

            fputcsv($csvFile, $model->getCsvAttributes());

            foreach ($arrayable->toArray() as $item) {
                fputcsv($csvFile, $item);
            }

            fclose($csvFile);
            
            $headers = array('Content-Type' => 'text/csv');
            return response()->download($filename, 'output.csv', $headers);
        }
    }

    private function doesRequestSupportPagination(Request $request): bool{
        return (
            $request->has('resultsperpage') 
            && !$request->accepts(['text/csv'])
            && is_numeric($request->input('resultsperpage'))
        );
    }

    private function validateLatitude($latitude){
        return ($latitude < 90 && $latitude > -90);
    }

    private function validateLongitude($longitude){
        return ($longitude < 180 && $longitude > -180);
    }
}
