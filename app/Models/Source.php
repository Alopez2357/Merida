<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    public $fillable=['name','url'];

    public function loads()
    {
        return $this->hasMany(Load::class);
    }
}
