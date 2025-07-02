<?php

namespace App\View\Composers;

use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $items=[
                'Economy'=>[
                    'name'=>'Economía',
                    'icon'=>'<i class="fas fa-search-dollar"></i>',
                    'roles'=>'Altos Funcionarios|Funcionarios Economía|SuperAdmin|Cinco Consulting|Admin',
                    'subs'=>[
                            ['route'=>'dashboard.economy.employment',
                            'name'=>'Empleo',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.occupation',
                            'name'=>'Ocupación en Mérida',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.distribution-e',
                            'name'=>'Distribución de Empleo',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.distribution-s',
                            'name'=>'Distribución de Salario',
                             'roles'=>''],
                            ['route'=>'dashboard.economy.position',
                            'name'=>'Posición en la ocupación',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.working-day',
                            'name'=>'Jornada de Trabajo',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.economic-unit',
                            'name'=>'Tipo de unidad económica',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.unemployed-age-groups',
                            'name'=>'Población desocupada grupos de edad',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.unemployed-education-level',
                            'name'=>'Población desocupada Nivel de instrucción',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.duration-unemployment',
                            'name'=>'Duración del desempleo',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.inpcmerida',
                            'name'=>'Inflación Mérida',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.coneval',
                            'name'=>'Medición Pobreza',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.business-destinations',
                            'name'=>'Destinos comerciales',
                            'roles'=>''],
                            ['route'=>'dashboard.economy.employment-city',
                            'name'=>'Ocupación por Ciudad',
                            'roles'=>'']
                        ]
                ],
                'Tourism'=>[
                    'name'=>'Turismo',
                    'icon'=>'<i class="fas fa-plane"></i>',
                    'roles'=>'Altos Funcionarios|Funcionarios Turismo|SuperAdmin|Cinco Consulting|Admin',
                    'subs'=>[
                            [
                            'route'=>'dashboard.tourism.arrivals',
                            'name'=>'Arribos',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.stopover',
                            'name'=>'Turistas Pernocta',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.arrivals-monthly',
                            'name'=>'Arribos Mensual',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.arrivals-historical',
                            'name'=>'Arribos Histórico',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.operational',
                            'name'=>'Movimiento Operacional',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.arrivals-national',
                            'name'=>'Llegadas nacionales',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.arrivals-international',
                            'name'=>'Llegadas Internacionales',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.departures-national',
                            'name'=>'Salidas Nacionales',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.departures-international',
                            'name'=>'Salidas Internacionales',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.occupation',
                            'name'=>'Ocupación Hotelera',
                            'roles'=>''],
                            [
                            'route'=>'dashboard.tourism.spend',
                            'name'=>'Gasto Promedio Turistas',
                            'roles'=>''],
                        ]
                ],
                'Maps'=>[
                    'name'=>'Mapas',
                    'icon'=>'<i class="fas fa-map-marked-alt"></i>',
                    'roles'=>'Altos Funcionarios|Funcionarios Turismo|Funcionarios Economía|SuperAdmin|Cinco Consulting|Admin',
                    'subs'=>[
                        /*['route'=>'dashboard.maps.commerce',
                        'name'=>'Comercios INEGI',
                        'roles'=>'Altos Funcionarios|Funcionarios Economía|SuperAdmin|Cinco Consulting|Admin'],*/
                        ['route'=>'dashboard.maps.ardnd',
                        'name'=>'Ocupación AirDNA',
                        'roles'=>'Altos Funcionarios|Funcionarios Turismo|SuperAdmin|Cinco Consulting|Admin'],
                        ['route'=>'dashboard.maps.denue',
                            'name'=>'Comercios INEGI DENUE',
                            'roles'=>'Altos Funcionarios|Funcionarios Turismo|SuperAdmin|Cinco Consulting|Admin'],
                    ],
                ],
            ];

        $loads=[
            'MapsLoad'=>[
                'name'=>'Mapas',
                'subs'=>[
                    ['route'=>'dashboard.uploads.maps.airdna',
                    'name'=>'Airdna'],
                ],
            ],
            'TourismLoad'=>[
                'name'=>'Turismo',
                'subs'=>[
                    ['route'=>'dashboard.uploads.tourism.arrivals',
                        'name'=>'Arribos'],
                    ['route'=>'dashboard.uploads.tourism.spend',
                        'name'=>'Gasto Promedio Turistas'],
                    ['route'=>'dashboard.uploads.tourism.occupation',
                        'name'=>'Ocupación Hotelera'],
                    ['route'=>'dashboard.uploads.tourism.stopover',
                        'name'=>'Turistas Pernocta'],
                    ['route'=>'dashboard.uploads.tourism.od',
                        'name'=>'Origen y Destino'],
                    ['route'=>'dashboard.uploads.tourism.operational',
                        'name'=>'Movimiento Operacional'],
                ],
            ],
            'EconomyLoad'=>[
                'name'=>'Economía',
                'subs'=>[
                    ['route'=>'dashboard.uploads.economy.employment',
                        'name'=>'Empleo'],
                    ['route'=>'dashboard.uploads.economy.inpc-merida',
                        'name'=>'INPC Mérida'],
                    ['route'=>'dashboard.uploads.economy.inpc-nacional',
                        'name'=>'INPC Nacional'],
                    ['route'=>'dashboard.uploads.economy.coneval',
                        'name'=>'CONEVAL'],
                    ['route'=>'dashboard.uploads.economy.business-destinations',
                        'name'=>'Destinos Comerciales']
                ],
            ]
        ];
        $view->with(['items'=>$items,'loads'=>$loads]);
    }
}