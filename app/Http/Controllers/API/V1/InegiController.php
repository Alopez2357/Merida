<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class InegiController extends BaseController
{
    protected $sql="";

    public function companies(Request $request)
    {


        $areas='';

        $validated=validator($request->all(),['areas'=>'sometimes|array']);

        if($request->areas) $areas = implode(',', $request->areas);

        if($validated->fails())
            return response($validated->errors(),400);


        $this->sql="SELECT 
            D.id as location_id,
            D.sub AS name,
            REPLACE(json_extract(D.`attr`,\"$.Latitud\"),'\"','') AS lat,
            REPLACE(json_extract(D.`attr`,\"$.Longitud\"),'\"','') AS lng,
            REPLACE(REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"',''),'\\\u00e1','á') AS estrato
            FROM data AS D
            INNER JOIN area_location AS AL
            ON D.id=AL.location_id
            WHERE D.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'INEGI Estratos')";

                // $result = DB::table('data')->where('type_id',17)
                // // ->join('area_location', function ($join) {
                // //     $join->on('data.id', '=', 'area_location.location_id');           
                // // })
                // ->selectRaw('sub as name')
                // ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(attr, "$.Latitud")) as lat')
                // ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(attr, "$.Longitud")) as lng')
                // ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(attr, "$.Estrato")) as estrato')
                // // ->selectRaw('area_location.location_id as id')
                // ->get();
            if(empty($areas)){
                $this->sql="SELECT 
                D.id as location_id,
                D.sub AS name,
                REPLACE(json_extract(D.`attr`,\"$.Latitud\"),'\"','') AS lat,
                REPLACE(json_extract(D.`attr`,\"$.Longitud\"),'\"','') AS lng,
                REPLACE(REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"',''),'\\\u00e1','á') AS estrato
                FROM data AS D
                -- INNER JOIN area_location AS AL
                -- ON D.id=AL.location_id
                WHERE D.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'INEGI Estratos')";

            }else{
                $this->sql="SELECT 
                D.id as location_id,
                D.sub AS name,
                REPLACE(json_extract(D.`attr`,\"$.Latitud\"),'\"','') AS lat,
                REPLACE(json_extract(D.`attr`,\"$.Longitud\"),'\"','') AS lng,
                REPLACE(REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"',''),'\\\u00e1','á') AS estrato
                FROM data AS D
                INNER JOIN area_location AS AL
                ON D.id=AL.location_id
                WHERE D.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'INEGI Estratos')";
                $this->sql.=($request->areas)?" AND AL.area_id IN ($areas)":"";
            }


            // $count = DB::select(DB::raw($this->sql));
            //var_dump($count);
            // die();

        $result=cache()->remember("companies-$areas",(60*60*24*30),function(){
            // echo 'dentro';
            return DB::select(DB::raw($this->sql));
        });
        //$articles = Cache::pull("companies-$areas");
        //var_dump("companies-$areas");

        if(!is_null($result)){
            //echo 'here';
            $this->response=[
                'body'=>[
                    'companies'=>$result,
                    'areas'=>$request->areas,
                ],
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);
    }

    public function company($id)
    {

        $sql="SELECT ||
            D.id as location_id,
            D.sub AS name,
            REPLACE(json_extract(D.`attr`,\"$.Razon_social\"),'\"','') AS Razon_social,
            REPLACE(json_extract(D.`attr`,\"$.Clase_actividad\"),'\"','') AS Clase_actividad,
            REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','') AS Estrato,
            REPLACE(json_extract(D.`attr`,\"$.Fecha_Alta\"),'\"','') AS Fecha_Alta
            FROM data AS D
            WHERE D.type_id IN (SELECT id FROM types WHERE name REGEXP 'Estrato')
            AND D.id = $id";

        $result=DB::select(DB::raw($sql));

        if(!is_null($result)){
            $this->response=[
                'body'=> collect($result)->first(),
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);
    }


    public function stratum(Request $request)
    {
        $validated=validator($request->all(),['areas'=>'sometimes|array']);

        if($request->areas) $areas = implode(',', $request->areas);

        if($validated->fails())
            return response($validated->errors(),400);

        $sql="SELECT
            REPLACE(REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"',''),'m\\\u00e1s','más') AS estrato,
            COUNT(*) AS total,
            CASE 
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='0 a 5 personas' THEN 1
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='6 a 10 personas' THEN 2
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='11 a 30 personas' THEN 3
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='31 a 50 personas' THEN 4
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='51 a 100 personas' THEN 5
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='101 a 250 personas' THEN 6
            WHEN REPLACE(json_extract(D.`attr`,\"$.Estrato\"),'\"','')='251 y m\u00e1s personas' THEN 7
            ELSE 100
            END AS orden
            FROM loads AS L
            INNER JOIN data AS D
            ON L.id=D.load_id
            LEFT JOIN area_location AS AL
            ON D.id=AL.location_id
            WHERE L.type_id IN (SELECT id FROM types WHERE NAME REGEXP 'Estrato')";
            $sql.=($request->areas)?"AND AL.area_id IN ($areas)":"";
            $sql.="AND AL.area_id IS NOT NULL
            GROUP BY estrato
            ORDER BY orden ASC;";

        $result=DB::select(DB::raw($sql));



        if(!is_null($result)){
            $this->response=[
                'body'=>[
                    'stratum'=>$result,
                    'areas'=>$request->areas,
                ],
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);
    }

    public function politicDivision()
    {

        $this->sql="SELECT * FROM (SELECT 
           D.headline,
            D.`key`,
            D.`sub`,
            json_extract(D.attr,'$.properties')AS properties,
            json_extract(D.attr,'$.coordinates')AS coordinates,
            D.`date_start`,
            D.`date_end`
             FROM 
            data AS D 
            INNER JOIN types AS T 
            ON D.`type_id`=T.`id`
            WHERE T.name REGEXP 'División Política'
            AND D.`date_start` >= DATE_FORMAT(CONCAT(YEAR(CURDATE()),'-01-01 00:00:00'), '%Y-%m-%d %H:%i:%s')
            ORDER BY D.created_at DESC) AS S
            GROUP BY S.headline";

        $result=cache()->remember("politicDivision",(60*60*24*30*365),function(){
            return DB::select(DB::raw($this->sql));
        });

        $body=collect($result)->map(function($item){
            return [
                'name'=>$item->headline,
                'id'=>$item->key,
                'abr'=>$item->sub,
                'properties'=>json_decode($item->properties),
                'coordinates'=>json_decode($item->coordinates),
                'date_start'=>$item->date_start,
                'date_end'=>$item->date_end
            ];
        });

        if(!is_null($result)){
            $this->response=[
                'body'=>$body,
                'message' => 'Successfully'];
            $this->code=200;
        }


        return response($this->response,$this->code);
    }
}
