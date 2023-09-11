<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShipControllerTest extends TestCase
{
    private $itemStructure = [
        '*' => [
            'id',
            'mmsi',
            'stationId',
            'speed',
            'lon',
            'lat',
            'course',
            'heading',
            'rot',
            'status',
            'timestamp',
            'created_at',
            'updated_at'
        ]
    ];

    public function test_json_get_by_mmsi_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/311040700');

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->itemStructure);
    }

    public function test_json_get_by_lat_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/latStart:30.1/latEnd:35.1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->itemStructure);
    }

    public function test_json_get_by_lon_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/lonStart:30.1/lonEnd:35.1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->itemStructure);
    }

    public function test_json_get_by_time_interval_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/from:1372699559/to:10-9-2023');

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->itemStructure);
    }
}
