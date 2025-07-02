<?php

namespace App\Imports;

use App\Models\Data;
use App\Traits\Translate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ConevalImport implements ToCollection, WithStartRow
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
        $flip=[];
        foreach($collection as $r=>$row){
          foreach ($row as $c=>$cell){
              $flip[$c][$r]=trim($cell);
          }
        }

       foreach ($flip as $row){
           try {
               $start= (new Carbon("$row[1] January"))->firstOfMonth()->format('Y-m-d H:i:s');
               $end= (new Carbon($start))->addYear()->format('Y-m-d H:i:s');

               Data::updateOrCreate([
                   'type_id'=>$this->load->type_id,
                   'headline'=>trim($row[0]),
                   'key'=>trim($row[2]),
                   'sub'=>trim($row[1]),
                   'date_start'=>$start,
                   'date_end'=>$end],
                   [
                       'attr'=>trim($row[3]),
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
