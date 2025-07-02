<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StopoverImport implements ToCollection, WithStartRow
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
        $headlines=$collection->first()->toArray();
        $data=[];

        foreach ($collection->skip(1) as $row){

            if(!Str::contains(strtolower($row[0]),'total') &&
                !Str::contains(strtolower($row[1]),'total')) {
                foreach ($headlines as $k => $cell) {
                    $data[trim($cell)] = trim($row[$k]);
                }

                try {
                    $date = "{$row[1]} {$row[0]}";

                    $star = (new Carbon($this->bothMonths($date)))->format('Y-m-d H:i:s');
                    $end = (new Carbon($this->bothMonths($date)))->addMonth()->format('Y-m-d H:i:s');

                    Data::updateOrCreate([
                                        'type_id'=>$this->load->type_id,
                                        'headline' => $row[0],
                                        'key' => $row[1],
                                        'sub'=>$date,
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
