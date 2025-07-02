<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Load;
use App\Models\Source;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InegiController extends Controller
{
    public $Inegi, $date;

    public function __construct()
    {
        $this->Inegi=config('constants.INEGI');
        $now=Carbon::now();
        $this->date=$now->format('Y-m-d H:i:s');
    }

    public function estrato()
    {

        try {
            $url = $this->Inegi['api'] . "consulta/BuscarAreaAct/{$this->Inegi['province']}/{$this->Inegi['townhall']}/0/0/0/0/0/0/0/0/1/1000000/0/{$this->Inegi['key']}";

            $name_file = "BuscarAreaAct INEGI " . $this->date;

            $Load = Load::create([
                'name' => $name_file,
                'headline' => '',
                'file' => $url,
                'type_id' => Type::where('name', 'REGEXP', 'Estrato')->first()->id,
                'source_id' => Source::where('name', 'REGEXP', 'INEGI')->first()->id,
                'user_id' => User::where('name', 'REGEXP', 'User')->first()->id]);

            $response = Http::get($url);

            $locations = $response->json();

            foreach ($locations as $loc) {

                try {
                    $Fecha_Alta = str_replace(' ', '-', $loc['Fecha_Alta']) . "-01";
                    $start = (new Carbon($Fecha_Alta));
                    $end = (new Carbon($Fecha_Alta))->endOfMonth();

                    Data::updateOrCreate([
                        'type_id' => $Load->type_id,
                        'headline' => $loc['CLEE'],
                        'key' => $loc['Id'],
                        'sub' => $loc['Nombre'],
                        'date_start' => $start->format("Y-m-d H:i:s"),
                        'date_end' => $end->format("Y-m-d H:i:s")
                    ], [
                        'attr' => json_encode($loc),
                        'load_id' => $Load->id
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

            }
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }

    }
}
