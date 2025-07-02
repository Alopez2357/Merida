<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public $fillable=['name','visible'];

    public function arealocation()
    {
        return $this->belongsToMany(Data::class,'area_location','area_id','location_id');
    }

    public function coordinates()
    {
        return $this->hasMany(Coordinate::class);
    }

}
