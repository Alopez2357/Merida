<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Load extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable=[
        'name',
        'file',
        'headline',
        'user_id',
        'type_id',
        'source_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class)->withTrashed();
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }

    public function errors()
    {
        return $this->hasMany(Error::class);
    }
}
