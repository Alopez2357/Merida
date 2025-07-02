<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Carbon\Carbon;
use Livewire\Component;
use function view;

class ArrivalsInternational extends Component
{
    public $start, $end;

    public function mount()
    {
        $date=Carbon::now();
        $date=$date->subYear();
        $this->start=Carbon::create($date->year,1,1)->format('Y-m-d');
        $this->end=(new Carbon($this->start))->addYear()->format('Y-m-d');

    }
    public function render()
    {
        return view('livewire.dashboard.tourism.arrivals-international')
            ->extends('layouts.admin')
            ->section('content');
    }
}
