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

    /**
     * Basic JSON Tests
     */
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

    public function test_json_get_ship_positions_invalid_arguments(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships');

        $response
            ->assertStatus(400);
    }

    public function test_json_get_ship_positions_invalid_latitude(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/latStart:30.1/latEnd:150');

        $response
            ->assertStatus(400);
    }

    public function test_json_get_ship_positions_invalid_longitude(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/ships/lonStart:30.1/lonEnd:205.1');

        $response
            ->assertStatus(400);
    }

    /**
     * Basic XML Tests
     */
    public function test_xml_get_by_mmsi_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/xml',
        ])->get('/api/ships/311040700');

        $response
            ->assertStatus(200);
    }

    public function test_xml_get_by_lat_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/xml',
        ])->get('/api/ships/latStart:30.1/latEnd:35.1');

        $response
            ->assertStatus(200);
    }

    public function test_xml_get_by_lon_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/xml',
        ])->get('/api/ships/lonStart:30.1/lonEnd:35.1');

        $response
            ->assertStatus(200);
    }

    public function test_xml_get_by_time_interval_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'application/xml',
        ])->get('/api/ships/from:1372699559/to:10-9-2023');

        $response
            ->assertStatus(200);
    }

    /**
     * Basic CSV Tests
     */
    public function test_csv_get_by_mmsi_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'text/csv',
        ])->get('/api/ships/311040700');

        $response
            ->assertStatus(200);
    }

    public function test_csv_get_by_lat_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'text/csv',
        ])->get('/api/ships/latStart:30.1/latEnd:35.1');

        $response
            ->assertStatus(200);
    }

    public function test_csv_get_by_lon_range_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'text/csv',
        ])->get('/api/ships/lonStart:30.1/lonEnd:35.1');

        $response
            ->assertStatus(200);
    }

    public function test_csv_get_by_time_interval_happy_path(){
        $response = $this->withHeaders([
            'Accept' => 'text/csv',
        ])->get('/api/ships/from:1372699559/to:10-9-2023');

        $response
            ->assertStatus(200);
    }
}
