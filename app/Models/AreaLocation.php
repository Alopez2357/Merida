<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaLocation extends Model
{
    use HasFactory;

    public $table='area_location';

    public $fillable=['location_id','area_id'];
}
