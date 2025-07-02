<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovementsController extends BaseController
{

    public function operational(Request $request)
    {

        $validated = validator($request->all(),['type' => 'required']);

        if ($validated->fails())
            return response($validated->errors(),400);

        $type=$request->type;

        $date = Carbon::now();
        $start = (new Carbon($date))->subYear()->format('Y-m-d H:i:s');
        $end = $date->format('Y-m-d H:i:s');

       /*$result=DB::select(DB::raw("SELECT D.id,
                                    D.type_id,
                                    D.headline,
                                    D.key,
                                    DATE_FORMAT(D.date_start,'%Y %b') AS date,
                                    YEAR(D.date_start) AS year,
                                    month(D.date_start) AS month,
                                    D.load_id,
                                    FORMAT((D.attr * 100),2) AS percent
                                    FROM
                                    data AS D
                                     WHERE D.type_id=11
                                     AND D.headline='$type'
                                     AND D.key NOT IN ('Total','Llegada','Salida')
                                     AND D.date_start >= DATE_FORMAT(date_sub(CURDATE(),INTERVAL 12 MONTH),'%Y-%m-%d %H:%i:%s')
                                     ORDER BY  year, month, D.key"));*/

       $result=DB::select(DB::raw("SELECT D.id,
                                    D.type_id,
                                    D.headline,
                                    D.key,
                                    DATE_FORMAT(D.date_start,'%Y %b') AS date,
                                    YEAR(D.date_start) AS year,
                                    month(D.date_start) AS month,
                                    D.load_id,
                                    FORMAT((D.attr * 100),2) AS percent
                                    FROM
                                    data AS D
                                    WHERE D.type_id=11
                                    AND D.headline='$type'
                                    AND D.key NOT IN ('Total','Llegada','Salida')
                                    AND D.date_start >= DATE_FORMAT(date_sub(CURDATE(),INTERVAL 12 MONTH),'%Y-%m-%d %H:%i:%s')
                                    GROUP BY D.headline, D.key, date, year, month
                                    ORDER BY year, month, D.key, D.load_id DESC "));

       $labels=[];
       $data_labels=[];
       $datas=[];
       foreach($result as $r=>$row){
                $labels[$r]=$row->date;
                $data_labels[$r]=$row->key;
                $datas[$row->key][$r]=$row->percent;

            }

      $data['labels']=array_unique($labels);



      $data['data_labels']=array_unique($data_labels);
      $data['datas']=$datas;

      $sum=0;
        foreach ($datas as $datum){
            $sum+=end($datum);
        }

        $data['sum']=$sum;


        if(!is_null($result)){
            $this->response=[
                'body'=>$data,
                'interval'=>[$start,$end],
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

    public function arrives(Request $request)
    {

        $validated = validator($request->all(),['national' => 'required|boolean',
                                                'start'=>'required|date',
                                                'end'=>'required|date|after_or_equal:start']);

        if ($validated->fails())
            return response($validated->errors(),400);

        $national=$request->national;
        $start=$request->start;
        $end=$request->end;

        //$type_id=Type::where('name','REGEXP','Origen Destino')->firstOrFail()->id;
        $arrStart = explode('-',$start);
        $arrEnd   = explode('-',$end);

        $dtStart = $arrStart[0] . '-' . str_pad($arrStart[1], 2, '0', STR_PAD_LEFT). '-' . str_pad($arrStart[2], 2, '0', STR_PAD_LEFT) . ' 00:00:00';
        $dtEnd   = $arrEnd[0] . '-' . str_pad($arrEnd[1], 2, '0', STR_PAD_LEFT)  . '-' . str_pad($arrEnd[2], 2, '0', STR_PAD_LEFT). ' 23:59:59';

        $sql="SELECT
                IF(S.pasajeros < 1000,'Otros',S.sub)  AS province,
                 sum(S.pasajeros) AS passengers
                FROM 
                 (
                SELECT 
                 D.sub, 
                 SUM(JSON_EXTRACT(D.attr,\"$.PASAJEROS\")) AS pasajeros
                FROM data AS D
                WHERE D.type_id IN (SELECT id from types WHERE name regexp 'Origen Destino')
                AND D.headline='Llegada'
                                             ";
            $sql.= ($national) ? "AND D.key='México'" : "AND D.key <> 'México'";
            $sql.="
                AND (D.date_start >= '$dtStart' AND D.date_start <= '$dtEnd')
                                             GROUP BY D.sub
                                            ORDER BY D.id DESC) AS S
                                            WHERE S.pasajeros > 0
                                            GROUP BY province
                                            ORDER BY passengers DESC";

       try {
            $result = DB::select(DB::raw($sql));
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }


        $start=(new Carbon($start))->format('d F Y ');
        $end=(new Carbon($end))->format('d F Y');

            $this->response=[
                'body'=>$result,
                'national'=>$national,
                'interval'=>['start'=>$start,'end'=>$end],
                'message' => 'Successfully'];
            $this->code=200;

        return response($this->response,$this->code);
    }

    public function departures(Request $request)
    {

        $validated = validator($request->all(),['national' => 'required|boolean',
                                'start'=>'required|date',
                                'end'=>'required|date|after_or_equal:start']);

        if ($validated->fails())
            return response($validated->errors(),400);

        $national=$request->national;
        $start=$request->start;
        $end=$request->end;

        $arrStart = explode('-',$start);
        $arrEnd   = explode('-',$end);

        $dtStart = $arrStart[0] . '-' . str_pad($arrStart[1], 2, '0', STR_PAD_LEFT). '-' . str_pad($arrStart[2], 2, '0', STR_PAD_LEFT) . ' 00:00:00';
        $dtEnd   = $arrEnd[0] . '-' . str_pad($arrEnd[1], 2, '0', STR_PAD_LEFT)  . '-' . str_pad($arrEnd[2], 2, '0', STR_PAD_LEFT). ' 23:59:59';

        $type_id=Type::where('name','REGEXP','Origen Destino')->firstOrFail()->id;

        $sql="SELECT 
                IF(S.pasajeros < 1000,'Otros',S.sub)  AS province,
                 sum(S.pasajeros) AS passengers
                FROM 
                 (
                SELECT 
                 D.sub, 
                 SUM(JSON_EXTRACT(D.attr,\"$.PASAJEROS\")) AS pasajeros
                FROM data AS D
                WHERE D.type_id IN (SELECT id from types WHERE name regexp 'Origen Destino')
                AND D.headline='Salida'
                                             ";
        $sql.=($national)?"AND D.key='México'":"AND D.key<>'México'";
        $sql.="
               AND (D.date_start >= '$dtStart' AND D.date_start <= '$dtEnd')  
                 GROUP BY D.sub
                ORDER BY D.id DESC) AS S
                WHERE S.pasajeros > 0
                GROUP BY province
                ORDER BY passengers DESC";

        $result=DB::select(DB::raw($sql));


        $start=(new Carbon($start))->format('d F Y ');
        $end=(new Carbon($end))->format('d F Y');

        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'national'=>$national,
                'interval'=>['start'=>$start,'end'=>$end],
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

}
