<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Error;
use App\Models\Load;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class OdImport implements ToCollection
{
    use Translate;

    public object $load;

    public function __construct($load)
    {
        $this->load=(object)$load;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $months=array_filter($collection->first()->toArray());
        $intervals=[];
        foreach ($months as $m=>$month){
            try {
                $intervals[$m]['start'] = (new Carbon($this->allMonths($month)))->format('Y-m-d H:i:s');
                $intervals[$m]['end'] = (new Carbon($this->allMonths($month)))->addMonth()->format('Y-m-d H:i:s');
            }catch (\Exception $e){
                Log::error($e->getMessage());
                Error::create(['code'=>$e->getCode(),'message'=>$e->getMessage(),'load_id'=>$this->load->id]);
                $Load=Load::find($this->load->id);
                $Load->status='Error';
                $Load->save();
            }
        }

        $headlines=collect(array_filter($collection->skip(1)->first()->toArray()));
        $headlines=array_unique($headlines->skip(3)->toArray());

        $index=0;
        foreach ($collection->skip(2) as $r=>$row){

            foreach ($intervals as $i => $interval) {

                if(!Str::contains($row[0],'Total')  &&  !Str::contains($row[1],'Total')){
                    try {
                        $type= $row[0] ?? $type;
                        $register[$index]['headline']= $type;
                        $title= $row[1] ?? $title;
                        $register[$index]['key'] = $title;
                        $sub=trim(preg_replace('/\[[A-Z]{3}\]/','',$row[2]));
                        $register[$index]['sub']=$sub;
                        $register[$index]['date_start']=$interval['start'];
                        $register[$index]['date_end']=$interval['end'];
                        $data=[];
                        $c=$i;
                        foreach ($headlines as $headline) {
                            $data[$i][$headline]=$row[$c] ?? 0;
                            $data[$i]['province']=$sub;
                            $data[$i]['type']=$type;
                            if(preg_match('/\[[A-Z]{3}\]/',$row[2],$m)){
                                $data[$i]['code'] = preg_replace('/\[|\]/','',$m[0]);
                            }
                            ++$c;
                        }

                        $register[$index]['attr'] =json_encode($data[$i]);

                        Data::updateOrCreate([
                            'type_id'=>$this->load->type_id,
                            'headline' =>trim($register[$index]['headline']),
                            'key' => trim($register[$index]['key']),
                            'sub'=>trim($register[$index]['sub']),
                            'date_start' => trim($register[$index]['date_start']),
                            'date_end' => trim($register[$index]['date_end'])
                        ],[
                            'attr' => trim($register[$index]['attr']),
                            'load_id' => $this->load->id
                        ]);
                    }catch (\Exception $e){
                        Log::error($e->getMessage());
                        Error::create(['code'=>$e->getCode(),'message'=>$e->getMessage(),'load_id'=>$this->load->id]);
                        $Load=Load::find($this->load->id);
                        $Load->status='Error';
                        $Load->save();
                    }

                    ++$index;
                }
            }
        }
    }

    public function startRow():int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
