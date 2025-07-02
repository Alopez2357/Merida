<?php

namespace App\Http\Livewire\Dashboard\Economy;

use Livewire\Component;
use function view;

class Inpcmerida extends Component
{
    public function render()
    {
        return view('livewire.dashboard.economy.inpcmerida')
            ->extends('layouts.admin')
            ->section('content');
    }
}
