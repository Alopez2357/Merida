<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;

class Spend extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.spend')
                                    ->extends('layouts.admin')
                                    ->section('content');
    }
}
