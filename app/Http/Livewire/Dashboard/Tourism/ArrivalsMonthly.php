<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;

class ArrivalsMonthly extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.arrivals-monthly')
            ->extends('layouts.admin')
            ->section('content');
    }
}
