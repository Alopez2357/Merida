<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OccupationImport implements ToCollection, WithStartRow
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

            if(!(strpos(strtolower($row[0]), 'total' ) !== false) &&
                !(strpos(strtolower($row[1]), 'total' ) !== false) &&
                !is_null($row[2]))
            {
                foreach ($headlines as $k => $cell) {
                    $data[trim($cell)] = trim($row[$k]);
                }

                try {
                $date = "{$row[1]} {$row[0]}";
                $star = (new Carbon($this->bothMonths($date)))->format('Y-m-d H:i:s');
                $end = (new Carbon($this->bothMonths($date)))->addMonth()->format('Y-m-d H:i:s');

                    Data::updateOrCreate([
                            'type_id'=>$this->load->type_id,
                            'headline' => trim($row[0]),
                            'key' => trim($row[1]),
                            'date_start' => $star,
                            'date_end' => $end],
                        [
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