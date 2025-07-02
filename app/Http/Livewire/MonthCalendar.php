<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class MonthCalendar extends Component
{

    public $Year, $Month;
    public $mes;

    public function selectMonth($y,$m)
    {
        $this->Year = $y;
        $this->Month = $m;
        $this->start_hour = (new Carbon("{$y}-{$m}"))->format('Y-m-d H:i:s');
        $this->end_hour = (new Carbon("{$y}-{$m}"))->endOfMonth()->format('Y-m-d H:i:s');
        $this->mes=(new Carbon("{$y}-{$m}"))->translatedFormat('M');
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

        return view('livewire.month-calendar',['dates'=>$dates]);
    }
}
