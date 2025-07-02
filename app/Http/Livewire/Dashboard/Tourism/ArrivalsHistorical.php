<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;

class ArrivalsHistorical extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.arrivals-historical')
            ->extends('layouts.admin')
            ->section('content');
    }
}
