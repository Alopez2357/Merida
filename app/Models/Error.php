<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Error extends Model
{
    use HasFactory,  SoftDeletes;

    public $fillable=['code','message','load_id'];

    public function loads()
    {
        return $this->belongsTo(Load::class);
    }

}
