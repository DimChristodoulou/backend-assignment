<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ship;
use Illuminate\Support\Facades\DB;

class ShipSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(!Ship::count()){
            $this->importFromJson();
        }
    }

    private function importFromJson(){
        try {
            $shipsFile = file_get_contents(storage_path()."/app/public/ship_positions.json");
        } catch (\Throwable $th) {
            throw $th;
        }

        $shipsData = json_decode($shipsFile, true);

        foreach($shipsData as $ship){
            DB::table('ship')->insert([
                'mmsi' => $ship['mmsi'],
                'stationId' => $ship['stationId'],
                'speed' => $ship['speed'],
                'lon' => $ship['lon'],
                'lat' => $ship['lat'],
                'course' => $ship['course'],
                'heading' => $ship['heading'],
                'rot' => $ship['rot'],
                'status' => $ship['status'],
                'timestamp' => $ship['timestamp']
            ]);
        }

    }
}
