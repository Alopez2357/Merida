<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InflationImport implements ToCollection, WithStartRow
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

        foreach ($collection->skip(1) as $row){
            $data=[];
            foreach ($headlines as $k => $cell) {
                $data[trim($cell)] = trim($row[$k]);
            }

            try {

            $date=str_replace('-','',$row[0]);
            $star = (new Carbon($this->bothMonths($date)))->firstOfMonth()->format('Y-m-d H:i:s');
            $end = (new Carbon($this->bothMonths($date)))->addMonth()->firstOfMonth()->format('Y-m-d H:i:s');


                Data::updateOrCreate([
                    'type_id' => $this->load->type_id,
                    'headline' => 'Inflación',
                    'key' => trim($row[0]),
                    'sub'=>trim($headlines[1]),
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

    public function startRow():int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
