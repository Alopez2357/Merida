<?php

namespace App\View\Composers;

use App\Models\Area;
use App\Models\Source;
use Illuminate\View\View;

class ConstantsComposer
{
    public function compose(View $view)
    {
        $apikey=config('app.api_key');

        $areas=Area::where('visible','=',1)
                        ->orderBy('name','ASC')
                        ->get();

        $view->with(['apikey'=>$apikey,'poligons'=>$areas]);
    }
}