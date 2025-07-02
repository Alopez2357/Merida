<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\Area;
use App\Models\Load;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapsController extends BaseController
{

    public $start;
    public $end;
    public $areas;
    public $locations=[];
    public $sql;

    public function __construct()
    {
        parent::__construct();
        $this->allowedFields=['id','name','created_at','updated_at'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function airdnd(Request $request)
    {

        $validated=validator($request->all(),[
                                        'start'=>'required|date',
                                        'end'=>'required|date|after_or_equal:start',
                                        'areas'=>'sometimes|array',
                                        ]);

        if($validated->fails())
            return response($validated->errors(),400);

            $this->areas=$request->areas;

            $arrStart = explode('-', $request->start);
            $arrEnd   = explode('-', $request->end);

            /*$this->start=(new Carbon($request->start))->format('Y-m-d 00:00:00');
            $this->end=(new Carbon($request->end))->format('Y-m-d 23:59:59');*/

            $this->start = $arrStart[0] . '-' . str_pad($arrStart[1], 2, '0', STR_PAD_LEFT). '-' . str_pad($arrStart[2], 2, '0', STR_PAD_LEFT) . ' 00:00:00';
            $this->end   = $arrEnd[0] . '-' . str_pad($arrEnd[1], 2, '0', STR_PAD_LEFT)  . '-' . str_pad($arrEnd[2], 2, '0', STR_PAD_LEFT). ' 23:59:59';

            $query = DB::table('loads')
                ->join('data','loads.id','=','data.load_id')
                ->join('area_location','data.id','=','area_location.location_id')
                ->join('areas','area_location.area_id','=','areas.id')
                ->where('loads.type_id', '=', Type::where('name','REGEXP','Airdna')->firstOrFail()->id)
                ->whereBetween('data.date_start',[$this->start,$this->end])
                ->whereRaw('JSON_EXTRACT(data.attr,"$.active") = true')
                ->whereRaw('JSON_EXTRACT(data.attr,"$.scraped_during_month") = true')
                ->when($request->areas,function($query,$areas){
                    $query->whereIn('areas.id',array_values($areas));
                })
                ->groupBy('data.key')
                ->orderBy('data.id', 'desc')
                ->select('data.id','data.date_start','data.attr', DB::raw('areas.id AS area_id'),DB::raw('areas.name AS area_name'))
                ->select('data.id','data.date_start','data.attr', DB::raw('areas.id AS area_id'),DB::raw('areas.name AS area_name'));

            $load = $query->get();

            $this->locations = collect($load)->map(function ($el) {
                return [
                        'id'=>$el->id,
                        'json'=>json_decode($el->attr),
                        'date_start'=>$el->date_start,
                        'area_id'=>$el->area_id,
                        'area_name'=>$el->area_name,
                    ];
            });


            if(!is_null($load)){
                $this->response=[
                    'body'=>[
                        'locations'=>$this->locations,
                        'start'=>"{$this->start}",
                        'end'=>$this->end,
                    ],
                    'message' => 'Successfully'];
                $this->code=200;
            }


        return response($this->response,$this->code);

    }

    public function statistics(Request $request)
    {
        $validated=validator($request->all(),['start'=>'required|date',
                                            'end'=>'required|date|after_or_equal:start',
                                            'areas'=>'sometimes|array']);


        if($validated->fails())
            return response($validated->errors(),400);

        $start=$request->start;
        $end=$request->end;

        $areas =(isset($request->areas) && count($request->areas) >0 )?implode(',', $request->areas):[];

        $type_id=Type::where('name','REGEXP','Airdna')->firstOrFail()->id;

        $this->sql="SELECT 
            DATE_FORMAT(D.date_start, \"%Y-%m\") AS sort,
            DATE_FORMAT(D.date_start, \"%Y-%b\") AS month_year,
            COUNT(D.id) AS assets,
            CAST(format(avg(JSON_EXTRACT(D.attr,\"$.revenue_usd\") ),2) AS DOUBLE) AS revenue_usd,
              CAST(REPLACE(FORMAT(avg(JSON_EXTRACT(D.attr,'$.revenue_native')),2),',','') AS DOUBLE) AS revenue_native,
             CAST(format(avg(JSON_EXTRACT(D.attr,\"$.adr_usd\")),2) AS DOUBLE)AS adr_usd,
             CAST(format(avg(JSON_EXTRACT(D.attr,\"$.adr_native\")),2)AS DOUBLE) AS adr_native,
              CAST(format(avg(JSON_EXTRACT(D.attr,'$.occupancy_rate') * 100),2) AS DOUBLE) AS ocupancy,
             CAST(format(avg(JSON_EXTRACT(D.attr,\"$.number_of_reservations\")),2) AS DOUBLE) AS reservations,
            cast(format(avg(JSON_EXTRACT(D.attr,\"$.reservation_days\")),2)AS DOUBLE) AS reservation_days,
            CAST(REPLACE(format(avg(JSON_EXTRACT(D.attr,\"$.revenue_native\") / JSON_EXTRACT(D.attr,\"$.reservation_days\")),2),',','') AS DOUBLE) fee_revenue_native
            FROM loads AS L
            INNER JOIN data AS D 
            ON L.id=D.load_id ";
        if(!empty($areas)){
            $this->sql.=" INNER JOIN area_location AS AL
                    ON D.id=AL.location_id
                    INNER JOIN areas AS A
                    ON AL.area_id=A.id ";
        }
        $this->sql.="WHERE L.type_id=$type_id
            AND (D.date_start BETWEEN DATE_FORMAT('$start','%Y-%m-%d %H:%i:%s')
            AND DATE_FORMAT('$end','%Y-%m-%d %H:%i:%s')) 
            AND JSON_EXTRACT(D.attr,\"$.active\") = true 
            AND JSON_EXTRACT(D.attr,\"$.scraped_during_month\") = true ";

        if(!empty($areas)) {
            $this->sql.= " AND A.id IN ($areas)";
        }
            $this->sql.=" GROUP BY month_year
                     ORDER BY sort ASC";

       $result=DB::select(DB::raw($this->sql));


        if(!is_null($result)){
            $this->response=[
                'body'=>$result,
                'message' => 'Successfully'];
            $this->code=200;
        }

        return response($this->response,$this->code);


    }

    public function areas(Request $request)
    {
        $validated=validator($request->all(),['areas'=>'sometimes|array']);

        if($validated->fails())
            return response($validated->errors(),400);

        $Areas = Area::select('id','name')
            ->with(['coordinates'=>function($coordinates){
                $coordinates->select('id','lat','lng','area_id');
            }])->when($request->areas,function ($query,$areas){
                $query->whereIn('id',array_values($areas));
            })
            ->where('visible', '=', 1)
            ->orderBy('name', 'asc')
            ->get();



        if(!is_null($Areas)){
            $this->response=[
                'areas'=>$Areas,
                'message' => 'Successfully'];
            $this->code=200;
        }

        return response($this->response,$this->code);
    }

}
