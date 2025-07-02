<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Error;
use App\Models\Load;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class OperationalImport implements ToCollection
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
                $intervals[$m]['start']= (new Carbon($this->allMonths($month)))->format('Y-m-d H:i:s');
                $intervals[$m]['end']=(new Carbon($this->allMonths($month)))->addMonth()->format('Y-m-d H:i:s');
                $intervals[$m]['month']=$month;
        }
        $intervals=array_filter($intervals);

        foreach ($collection->skip(1) as $r=>$row) {
            foreach ($intervals as $i => $interval) {

                    try{
                        Data::updateOrCreate([
                            'type_id'=>$this->load->type_id,
                            'headline'=> trim($row[0]),
                            'key'=> trim($row[1]),
                            'sub'=> trim($interval['month']),
                            'date_start'=>trim($interval['start']),
                            'date_end'=>trim($interval['end']),
                        ], [
                            'attr'=>trim($row[$i]),
                            'load_id' => $this->load->id
                        ]);

                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        Error::create(['code'=>$e->getCode(),'message'=>$e->getMessage(),'load_id'=>$this->load->id]);
                        $Load=Load::find($this->load->id);
                        $Load->status='Error';
                        $Load->save();
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
