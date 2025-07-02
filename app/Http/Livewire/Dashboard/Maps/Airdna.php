<?php

namespace App\Http\Livewire\Dashboard\Maps;

use App\Models\Load;
use Livewire\Component;
use Carbon\Carbon;

class Airdna extends Component
{
    public $Year, $Month;
    public $start, $end;


    public function mount()
    {
        $date = Carbon::now();

        $this->start = $date->firstOfMonth()->format('Y-m-d H:i:s');
        $this->end = $date->endOfMonth()->format('Y-m-d H:i:s');

        $this->Year = $date->format('Y');
        $this->Month = $date->format('m');

    }

    public function search()
    {
        $load=Load::with(['data'=>function($data){
                        $data->where('date_start','>=',"{$this->start}")->groupBy('data.key');
                    }])
                    ->where('type_id','=',11)
                    ->orderBy('id','desc')
                    ->first();
        $this->locations = collect($load->data)->map(function ($el){
            return json_decode($el->attr);
        });


    }

    public function render()
    {
        $loop=Carbon::now();
        $dates=[];
        $i=14;
        while($i > 0 ){
            $y=$loop->format('Y');
            $m=$loop->format('m');
            $M=$loop->translatedFormat('M');
            $dates[$y][$m]=$M;
            $loop=$loop->subMonth();
            --$i;
        }

            return view('livewire.dashboard.maps.airdna')
            ->extends('layouts.admin')
            ->section('content');
    }
}
