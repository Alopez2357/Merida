<?php

namespace App\Http\Livewire\Dashboard\Economy;

use Livewire\Component;
use function view;

class Occupation extends Component
{
    public function render()
    {
        return view('livewire.dashboard.economy.occupation')
            ->extends('layouts.admin')
            ->section('content');
    }
}
