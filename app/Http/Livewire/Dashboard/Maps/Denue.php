<?php

namespace App\Http\Livewire\Dashboard\Maps;

use Livewire\Component;

class Denue extends Component
{
    public function render()
    {
        return view('livewire.dashboard.maps.denue')
            ->extends('layouts.admin')
            ->section('content');
    }
}
