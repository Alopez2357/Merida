<?php

namespace App\Http\Livewire\Dashboard\Maps;

use Illuminate\Http\Client\ConnectionException;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Commerce extends Component
{
    public $search='neveria';
    public $lat;
    public $lng;
    public $url;
    public $markers;
    public $radio=(5*1000);

    public function __construct()
    {
        $center=config('constants.maps.center');
        $this->INEGI=config('constants.INEGI');
        $this->lat=$center['lat'];
        $this->lng=$center['lng'];
    }

    public function mount()
    {
        
    }

    public function getSearch()
    {


    }
    public function render()
    {
        return view('livewire.dashboard.maps.commerce')
            ->extends('layouts.admin')
            ->section('content');
    }
}
