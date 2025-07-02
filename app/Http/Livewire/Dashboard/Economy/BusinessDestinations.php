<?php

namespace App\Http\Livewire\Dashboard\Economy;

use Livewire\Component;
use function view;

class BusinessDestinations extends Component
{
    public function render()
    {
        return view('livewire.dashboard.economy.business-destinations')
            ->extends('layouts.admin')
            ->section('content');
    }
}
