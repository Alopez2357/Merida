<?php

namespace Database\Seeders;

use App\Models\Load;
use App\Models\Source;
use App\Models\Type;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Role::create(['name'=>'SuperAdmin']);
        Role::create(['name'=>'Admin']);

        Role::create(['name'=>'Cinco Consulting']);
        Role::create(['name'=>'Altos Funcionarios']);
        Role::create(['name'=>'Funcionarios Turismo']);
        Role::create(['name'=>'Funcionarios Economía']);
        Role::create(['name'=>'Pasantes']);

        User::create([
            'name' => 'User',
            'email' => 'correo@correo.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ])->syncRoles(['Admin']);

        User::create([
            'name' => 'Leonel',
            'email' => 'leonel.lopez.web@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10)
        ])->syncRoles(['SuperAdmin']);

        User::create([
            'name' => 'Natalia',
            'email' => 'natzenteno36@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10)
        ])->syncRoles(['SuperAdmin']);


        User::create([
            'name' => 'Gonzalo',
            'email' => 'gonzalo.nava.castilla@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10)
        ])->syncRoles(['SuperAdmin']);


        $Type=Type::create(['name'=>'Economía','type_id'=>0]);
            Type::create(['name'=>'Empleo','type_id'=>$Type->id]);
            Type::create(['name'=>'INPC Nacional','type_id'=>$Type->id]);
            Type::create(['name'=>'INPC Merida','type_id'=>$Type->id]);
            Type::create(['name'=>'Inflación','type_id'=>$Type->id]);

        $Type=Type::create(['name'=>'Turismo','type_id'=>0]);
            Type::create(['name'=>'Arribos al Areopuerto','type_id'=>$Type->id]);
            Type::create(['name'=>'Gasto Promedio de Turistas','type_id'=>$Type->id]);
            Type::create(['name'=>'Ocupación Hotelera','type_id'=>$Type->id]);
            Type::create(['name'=>'Turistas con Pernocta','type_id'=>$Type->id]);
            Type::create(['name'=>'Origen Destino','type_id'=>$Type->id]);
            Type::create(['name'=>'Operaciones Aeropuerto','type_id'=>$Type->id]);

        $Type=Type::create(['name'=>'Geolocalización','type_id'=>0]);
            Type::create(['name'=>'Airbnb','type_id'=>$Type->id]);
            Type::create(['name'=>'Comercios','type_id'=>$Type->id]);

        Source::create(['name'=>'INEGI','url'=>'https://www.inegi.org.mx/','active'=>1,'visible'=>1]);
        Source::create(['name'=>'AIRDNA','url'=>'http://ardnd.com/en/','active'=>1,'visible'=>1]);
        Source::create(['name'=>'CONEVAL','url'=>'https://www.coneval.org.mx','active'=>1,'visible'=>1]);


        Load::factory()->count(50)->create();

        $this->call([
            PolygonsSeeder::class
        ]);

    }
}
