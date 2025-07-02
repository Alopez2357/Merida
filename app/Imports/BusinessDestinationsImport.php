<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class BusinessDestinationsImport implements ToCollection
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
        $headlines=array_filter($collection->first()->toArray());
        $data=[];

        foreach ($collection->skip(1) as $row){
            try{
                foreach ($headlines as $k => $cell) {
                    $data[trim(strtolower(str_replace(' ','_',$cell)))] = trim($row[$k]);
                }


                $star = (new Carbon($this->allMonths($row[6])))->format('Y-m-d H:i:s');
                $end = (new Carbon($this->allMonths($row[6])))->addMonth()->format('Y-m-d H:i:s');

                Data::updateOrCreate([
                    'type_id'=>$this->load->type_id,
                    'headline' => trim($row[1]),
                    'key' => trim($row[2]),
                    'sub'=>trim($row[3]),
                    'date_start' => $star,
                    'date_end' => $end
                ],[
                    'attr' => json_encode($data),
                    'load_id' => $this->load->id
                ]);
            }catch (\Exception $e){

                Log::error($e->getMessage());
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
