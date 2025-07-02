<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InpcnacionalImport implements ToCollection,  WithStartRow
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
        $row=$collection->first();
        $headlines=$row->toArray();
        $data=[];

        foreach ($collection->skip(1) as $row)
        {
            if(!is_null($row[0])) {
                foreach ($headlines as $k => $cell) {
                    $data[trim($cell)] = trim($row[$k]);
                }
                try {
                    $star = (new Carbon($this->bothMonths($row[0])))->firstOfMonth()->format('Y-m-d H:i:s');
                    $end = (new Carbon($this->bothMonths($row[0])))->addMonth()->firstOfMonth()->format('Y-m-d H:i:s');
                    Data::updateOrCreate([
                        'type_id' => $this->load->type_id,
                        'headline' => trim('INPC Nacional'),
                        'key' => trim($row[0]),
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
