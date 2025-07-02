<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Livewire\Component;
use function view;

class Occupation extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tourism.occupation')
            ->extends('layouts.admin')
            ->section('content');
    }
}
