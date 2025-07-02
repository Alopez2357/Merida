<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    public $fillable=['type_id','headline','key','sub','attr','load_id','date_start','date_end'];

    public function areas()
    {
        return $this->belongsToMany(Area::class,'area_location','area_id','location_id');
    }
}
