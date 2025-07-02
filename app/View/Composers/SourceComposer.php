<?php

namespace App\View\Composers;

use App\Models\Source;
use Illuminate\View\View;

class SourceComposer
{
    public function compose(View $view)
    {
        $sources=Source::where('active','=',1)
                        ->where('visible','=',1)
                        ->orderBy('name','ASC')->get();

        

        $view->with(['sources'=>$sources]);
    }
}