<?php

namespace App\Http\Livewire\Dashboard\Economy;

use Livewire\Component;

class EmploymentCity extends Component
{
    public function render()
    {
        return view('livewire.dashboard.economy.employment-city')
            ->extends('layouts.admin')
            ->section('content');
    }
}
