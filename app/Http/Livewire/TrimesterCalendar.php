<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TrimesterCalendar extends Component
{
    public  $Year, $Quarter;
    public $dates=[];
    public $headlines=[];
    public $headline='';
    public $keys=[];

    public function  mount()
    {
        $this->getQuarters();
    }

    public function getQuarters()
    {

        $year=(int)date('Y');
        $floor=$year - 5;
        while( $year >=  $floor){
            $this->dates[$floor]['name']=$floor;
            $q=1;
            while($q < 5){
                $this->dates[$floor]['quarters'][$q]=$q;
                ++$q;
            }
            $floor++;
        }

        $this->dates=array_reverse($this->dates);
    }

    public function selectQuarter($y, $q)
    {
        $this->Year=$y;
        $this->Quarter=$q;
    }

    public function render()
    {
        return view('livewire.trimester-calendar');
    }
}
