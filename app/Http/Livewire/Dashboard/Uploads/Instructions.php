<?php

namespace App\Http\Livewire\Dashboard\Uploads;

use Livewire\Component;

class Instructions extends Component
{
    public function render()
    {
        return view('livewire.dashboard.upload.instructions')
            ->extends('layouts.admin')
            ->section('content');
    }
}
