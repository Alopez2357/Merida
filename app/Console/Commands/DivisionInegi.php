<?php

namespace App\Console\Commands;

use App\Models\Data;
use App\Models\Load;
use App\Models\Source;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DivisionInegi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inegi:division';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene la división politica de México';

    public $date;
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $now=Carbon::now();
        $this->date=$now->format('Y');
        $this->date=(new Carbon("{$this->date}-01-01"))->format('Y-m-d H:i:s');

        for($i=1; $i <= 32; $i++){
            try {
                $url = "https://gaia.inegi.org.mx/wscatgeo/geo/mgee/".str_pad($i, 2, "0", STR_PAD_LEFT);

                $name_file = "División politica INEGI " . $this->date;

                $Load = Load::create([
                    'name' => $name_file,
                    'headline' => '',
                    'file' => $url,
                    'type_id' => Type::where('name', 'REGEXP', 'División Política')->first()->id,
                    'source_id' => Source::where('name', 'REGEXP', 'INEGI')->first()->id,
                    'user_id' => User::where('name', 'REGEXP', 'User')->first()->id]);

                    $response = Http::get($url);

                   $result= $response->json();


                }catch (\Exception $e){
                    Log::error('Load: '.$e->getMessage());
                }

                try {

                    $coords=[];
                    $coordinates=$result['features'][0]['geometry']['coordinates'][0][0];
                    foreach ($coordinates as $c=>$coordinate){
                        $coords[$c]=['lat'=>$coordinate[1],'lng'=>$coordinate[0]];
                    }



                    $json=['properties'=> $result['features'][0]['properties'],
                            'coordinates'=>$coords];


                    Data::updateOrCreate([
                        'type_id' => $Load->type_id,
                        'headline' =>  $result['features'][0]['properties']['nom_agee'],
                        'key' =>  $result['features'][0]['properties']['cve_agee'],
                        'sub' =>  $result['features'][0]['properties']['nom_abrev'],
                        'date_start' => $this->date,
                        'date_end' => $this->date,
                    ], [
                        'attr' => json_encode($json),
                        'load_id' => $Load->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Fill: '.$e->getMessage());
                }

        }

        return Command::SUCCESS;
    }
}
