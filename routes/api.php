<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

if(!str_icontains('localhost', get_const('APP_URL')))
{
  URL::forceRootUrl(get_const('APP_URL'));
  URL::forceScheme('https');
}

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('apikey')->prefix('/v1')->name('v1.')->group(function() {


    //DASHBOARD
    Route::prefix('/dashboard')->name('dashboard.')->group(function() {
        Route::get('/accommodation',[\App\Http\Controllers\API\V1\DashController::class,'accommodation'])->name('accommodation');
        Route::get('/inflation',[\App\Http\Controllers\API\V1\DashController::class,'inflation'])->name('inflation');
        Route::get('/airdna',[\App\Http\Controllers\API\V1\DashController::class,'airdna'])->name('airdna');
        Route::get('/employment',[\App\Http\Controllers\API\V1\DashController::class,'employment'])->name('employment');
        Route::get('/employment-rate',[\App\Http\Controllers\API\V1\DashController::class,'employment_rate'])->name('employment_rate');
        Route::get('/unemployment-rate',[\App\Http\Controllers\API\V1\DashController::class,'unemployment_rate'])->name('unemployment_rate');
    });

    //MAPS
    Route::prefix('/maps')->name('maps.')->group(function() {
        Route::post('/airdna',[\App\Http\Controllers\API\V1\MapsController::class,'airdnd'])->name('airdnd');
        Route::post('/areas',[\App\Http\Controllers\API\V1\MapsController::class,'areas'])->name('areas');
        Route::post('/statistics',[\App\Http\Controllers\API\V1\MapsController::class,'statistics'])->name('statistics');
        Route::prefix('/inegi')->name('inegi.')->group(function() {
            Route::post('/companies',[\App\Http\Controllers\API\V1\InegiController::class,'companies'])->name('companies');
            Route::get('/company/{id}',[\App\Http\Controllers\API\V1\InegiController::class,'company'])->name('company');
            Route::post('/stratum',[\App\Http\Controllers\API\V1\InegiController::class,'stratum'])->name('stratum');
            Route::get('/politic-division',[\App\Http\Controllers\API\V1\InegiController::class,'politicDivision'])->name('politic-division');
        });
    });

    // Economy
    Route::prefix('/economy')->name('economy.')->group(function() {

        Route::get('/inpc-merida',[\App\Http\Controllers\API\V1\EconomyController::class,'inpcMerida'])->name('inpc-merida');
        Route::get('/inflation',[\App\Http\Controllers\API\V1\EconomyController::class,'inflation'])->name('inflation');
        Route::get('/business-destinations',[\App\Http\Controllers\API\V1\EconomyController::class,'business_destinations'])->name('business-destinations');

        Route::prefix('/coneval')->name('coneval.')->group(function() {
            Route::get('/deficiencies',[\App\Http\Controllers\API\V1\EconomyController::class,'deficiencies'])->name('deficiencies');
            Route::get('/poverty',[\App\Http\Controllers\API\V1\EconomyController::class,'poverty'])->name('poverty');
        });

        Route::prefix('/employment')->name('employment.')->group(function() {
            Route::post('/headlines',[\App\Http\Controllers\API\V1\EmploymentController::class,'headlines'])->name('headlines');
            Route::post('/keys',[\App\Http\Controllers\API\V1\EmploymentController::class,'keys'])->name('keys');
            Route::post('/data',[\App\Http\Controllers\API\V1\EmploymentController::class,'data'])->name('data');
            Route::get('/history',[\App\Http\Controllers\API\V1\EmploymentController::class,'history'])->name('history');
        });
    });

    //Turismo
    Route::prefix('/tourism')->name('tourism.')->group(function() {
        Route::prefix('/arrivals')->name('arrivals.')->group(function() {
            Route::post('/',[\App\Http\Controllers\API\V1\TourismController::class,'arrivals'])->name('index');
            Route::get('/monthly',[\App\Http\Controllers\API\V1\TourismController::class,'arrivalsMonthly'])->name('monthly');
            Route::get('/historical',[\App\Http\Controllers\API\V1\TourismController::class,'arrivalsHistorical'])->name('historical');
        });
        Route::post('/spend',[\App\Http\Controllers\API\V1\TourismController::class,'spend'])->name('spend');
        Route::post('/occupation',[\App\Http\Controllers\API\V1\TourismController::class,'occupation'])->name('occupation');
        Route::post('/stopover',[\App\Http\Controllers\API\V1\TourismController::class,'stopover'])->name('stopover');
        Route::prefix('/movements')->name('movements.')->group(function() {
            Route::post('/operational', [\App\Http\Controllers\API\V1\MovementsController::class, 'operational'])->name('operational');
            Route::post('/arrives', [\App\Http\Controllers\API\V1\MovementsController::class, 'arrives'])->name('arrives');
            Route::post('/departures', [\App\Http\Controllers\API\V1\MovementsController::class, 'departures'])->name('departures');
        });
    });

    // UPLOADS
    Route::prefix('/upload')->name('upload.')->group(function() {
        //MAPS
        Route::prefix('/maps')->name('maps.')->group(function() {
            Route::post('/airdna', [\App\Http\Controllers\API\V1\Upload\MapsController::class, 'airdna'])->name('airdna');
        });
        //History Uploads
        Route::prefix('/history')->name('history.')->group(function() {
            Route::post('/source', [\App\Http\Controllers\API\V1\Upload\HistoryController::class, 'source'])->name('source');
        });
    });




});
