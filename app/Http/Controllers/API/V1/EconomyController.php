<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EconomyController extends BaseController
{
  public function inpcMerida()
  {
      $sql="SELECT
      D.headline,
      D.key,
      D.date_start,
      REPLACE(JSON_EXTRACT(D.attr,\"$.602155\"),'\"','') AS total
      FROM data AS D
      WHERE type_id IN (SELECT id FROM types AS T WHERE T.`name` REGEXP 'inpc mérida')
      AND date_start >= DATE_FORMAT(DATE_SUB(CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE()),'-01'), INTERVAL 1 YEAR),'%Y-%m-%d %H:%i:%s')";

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
      $type_id=Type::where('name','REGEXP','Inflación')->firstOrFail()->id;
      $sql="
          ";

      $result=DB::select(DB::raw($sql));

      if(!is_null($result)){
          $this->response=[
              'body'=>$result,
              'message' => 'Successfully'];
          $this->code=200;
      }
      return response($this->response,$this->code);
  }

  public function deficiencies()
  {
      $sql="
       SELECT
      D.headline as category, D.sub as year,
      FORMAT(D.attr,2) as percent
      FROM
      data AS D
      inner join types as T
      on D.type_id = T.id
      AND T.name regexp 'coneval'
      WHERE date_start > date_format(date_sub(curdate(), interval 15 year),'%Y-%m-%d %H:%i:%s')
       AND D.headline regexp 'rezago|salud|seguridad|vivienda|alimentación'
      AND D.key regexp 'porcentaje'
      group by D.headline, D.sub";

      $result=DB::select(DB::raw($sql));

      $labels=[];

      $data=[];
      foreach ($result as $k=>$row){
          $labels[$k]=$row->category;
          $data[$row->year][$row->category]=$row->percent;
      }


      $body['labels']=array_unique($labels);
      $body['data']=$data;


      if(!is_null($result)){
          $this->response=[
              'body'=>$body,
              'message' => 'Successfully'];
          $this->code=200;
      }
      return response($this->response,$this->code);
  }

  public function poverty()
  {
      $sql="
       SELECT
      D.headline as category, D.sub as year,
      FORMAT(D.attr,2) as percent
      FROM
      data AS D
      inner join types as T
      on D.type_id = T.id
      AND T.name regexp 'coneval'
      where date_start > date_format(date_sub(curdate(), interval 15 year),'%Y-%m-%d %H:%i:%s')
      AND D.headline regexp 'pobreza|vulnerable'
      AND D.headline not regexp 'ingreso'
      AND D.key regexp 'porcentaje'
      group by D.headline, D.sub
      ORDER by D.headline";

      $result=DB::select(DB::raw($sql));

      $labels=[];

      $data=[];
      foreach ($result as $k=>$row){
          $labels[$k]=$row->category;
          $data[$row->year][$row->category]=$row->percent;
      }


      $body['labels']=array_unique($labels);
      $body['data']=$data;


      if(!is_null($result)){
          $this->response=[
              'body'=>$body,
              'message' => 'Successfully'];
          $this->code=200;
      }
      return response($this->response,$this->code);
  }

  public function business_destinations()
  {
      $sql="SELECT
          C.country_id,
          C.name,
          C.flag,
          S.*
          FROM (SELECT
          D.headline,
          D.key,
          D.sub,
          CAST(JSON_EXTRACT(D.`attr`,'$.trade_value')AS DOUBLE) AS trade_value,
          ROUND(CAST(JSON_EXTRACT(D.`attr`,'$.share') AS DOUBLE ),2) AS share,
          D.`date_start`,
          D.`date_end`
          FROM data AS D
          INNER JOIN types AS T
          ON D.`type_id` =T.`id`
          WHERE T.`name` REGEXP 'destinos comerciales'
          AND D.date_start >= DATE_SUB(DATE_FORMAT((SELECT date_start FROM data WHERE type_id IN (SELECT id FROM types WHERE NAME REGEXP 'destinos comerciales') ORDER BY date_start DESC LIMIT 1),'%Y-%m-%d %H:%i:%s'), INTERVAL  1 YEAR)
          ORDER BY SHARE DESC) AS S
          INNER JOIN countries AS C
          ON S.key =  C.key
          WHERE S.share > 5
          ORDER BY S.share DESC";

      $result=DB::select(DB::raw($sql));

      if(!is_null($result)){
          $this->response=[
              'body'=>$result,
              'message' => 'Successfully'];
          $this->code=200;
      }
      return response($this->response,$this->code);
  }

}
