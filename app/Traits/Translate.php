<?php

namespace App\Traits;

use function Livewire\str;

trait Translate
{

    public $sort_eng=['Jan ','Feb ','Mar ','Apr ','May ','Jun ','Jul ','Aug ','Sep ','Oct ','Nov ','Dec '];

    public $sort_months=['Ene ','Feb ','Mar ','Abr ','May ','Jun ','Jul ','Ago ','Sep ','Oct ','Nov ','Dic '];

    public $full_months=['Enero ','Febrero ','Marzo ','Abril ','Mayo ','Junio ','Julio ','Agosto ','Septiembre ','Octubre ','Noviembre ','Diciembre '];

    public $months=['January ','February ','March ','April ','May ','June ','July ','August ','September ','October ','November ','December '];


    public $trimesters=['1Trimestre ','2Trimestre ','3Trimestre ','4Trimestre '];

    public $tri_months=['January ','April ','July ','October '];

    public function inverse($date)
    {
        return str_replace($this->months, $this->full_months,$date);
    }

    public function sMonths($date)
    {
        return str_replace($this->sort_months,$this->months,$date);
    }


    public function fMonths($date)
    {
        return str_replace($this->full_months,$this->months,$date);
    }

    public function bothMonths($date)
    {
        return $this->sMonths($this->fMonths($date));
    }

    public function allMonths($date)
    {
        return str_replace($this->sort_eng,$this->months,$this->sMonths($this->fMonths($date)));
    }

    public function Trimesters($date)
    {
        return str_replace($this->trimesters,$this->tri_months,$date);
    }
}