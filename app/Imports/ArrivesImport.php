<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Load;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ArrivesImport implements ToCollection, WithStartRow
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

            if(!Str::contains(strtolower($row[0]),'total') &&
                !Str::contains(strtolower($row[1]),'total')) {
                try{
                    foreach ($headlines as $k => $cell) {
                        $data[trim($cell)] = trim($row[$k]);
                  }

                    /*$date = "{$row[1]} {$row[0]}";  */
                    $cAux = $row[1] . ' ' . $row[0];
                    
                    $date = $row[0];

                    $star = (new Carbon($this->allMonths($date)))->format('Y-m-d H:i:s');
                    $end = (new Carbon($this->allMonths($date)))->addMonth()->format('Y-m-d H:i:s');

                    Data::updateOrCreate([
                                            'type_id'=>$this->load->type_id,
                                            'headline' => trim($row[0]),
                                            'key' => trim($row[1]),
                                            'sub'=>trim($cAux),
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
