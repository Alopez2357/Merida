<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Carbon\Carbon;
use Livewire\Component;
use function view;

class ArrivalsNational extends Component
{

    public $start, $end;

    public function mount()
    {
        $date=Carbon::now();
        $date=$date->subMonth();
        $this->start=(new Carbon($date))->firstOfMonth()->format('Y-m-d');
        $this->end=(new Carbon($this->start))->addMonth()->format('Y-m-d');

    }
    public function render()
    {
        return view('livewire.dashboard.tourism.arrivals-national')
            ->extends('layouts.admin')
            ->section('content');
    }
}
