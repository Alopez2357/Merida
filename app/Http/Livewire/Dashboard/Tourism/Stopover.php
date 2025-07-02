<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;
use function view;

class Stopover extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.stopover')
            ->extends('layouts.admin')
            ->section('content');
    }
}
