<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;
use function view;

class Operational extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.operational')
            ->extends('layouts.admin')
            ->section('content');
    }
}
