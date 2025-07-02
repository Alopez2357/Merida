<?php
namespace App\Imports;

use Carbon\Carbon;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\Data;
use App\Models\AreaLocation;

class AirdnaImport implements ToCollection, WithStartRow, WithBatchInserts, WithChunkReading
{
  public object $load;
  public array $headings;

    public function __construct($load, $headings)
    {
        $this->headings=$headings[0][0];
        $this->load=(object)$load;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
      $data = [];

      foreach($collection->skip(1) as $row)
      {
        foreach ($this->headings as $k=>$cell)
        {
          $data[trim($cell)] = trim($row[$k]);
        }

        try
        {
          $split = explode('/',$row[4]);

          $start = Carbon::create(intval($split[2]),intval($split[1]),intval($split[0]))->format('Y-m-d H:i:s');

          $cDateSub = intval($split[0]) . '/' . intval($split[1]) . '/' . intval($split[2]);

          $end = (new Carbon($start))->addMonth()->format('Y-m-d H:i:s');

          $item = Data::updateOrCreate([
              'type_id' => $this->load->type_id,
              'headline' => '',
              'key' => trim($row[0]),
              'sub' => trim($cDateSub),
              'date_start' => $start,
              'date_end' => $end,
          ],[
              'attr' => json_encode($data),
              'load_id' => $this->load->id
          ]);

          $cAux = strtolower($data["neighborhood"]);

          $aux = [];

          $aux['location_id'] = $item->id;
          $aux['area_id']     = 1;

          if(str_icontains('merida',$cAux))
          {
            if(str_icontains('centro',$cAux) or str_icontains('center', $cAux))
            {
              $aux['area_id'] = 2;
            }
            else
            {
              if(str_icontains('nort',$cAux) or str_icontains('nord',$cAux))
              {
                if(str_icontains('este',$cAux))
                {
                  $aux['area_id'] = 3;
                }

                if(str_icontains('oeste',$cAux))
                {
                  $aux['area_id'] = 6;
                }
              }

              if(str_icontains('sur',$cAux) or str_icontains('sud',$cAux))
              {
                if(str_icontains('este',$cAux))
                {
                  $aux['area_id'] = 4;
                }

                if(str_icontains('oeste',$cAux))
                {
                  $aux['area_id'] = 5;
                }
              }
            }
          }

          AreaLocation::where('location_id', $item->id)->delete();

          AreaLocation::insert($aux);
        }
        catch(\exception $ex)
        {
          Log::error('Filling: ' . $ex->getMessage());
        }

      }
    }

    public function startRow(): int
    {
      return 1;
    }

    public function batchSize(): int
    {
      return 500;
    }

    public function chunkSize(): int
    {
      return 500;
    }

}
