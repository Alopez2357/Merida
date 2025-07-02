<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmploymentController extends  BaseController
{
    public $date_start;
    public $date_end;

    public function headlines(Request $request)
    {
        $validated=validator($request->all(),['year'=>'required',
                                            'quarter'=>'required']);

        if($validated->fails())
            return response($validated->errors(),400);


        $this->setDates($request->year, $request->quarter);

        $sql= "
        SELECT D.`headline`
        FROM data AS D
        WHERE 1=1
        AND  type_id IN ( SELECT id FROM types WHERE NAME REGEXP 'empleo')
        AND D.date_start >= '$this->date_start'
        AND D.date_end <= '$this->date_end'
        AND D.headline NOT REGEXP '^(10.|1.)'
        GROUP BY D.`headline`
        ORDER BY D.`headline` ASC;
        ";

        $headlines=[];
        $result=DB::select(DB::raw($sql));
        foreach($result as $i=>$item){
            $headlines[$i]=$item->headline;
        }


        if(!is_null($result)){
            $this->response=[
                'body'=>$headlines,
                'date_start'=>$this->date_start,
                'date_end'=>$this->date_end,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }

    public function keys(Request $request)
    {

        $validated=validator($request->all(),['headline'=>'required',
                                             'year'=>'required',
                                            'quarter'=>'required']);

        if($validated->fails())
            return response($validated->errors(),400);

        $this->setDates($request->year, $request->quarter);

        $sql="SELECT
        D.`key`
        FROM
        data AS D
        WHERE
        type_id IN
        (SELECT
        id
        FROM
        types
        WHERE NAME REGEXP 'empleo')
        AND D.date_start >= '$this->date_start'
        AND D.date_end <= '$this->date_end'
        AND D.headline=TRIM('{$request->headline}')
        AND D.key IS NOT NULL
        GROUP BY D.`key`
        ORDER BY D.`key` ASC;";

        $result=DB::select(DB::raw($sql));


        if(!empty($result)) {
            $this->response = [
                'body' => $result,
                'message' => 'Successfully'];
            $this->code = 200;
        }else{

            $sql="SELECT
            D.`sub` AS `key`
            FROM
              data AS D
            WHERE
            type_id IN
              (SELECT
                id
              FROM
                types
              WHERE NAME REGEXP 'empleo')
            AND D.date_start >= '$this->date_start'
            AND D.date_end <= '$this->date_end'
            AND D.headline=TRIM('{$request->headline}')
            AND D.`sub` IS NOT NULL
            GROUP BY D.`sub`
            ORDER BY D.`sub` ASC;";
                $result=DB::select(DB::raw($sql));

            $this->response = [
                'body' => $result,
                'message' => 'Successfully'];
            $this->code = 200;
        }

        return response($this->response,$this->code);

    }

    public function data(Request $request)
    {
        $validated=validator($request->all(),['key'=>'required',
                                            'year'=>'required',
                                            'quarter'=>'required']);

        if($validated->fails())
            return response($validated->errors(),400);

        $this->setDates($request->year, $request->quarter);

        $sql="SELECT
        D.`sub` as title,
        D.`date_start`,
        D.`date_end`,
        D.`attr`
            FROM
              data AS D
            WHERE
            type_id IN
              (SELECT
                id
              FROM
                types
              WHERE name REGEXP 'empleo')
            AND D.`date_start`>='{$this->date_start}'
            AND D.`date_end`<='{$this->date_end}'
            AND D.key=TRIM('$request->key')
            AND D.key IS NOT NULL
            AND D.`sub` IS NOT NULL
            AND json_extract(D.`attr`,\"$.Hombres\")<>'null'
            AND json_extract(D.`attr`,\"$.Mujeres\")<>'null'
               ORDER BY D.`key` ASC;";


        $result=DB::select(DB::raw($sql));


        if(empty($result)) {
            $sql="SELECT
            IFNULL(D.`sub`,D.key) as title,
            D.`date_start`,
            D.`date_end`,
            D.`attr`
            FROM
              data AS D
            WHERE
            type_id IN
              (SELECT
                id
              FROM
                types
              WHERE NAME REGEXP 'empleo')
            AND D.`date_start`>='{$this->date_start}'
            AND D.`date_end`<='{$this->date_end}'
            AND (D.sub=TRIM('$request->key') OR  D.key=TRIM('$request->key'))
            ORDER BY D.`sub` ASC;";

            $result=DB::select(DB::raw($sql));

        }

        $result_array=[];
        foreach($result as $res){
            $el=(array)$res;
            $el['attr']=json_decode($el['attr']);
            $result_array[]=$el;
        }

            $this->response=[
                'body'=>$result_array,
                'message' => 'Successfully'];
            $this->code=200;

        return response($this->response,$this->code);
    }

    public function setDates($Year,$Quarter)
    {
        $Mbegin='';
        $Mend='';

        switch ($Quarter) {
            case 1:
                $Mbegin=1;
                $Mend=3;
                break;
            case 2:
                $Mbegin=4;
                $Mend=6;
                break;
            case 3:
                $Mbegin=7;
                $Mend=9;
                break;
            case 4:
                $Mbegin=10;
                $Mend=12;
                break;
        }

        $this->date_start=Carbon::create($Year, $Mbegin,1)->format("Y-m-d H:i:s");
        $this->date_end=(Carbon::create($Year, $Mend,1))->addMonth()->format("Y-m-d H:i:s");
    }

    public function history()
    {
        $sql="SELECT 
        D.`headline`,
        YEAR(D.`date_start`) AS year,
        QUARTER(D.`date_start`) AS quarter,
        D.`attr`,
        D.`date_start`,
        D.`date_end`
        FROM data AS D
        WHERE D.`type_id` IN (SELECT id  FROM types WHERE NAME REGEXP 'empleo')
        AND D.date_start >= DATE_SUB(NOW(), INTERVAL 10 YEAR)
        AND D.`headline` REGEXP 'Población total'
        ORDER BY year ASC, quarter ASC
        ";

        $result=DB::select(DB::raw($sql));

        $history=[];
        foreach ($result  as $res){
            $history[$res->year]=json_decode($res->attr);
        }


        if(!is_null($result)){
            $this->response=[
                'body'=>$history,
                'message' => 'Successfully'];
            $this->code=200;
        }
        return response($this->response,$this->code);
    }
}
