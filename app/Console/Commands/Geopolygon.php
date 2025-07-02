<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\AreaLocation;
use App\Models\Data;
use App\Models\Load;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Location\Coordinate;
use Location\Polygon;

class Geopolygon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:polygon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'find polygon to markers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
                                        WHERE L.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'Airdna')
                                        AND AL.area_id IS null"));


            foreach ($result as $datum){
                $decode=json_decode($datum->attr,true);
                $checkpoint = new Coordinate($decode['latitude'],$decode['longitude']);
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





        return Command::SUCCESS;
    }
}
