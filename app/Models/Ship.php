<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;
    protected $table = 'ship';
    protected $fillable = [
        'mmsi', 
        'status', 
        'station', 
        'speed', 
        'lon', 
        'lat', 
        'course', 
        'heading', 
        'rot', 
        'timestamp'
    ];

    private $csvAttributes = [
        'id',
        'mmsi', 
        'status', 
        'station', 
        'speed', 
        'lon', 
        'lat', 
        'course', 
        'heading', 
        'rot', 
        'timestamp',
        'created_at',
        'updated_at'
    ];

    public function getCsvAttributes(){
        return $this->csvAttributes;
    }
}
