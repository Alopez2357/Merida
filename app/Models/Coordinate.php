<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    use HasFactory;

    public $fillable=['lat','lng','area_id'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
