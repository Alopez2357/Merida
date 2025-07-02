<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Coordinate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolygonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create(['name'=>'-- Sin Cuadrante --','visible'=>0]);

        $Area=Area::create(['name'=>'Centro','visible'=>1]);
        Coordinate::insert([
                            ['lng'=>-89.6388661,'lat'=>20.9856425,'area_id'=>$Area->id],
                            ['lng'=>-89.641441,'lat'=>20.973942,'area_id'=>$Area->id],
                            ['lng'=>-89.6422993,'lat'=>20.9636832,'area_id'=>$Area->id],
                            ['lng'=>-89.6445309,'lat'=>20.9555078,'area_id'=>$Area->id],
                            ['lng'=>-89.6452175,'lat'=>20.9484541,'area_id'=>$Area->id],
                            ['lng'=>-89.6390377,'lat'=>20.9465303,'area_id'=>$Area->id],
                            ['lng'=>-89.6280514,'lat'=>20.9439652,'area_id'=>$Area->id],
                            ['lng'=>-89.6162068,'lat'=>20.9462097,'area_id'=>$Area->id],
                            ['lng'=>-89.6047054,'lat'=>20.9559887,'area_id'=>$Area->id],
                            ['lng'=>-89.6011006,'lat'=>20.9651259,'area_id'=>$Area->id],
                            ['lng'=>-89.6026455,'lat'=>20.9763463,'area_id'=>$Area->id],
                            ['lng'=>-89.607967,'lat'=>20.9843603,'area_id'=>$Area->id],
                            ['lng'=>-89.6101986,'lat'=>20.9898096,'area_id'=>$Area->id],
                            ['lng'=>-89.6225582,'lat'=>20.9922137,'area_id'=>$Area->id],
                            ['lng'=>-89.6332012,'lat'=>20.9917329,'area_id'=>$Area->id],
                            ['lng'=>-89.6388661,'lat'=>20.9856425,'area_id'=>$Area->id],
                        ]);
        $Area=Area::create(['name'=>'NorEste','visible'=>1]);

        Coordinate::insert([
            ['lng'=>-89.6225582,'lat'=>20.9922137,'area_id'=>$Area->id],
            ['lng'=>-89.6101986,'lat'=>20.9898096,'area_id'=>$Area->id],
            ['lng'=>-89.607967,'lat'=>20.9843603,'area_id'=>$Area->id],
            ['lng'=>-89.6026455,'lat'=>20.9763463,'area_id'=>$Area->id],
            ['lng'=>-89.6017597,'lat'=>20.9708336,'area_id'=>$Area->id],
            ['lng'=>-89.4514273,'lat'=>20.9752816,'area_id'=>$Area->id],
            ['lng'=>-89.4476507,'lat'=>21.115306,'area_id'=>$Area->id],
            ['lng'=>-89.6244619,'lat'=>21.1178681,'area_id'=>$Area->id],
            ['lng'=>-89.6225582,'lat'=>20.9922137,'area_id'=>$Area->id],
        ]);

        $Area=Area::create(['name'=>'SurEste','visible'=>1]);
        Coordinate::insert([
                            ['lng'=>-89.6018777,'lat'=>20.9708186,'area_id'=>$Area->id],
                            ['lng'=>-89.6011006,'lat'=>20.9651259,'area_id'=>$Area->id],
                            ['lng'=>-89.6047054,'lat'=>20.9559887,'area_id'=>$Area->id],
                            ['lng'=>-89.6162068,'lat'=>20.9462097,'area_id'=>$Area->id],
                            ['lng'=>-89.6280514,'lat'=>20.9439652,'area_id'=>$Area->id],
                            ['lng'=>-89.6275839,'lat'=>20.8774507,'area_id'=>$Area->id],
                            ['lng'=>-89.4535193,'lat'=>20.8760072,'area_id'=>$Area->id],
                            ['lng'=>-89.4514273,'lat'=>20.9752816,'area_id'=>$Area->id],
                            ['lng'=>-89.6018777,'lat'=>20.9708186,'area_id'=>$Area->id],
                    ]);

        $Area=Area::create(['name'=>'SurOeste','visible'=>1]);
        Coordinate::insert([
                            ['lng'=>-89.6280514,'lat'=>20.9439652,'area_id'=>$Area->id],
                            ['lng'=>-89.6390377,'lat'=>20.9465303,'area_id'=>$Area->id],
                            ['lng'=>-89.6452175,'lat'=>20.9484541,'area_id'=>$Area->id],
                            ['lng'=>-89.6445309,'lat'=>20.9555078,'area_id'=>$Area->id],
                            ['lng'=>-89.6422993,'lat'=>20.9636832,'area_id'=>$Area->id],
                            ['lng'=>-89.641441,'lat'=>20.973942,'area_id'=>$Area->id],
                            ['lng'=>-89.7523737,'lat'=>20.9752053,'area_id'=>$Area->id],
                            ['lng'=>-89.7528376,'lat'=>20.9054754,'area_id'=>$Area->id],
                            ['lng'=>-89.7267451,'lat'=>20.880457,'area_id'=>$Area->id],
                            ['lng'=>-89.6275839,'lat'=>20.8774507,'area_id'=>$Area->id],
                            ['lng'=>-89.6280514,'lat'=>20.9439652,'area_id'=>$Area->id],
                    ]);


        $Area=Area::create(['name'=>'NorOeste','visible'=>1]);
        Coordinate::insert([
                            ['lng'=>-89.6244619,'lat'=>21.1178681,'area_id'=>$Area->id],
                            ['lng'=>-89.750437,'lat'=>21.1185756,'area_id'=>$Area->id],
                            ['lng'=>-89.7523737,'lat'=>20.9752053,'area_id'=>$Area->id],
                            ['lng'=>-89.641441,'lat'=>20.973942,'area_id'=>$Area->id],
                            ['lng'=>-89.6388661,'lat'=>20.9856425,'area_id'=>$Area->id],
                            ['lng'=>-89.6332012,'lat'=>20.9917329,'area_id'=>$Area->id],
                            ['lng'=>-89.6225582,'lat'=>20.9922137,'area_id'=>$Area->id],
                            ['lng'=>-89.6244619,'lat'=>21.1178681,'area_id'=>$Area->id],
                        ]);
    }
}
