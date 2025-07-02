<?php

namespace App\Http\Livewire\Dashboard\Tourism;

use Carbon\Carbon;
use Livewire\Component;

class Arrivals extends Component
{

    public $start, $end;

    public function mount()
    {
        $date=Carbon::now();

        $this->start=$date->firstOfMonth()->format('Y-m-d H:i:s');
        $this->end=$date->endOfMonth()->format('Y-m-d H:i:s');

        $this->Year=$date->format('Y');
        $this->Month=$date->format('m');

        $this->setDates();

    }

    public function setDates()
    {
        $this->start_hour=(new Carbon($this->start))->format('Y-m-d H:i:s');
        $this->end_hour=(new Carbon($this->end))->format('Y-m-d H:i:s');
    }




    public function render()
    {

        return view('livewire.dashboard.tourism.arrivals')
            ->extends('layouts.admin')
            ->section('content');
    }
}
