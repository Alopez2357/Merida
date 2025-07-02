<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\Load;
use App\Models\Type;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourismController extends BaseController
{
    public $date;
    public $start;
    public $end;

    public function arrivals(Request $request)
    {
        $validated = validator($request->all(),['start'=>'required|date',
                                                'end'=>'required|date|after_or_equal:start']);

        if ($validated->fails())
            return response($validated->errors(),400);


        $start=(new Carbon($request->start))->format('Y-m-d H:i:s');
        $end=(new Carbon($request->end))->format('Y-m-d H:i:s');
        $mes=(new Carbon($request->start))->format('M');
        $year=(new Carbon($request->start))->format('Y');
        if ($mes == 'Jul' && $year == '2023'||$mes == 'Jun' && $year == '2023'){
            $result = DB::table('data')->where('headline', $year)
            ->where('key',$mes)
            ->where('type_id','6')
            ->value('attr');
            //var_dump($result);
            // $result = collect(DB::select(DB::raw($sql)))->first();
            // var_dump($result);
            // die();

            if(!is_null($result)){

                // var_dump($result);

                $this->response=[

                    // 'sql'=>$sql,

                    'body'=>json_decode($result),

                    'start'=>$request->start,

                    'end'=>$request->end,

                    'message' => 'Successfully'];

                $this->code=200;

            }
        }else {
            $sql="SELECT D.attr FROM data AS D
                    WHERE D.`type_id` IN (SELECT id FROM types WHERE NAME REGEXP 'Arribos al Aeropuerto')
                    AND (D.date_start BETWEEN DATE_FORMAT('{$start}','%Y-%m-%d %H:%i:%s')
                            AND DATE_FORMAT('{$end}','%Y-%m-%d %H:%i:%s'))
                    AND JSON_EXTRACT(D.`attr`,\"$.Total\")<> 'null'  AND JSON_EXTRACT(D.`attr`,\"$.Total\")<> ''
                    AND JSON_EXTRACT(D.`attr`,\"$.Domestic\")<> 'null' AND JSON_EXTRACT(D.`attr`,\"$.Domestic\")<> ''
                    AND JSON_EXTRACT(D.`attr`,\"$.International\")<> 'null' AND JSON_EXTRACT(D.`attr`,\"$.International\")<> ''
                    GROUP BY D.`sub`";


            $result=collect(DB::select(DB::raw($sql)))->first();
            if(!is_null($result)){
                $this->response=[
                    'sql'=>$sql,
                    'body'=>json_decode($result->attr),
                    'start'=>$request->start,
                    'end'=>$request->end,
                    'message' => 'Successfully'];
                $this->code=200;
            }
        }


        return response($this->response,$this->code);
    }

    public function arrivalsOld(Request $request)
    {
        $validated = validator($request->all(),['start'=>'required|date',
                                                'end'=>'required|date|after_or_equal:start']);

        if ($validated->fails())
            return response($validated->errors(),400);


        $start=(new Carbon($request->start))->format('Y-m-d H:i:s');
        $end=(new Carbon($request->end))->format('Y-m-d H:i:s');

        $sql="SELECT D.attr FROM data AS D
                WHERE D.`type_id` IN (SELECT id FROM types WHERE NAME REGEXP 'Arribos al Aeropuerto')
                AND (D.date_start BETWEEN DATE_FORMAT('{$start}','%Y-%m-%d %H:%i:%s') 
                        AND DATE_FORMAT('{$end}','%Y-%m-%d %H:%i:%s'))
                AND JSON_EXTRACT(D.`attr`,\"$.Total\")<> 'null'  AND JSON_EXTRACT(D.`attr`,\"$.Total\")<> ''                  
                AND JSON_EXTRACT(D.`attr`,\"$.Domestic\")<> 'null' AND JSON_EXTRACT(D.`attr`,\"$.Domestic\")<> ''
                AND JSON_EXTRACT(D.`attr`,\"$.International\")<> 'null' AND JSON_EXTRACT(D.`attr`,\"$.International\")<> ''
                GROUP BY D.`sub`";

        $result=collect(DB::select(DB::raw($sql)))->first();


            if(!is_null($result)){
                $this->response=[
                    'sql'=>$sql,
                    'body'=>json_decode($result->attr),
                    'start'=>$request->start,
                    'end'=>$request->end,
                    'message' => 'Successfully'];
                $this->code=200;
            }
        return response($this->response,$this->code);
    }

    public function occupation(Request $request)
    {
        $validated = validator($request->all(),[
                                            'since_year'=>'required|numeric']);
        if ($validated->fails())
            return response($validated->errors(),400);

        $since_year = $request->since_year;

        $sql = "SELECT 
                year(D.date_start) AS int_year,
                month(D.date_start) AS int_month,
                DATE_FORMAT(D.date_start, \"%b\") AS str_month,
                replace(JSON_EXTRACT(D.attr,\"$.month\"),'\"','') as mes,
                CAST(JSON_EXTRACT(D.attr,\"$.percent\") as DOUBLE) as percent
                FROM loads AS L 
                INNER JOIN data AS D 
                ON L.id=D.load_id
                WHERE L.type_id IN (SELECT id FROM types WHERE name REGEXP 'Ocupación Hotelera')
                AND D.date_start >= DATE_FORMAT(CONCAT('{$since_year}','-01-01'),'%Y-%m-%d %H:%i:%s')
                ORDER BY int_year, int_month;";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){
            $collect=[];
            $years=[];
            foreach ($result as $row){
                $years[(int)$row->int_year][]=['month'=>$row->mes,
                                                'percent'=>$row->percent,
                ];
            }
            foreach ($years as $y=>$year){
                $collect[]=['year'=>$y,
                    'data'=>$year];
            }

            $response=[
                'body'=>['data'=>$collect,
                        'year'=>"{$since_year}"],
                'message' => 'Successfully'];
        }
        return response($response,200);

    }

    public function spend(Request $request)
    {
        $validated = validator($request->all(),[
            'since_year'=>'required|numeric']);
        if ($validated->fails())
            return response($validated->errors(),400);

        $since_year = $request->since_year;

        $sql = "SELECT 
            year(D.date_start) AS int_year,
            replace(replace(replace(replace(QUARTER(D.date_start),'1','I'),'2','II'),'3','III'),'4','IV') AS trimester,
            date(D.date_start) AS start,
            date(D.date_end) AS end,
            replace(format(JSON_EXTRACT(D.attr,\"$.spend_day\"),2),',','') as spend
            FROM loads AS L 
            INNER JOIN data AS D 
            ON L.id=D.load_id
            WHERE L.type_id=7
            AND D.date_start >= DATE_FORMAT(CONCAT('{$since_year}','-01-01'),'%Y-%m-%d %H:%i:%s')
            ORDER BY int_year, trimester;";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){
            $collect=[];
            $years=[];
            foreach ($result as $row){
                $years[(int)$row->int_year][]=['quarter'=>$row->trimester,
                    'spend'=>$row->spend,
                ];
            }
            foreach ($years as $y=>$year){
                $collect[]=['year'=>$y,
                    'data'=>$year];
            }

            $response=[
                'body'=>['data'=>$collect,
                    'year'=>"{$since_year}"],
                'message' => 'Successfully'];
        }
        return response($response,200);
    }

    public function stopover(Request $request)
    {
        $validated = validator($request->all(),[
                                            'year' => 'required|numeric']);
        if ($validated->fails())
            return response($validated->errors(),400);
        $year = $request->year;
        $query="SELECT 
                YEAR(D.date_start) AS int_year,
                MONTH(D.date_start) AS int_month,
                DATE_FORMAT(D.date_start, '%b') AS str_month,
                CAST(JSON_EXTRACT(D.attr,'$.Extranjeros') AS DOUBLE) AS international,
                CAST(JSON_EXTRACT(D.attr,'$.Nacionales') AS DOUBLE) AS domestic,
                CAST(JSON_EXTRACT(D.attr,'$.Total') AS DOUBLE) AS total
                FROM loads AS L 
                INNER JOIN data AS D 
                ON L.id=D.load_id
                WHERE  L.type_id=9 
                AND  D.date_start >= DATE_FORMAT(DATE_SUB(CONCAT(YEAR(CURDATE()),'-01-01'),INTERVAL 2 YEAR),'%Y-%m-%d %H:%i:%s')
                ORDER BY int_year, int_month;";
        $result = DB::select(DB::raw($query));
        $years=[];
        foreach ($result as $row){
            $years[(int)$row->int_year][]=['month'=>$row->str_month,
                                        'total'=>$row->total,
                                        'international'=>$row->international,
                                        'domestic'=>$row->domestic
                                        ];
        }
        $collect=[];
        foreach ($years as $y=>$year){
            $collect[]=['year'=>$y,
                        'data'=>$year];
        }
        if(!is_null($result)){
            $this->response=[
                'data'=>$collect,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

    public function arrivalsMonthly()
    {
        $sql="SELECT 
            year(D.date_start) AS int_year,
            month(D.date_start) AS int_month,
            DATE_FORMAT(D.date_start, \"%b\") AS str_month,
            CAST(REPLACE(JSON_EXTRACT(D.attr,\"$.International\"),'\"','') AS INT) AS international,
            CAST(REPLACE(JSON_EXTRACT(D.attr,\"$.Domestic\"),'\"','')AS INT) AS domestic,
            CAST(REPLACE(JSON_EXTRACT(D.attr,\"$.Total\"),'\"','')AS INT) AS total
            FROM loads AS L 
            INNER JOIN data AS D 
            ON L.id=D.load_id
            WHERE L.type_id=(SELECT id FROM types WHERE name REGEXP 'Arribos al Aeropuerto')
            AND D.date_start >= DATE_FORMAT(date_sub(CONCAT(YEAR(CURDATE()),'-01-01'),interval 1 year),'%Y-%m-%d %H:%i:%s')
            AND JSON_EXTRACT(D.attr,\"$.International\") <> 'null'
            AND JSON_EXTRACT(D.attr,\"$.Domestic\") <> 'null'
            AND JSON_EXTRACT(D.attr,\"$.Total\") <> 'null'
            AND JSON_EXTRACT(D.attr,\"$.International\") <> ''
            AND JSON_EXTRACT(D.attr,\"$.Domestic\") <> ''
            AND JSON_EXTRACT(D.attr,\"$.Total\") <> ''
            GROUP BY int_year, int_month
            ORDER BY int_year, int_month;";
        $result = DB::select(DB::raw($sql));

        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

    public function arrivalsHistorical()
    {
        $sql="SELECT 
            year(D.date_start) AS int_year,
            sum(JSON_EXTRACT(D.attr,\"$.International\")) as international,
            sum(JSON_EXTRACT(D.attr,\"$.Domestic\")) as domestic,
            sum(JSON_EXTRACT(D.attr,\"$.Total\")) as total
            FROM loads AS L 
            INNER JOIN data AS D 
            ON L.id=D.load_id
            WHERE L.type_id=6
            AND D.date_start >= DATE_FORMAT(date_sub(CONCAT(YEAR(CURDATE()),'-01-01'),interval 10 year),'%Y-%m-%d %H:%i:%s')
            GROUP BY int_year
            ORDER BY int_year;";
        $result = DB::select(DB::raw($sql));

        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);

    }


}
