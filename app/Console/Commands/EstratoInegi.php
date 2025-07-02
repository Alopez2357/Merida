<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Data;
use App\Models\Load;
use App\Models\Source;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Location\Coordinate;
use Location\Polygon;
use Illuminate\Support\Facades\Cache;

class EstratoInegi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inegi:estrato';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all companies Merida form INEGI';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public $Inegi, $date;


    public function handle()
    {
        $this->Inegi=config('constants.INEGI');
        $now=Carbon::now();
        $this->date=$now->format('Y-m-d H:i:s');

        $this->estrato();

        $this->polygon();

        return Command::SUCCESS;
    }

    public function polygon()
    {
        $Polygons=Area::select('id','name')
            ->with(['coordinates'=>function($coordinates){
                $coordinates->select('id','lat','lng','area_id');
            }])
            ->where('visible','=',1)
            ->get();

        $result=DB::select( DB::raw("SELECT D.id AS location_id, D.`key`,
                                        AL.id AS relation,
                                        AL.area_id,
                                        D.attr
                                        FROM loads AS L
                                        INNER JOIN data AS D
                                        ON L.id=D.load_id
                                        LEFT JOIN area_location AS AL
                                        ON D.id=AL.location_id
                                        WHERE L.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'INEGI Estratos')
                                        AND AL.area_id IS null"));


        foreach ($result as $datum){
            $decode=json_decode($datum->attr,true);
            $checkpoint = new Coordinate($decode['Latitud'],$decode['Longitud']);
            foreach ($Polygons as $polygon) {
                $geofence = new Polygon();
                foreach ($polygon->coordinates as $coordinate) {
                    $geofence->addPoint(new Coordinate($coordinate->lat, $coordinate->lng));
                }

                if ($geofence->contains($checkpoint)) {
                    $location=Data::find($datum->location_id);
                    $polygon->arealocation()->attach($location);
                }
            }

        }
    }

    public function estrato()
    {

        $increment=5000;
        $init=0;
        $end=$init+$increment;
        for($i=0; $i < 15; ++$i) {

            try {
                $url = $this->Inegi['api'] . "consulta/BuscarAreaAct/{$this->Inegi['province']}/{$this->Inegi['townhall']}/0/0/0/0/0/0/0/0/$init/$end/0/{$this->Inegi['key']}";

                $name_file = "BuscarAreaAct INEGI " . $this->date;

                $Load = Load::create([
                    'name' => $name_file,
                    'headline' => '',
                    'file' => $url,
                    'type_id' => Type::where('name', 'REGEXP', 'INEGI Estratos')->first()->id,
                    'source_id' => Source::where('name', 'REGEXP', 'INEGI')->first()->id,
                    'user_id' => User::where('name', 'REGEXP', 'User')->first()->id]);

                $response = Http::get($url);

                $locations = $response->json();

                foreach ($locations as $loc) {

                    try {
                        $Fecha_Alta = str_replace(' ', '-', $loc['Fecha_Alta']) . "-01";
                        $date_start = (new Carbon($Fecha_Alta));
                        $date_end = (new Carbon($Fecha_Alta))->endOfMonth();

                        Data::updateOrCreate([
                            'type_id' => $Load->type_id,
                            'headline' => $loc['CLEE'],
                            'key' => $loc['Id'],
                            'sub' => $loc['Nombre'],
                            'date_start' => $date_start->format("Y-m-d H:i:s"),
                            'date_end' => $date_end->format("Y-m-d H:i:s")
                        ], [
                            'attr' => json_encode($loc),
                            'load_id' => $Load->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Fill: '.$e->getMessage());
                    }

                }
            } catch (\Exception $e) {
                Log::error('Load: '.$e->getMessage());
            }

            $init+=$increment;
            $end+=$increment;

        }
    }
}
