<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashController extends BaseController
{
    public function accommodation()
    {
        $sql="
        SELECT
       replace(replace(replace(replace(replace(JSON_EXTRACT(D.attr,\"$.listing_type\"),'\"',''),'Hotel room','Habitacíon Hotel'),'Private room','Habitación Privada'),'Shared room','Habitación Compartida'),'Entire home\\\/apt','Casa Completa') AS listing_type,  
        COUNT(D.id) AS count
        FROM data AS D
        WHERE D.type_id=(SELECT id FROM types WHERE NAME REGEXP 'airdna') 
        AND D.date_start = DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'airdna') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s')
        GROUP BY listing_type";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);

    }

    public function inflation()
    {
        $sql="
        SELECT 
        D.id, 
        D.key,
        D.sub, 
        format(JSON_EXTRACT(D.attr,\"$.602155\"),2) as percent
            FROM data AS D 
            WHERE D.type_id IN (SELECT id FROM types WHERE name REGEXP 'inpc mérida' )
            AND date_start >= DATE_FORMAT(DATE_SUB(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE()),'-01'), INTERVAL 1 YEAR),'%Y-%m-%d %H:%i:%s')
            ORDER BY D.date_start ASC; ";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

    public function airdna(Request $request)
    {

        $sql="SELECT 
            D.`id`,
            D.`date_start`,
            D.`date_end`,
            D.`attr`
            FROM loads AS L 
            INNER JOIN data AS D
            ON L.`id`=D.`load_id`
            WHERE L.`type_id` IN (SELECT id  FROM types WHERE NAME REGEXP 'airdna')
            AND D.date_start = DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'airdna') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s')
            AND JSON_EXTRACT(D.attr,\"$.active\") = true
            AND JSON_EXTRACT(D.attr,\"$.scraped_during_month\") = true";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){

            $locations = collect($result)->map(function ($el) {
                return [
                    'id'=>$el->id,
                    'json'=>json_decode($el->attr),
                    'date_start'=>$el->date_start,
                    'date_end'=>$el->date_end,
                ];
            });

            $this->response=[
                'body'=>$locations,
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);

    }

    public function employment()
    {

        $sql="
        SELECT
        D.`headline`,
        D.`date_start`,
        D.`date_end`,
        D.`attr` 
        FROM data AS D 
        WHERE D.`type_id` IN (SELECT id  FROM types WHERE NAME REGEXP 'empleo')
        AND D.date_start = DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'empleo') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s')
        AND D.`headline` REGEXP 'Población total'
        ORDER BY D.load_id DESC
        ";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){

            $employment = collect($result)->map(function ($el) {
                return [
                    'headline'=>$el->headline,
                    'json'=>json_decode($el->attr),
                    'date_start'=>$el->date_start,
                    'date_end'=>$el->date_end,
                ];
            });

            $this->response=[
                'body'=>$employment->first(),
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);

    }


    public function employment_rate()
    {

        $sql="SELECT 
        D.`headline`,
        YEAR(D.`date_start`) AS year,
        QUARTER(D.`date_start`) AS quarter,
        D.`sub`,
        JSON_EXTRACT(JSON_EXTRACT(REPLACE(D.`attr`,'\\\u00f3','ó'),\"$.Tasas calculadas contra la población en edad de trabajar\"),\"$.Tasa de participación\") AS json,
        D.`date_start`,
        D.`date_end`
        FROM data as D
        WHERE D.`type_id` IN (SELECT id  FROM types WHERE NAME REGEXP 'empleo')
        AND D.date_start >= DATE_SUB(DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'empleo') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s'), INTERVAL  1 YEAR)
        AND D.`headline` REGEXP 'Tasa'
        AND D.`attr` REGEXP 'Tasa de participa'
        ORDER BY year DESC, quarter ASC
        LIMIT 0,4";

        $result=DB::select(DB::raw($sql));

        $employment_rate = collect($result)->map(function ($el) {
            return [
                'headline'=>$el->headline,
                'year'=>$el->year,
                'quarter'=>$el->quarter,
                'sub'=>$el->sub,
                'json'=>json_decode($el->json),
                'date_start'=>$el->date_start,
                'date_end'=>$el->date_end,
            ];
        });

        if(!is_null($result)){

            $this->response=[
                'body'=>$employment_rate,
                'message' => 'Successfully'];
            $this->code=200;
        }

        return response($this->response,$this->code);

    }


    public function unemployment_rate()
    {

        $sql="SELECT 
        D.`headline`,
        YEAR(D.`date_start`) AS year,
        QUARTER(D.`date_start`) AS quarter,
        D.`sub`,
        JSON_EXTRACT(JSON_EXTRACT(REPLACE(D.`attr`,'\\\u00f3','ó'),\"$.Tasas calculadas contra la población económicamente activa\"),\"$.Tasa de desocupación\") AS json,
        D.`date_start`,
        D.`date_end`
        FROM data as D
        WHERE D.`type_id` IN (SELECT id  FROM types WHERE NAME REGEXP 'empleo')
        AND D.date_start >= DATE_SUB(DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'empleo') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s'), INTERVAL  1 YEAR)
        AND D.`headline` REGEXP 'Tasa'
        AND D.`attr` REGEXP 'Tasa de desocupaci'
        ORDER BY year DESC, quarter ASC
        LIMIT 0,4";

        $result=DB::select(DB::raw($sql));
        $unemployment_rate = collect($result)->map(function ($el) {
            return [
                'headline'=>$el->headline,
                'year'=>$el->year,
                'quarter'=>$el->quarter,
                'sub'=>$el->sub,
                'json'=>json_decode($el->json),
                'date_start'=>$el->date_start,
                'date_end'=>$el->date_end,
            ];
        });


        if(!is_null($result)){

            $this->response=[
                'body'=>$unemployment_rate,
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);

    }

}
