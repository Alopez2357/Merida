<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if(!str_icontains('localhost', get_const('APP_URL')))
{
  URL::forceRootUrl(get_const('APP_URL'));
  URL::forceScheme('https');
}

Route::get('/',function (){
   return redirect()->route('dashboard.main');
});

/*Route::prefix('inegi')->name('inegi.')->group(function () {
    Route::get('/estrato', [\App\Http\Controllers\InegiController::class, 'estrato'])->name('estato');
});*/

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth:sanctum',config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::get('/home',\App\Http\Livewire\Dashboard\Home::class)->name('home');

    Route::get('/main', \App\Http\Livewire\Dashboard\Main::class)->name('main');

    Route::prefix('economy')->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Altos Funcionarios|Funcionarios Economía'])->name('economy.')->group(function (){
        Route::get('/employment', \App\Http\Livewire\Dashboard\Economy\Employment::class)->name('employment');
        Route::get('/coneval', \App\Http\Livewire\Dashboard\Economy\Coneval::class)->name('coneval');
        Route::get('/occupation', \App\Http\Livewire\Dashboard\Economy\Occupation::class)->name('occupation');
        Route::get('/distribution-e', \App\Http\Livewire\Dashboard\Economy\DistributionE::class)->name('distribution-e');
        Route::get('/distribution-s', \App\Http\Livewire\Dashboard\Economy\DistributionS::class)->name('distribution-s');
        Route::get('/position', \App\Http\Livewire\Dashboard\Economy\Position::class)->name('position');
        Route::get('/working-day', \App\Http\Livewire\Dashboard\Economy\WorkingDay::class)->name('working-day');
        Route::get('/economic-unit', \App\Http\Livewire\Dashboard\Economy\EconomicUnit::class)->name('economic-unit');
        Route::get('/unemployed-age-groups', \App\Http\Livewire\Dashboard\Economy\UnemployedAgeGroups::class)->name('unemployed-age-groups');
        Route::get('/unemployed-education-level', \App\Http\Livewire\Dashboard\Economy\UnemployedEducationLevel::class)->name('unemployed-education-level');
        Route::get('/duration-unemployment', \App\Http\Livewire\Dashboard\Economy\DurationUnemployment::class)->name('duration-unemployment');
        Route::get('/inpcmerida', \App\Http\Livewire\Dashboard\Economy\Inpcmerida::class)->name('inpcmerida');
        Route::get('/business-destinations', \App\Http\Livewire\Dashboard\Economy\BusinessDestinations::class)->name('business-destinations');
        Route::get('/employment-city', \App\Http\Livewire\Dashboard\Economy\EmploymentCity::class)->name('employment-city');
    });

    Route::prefix('tourism')->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Altos Funcionarios|Funcionarios Turismo'])->name('tourism.')->group(function (){
        Route::get('/arrivals', \App\Http\Livewire\Dashboard\Tourism\Arrivals::class)->name('arrivals');
        Route::get('/arrivals-monthly', \App\Http\Livewire\Dashboard\Tourism\ArrivalsMonthly::class)->name('arrivals-monthly');
        Route::get('/arrivals-historical', \App\Http\Livewire\Dashboard\Tourism\ArrivalsHistorical::class)->name('arrivals-historical');
        Route::get('/occupation', \App\Http\Livewire\Dashboard\Tourism\Occupation::class)->name('occupation');
        Route::get('/spend', \App\Http\Livewire\Dashboard\Tourism\Spend::class)->name('spend');
        Route::get('/stopover', \App\Http\Livewire\Dashboard\Tourism\Stopover::class)->name('stopover');
        Route::get('/operational', \App\Http\Livewire\Dashboard\Tourism\Operational::class)->name('operational');
        Route::get('/arrivals-national', \App\Http\Livewire\Dashboard\Tourism\ArrivalsNational::class)->name('arrivals-national');
        Route::get('/arrivals-international', \App\Http\Livewire\Dashboard\Tourism\ArrivalsInternational::class)->name('arrivals-international');
        Route::get('/departures-national', \App\Http\Livewire\Dashboard\Tourism\DeparturesNational::class)->name('departures-national');
        Route::get('/departures-international', \App\Http\Livewire\Dashboard\Tourism\DeparturesInternational::class)->name('departures-international');
    });

    Route::prefix('maps')->name('maps.')->group(function () {
        Route::get('/commerce', \App\Http\Livewire\Dashboard\Maps\Commerce::class)->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Altos Funcionarios|Funcionarios Economía'])->name('commerce');
        Route::get('/denue', \App\Http\Livewire\Dashboard\Maps\Denue::class)->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Altos Funcionarios|Funcionarios Economía'])->name('denue');
        Route::get('/ardnd', \App\Http\Livewire\Dashboard\Maps\Airdna::class)->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Altos Funcionarios|Funcionarios Turismo'])->name('ardnd');
    });

    Route::get('/sources', \App\Http\Livewire\Dashboard\Sources::class)->middleware(['role:SuperAdmin|Admin|Cinco Consulting'])->name('sources');

    Route::prefix('uploads')->name('uploads.')
      ->middleware(['role:SuperAdmin|Admin|Cinco Consulting|Pasantes'])->group(function(){
        Route::get('/instructions', \App\Http\Livewire\Dashboard\Uploads\Instructions::class)->name('instructions');

        Route::prefix('economy')->name('economy.')->group(function () {
            Route::get('/coneval', \App\Http\Livewire\Dashboard\Uploads\Economy\Coneval::class)->name('coneval');
            Route::get('/employment', \App\Http\Livewire\Dashboard\Uploads\Economy\Employment::class)->name('employment');
            Route::get('/inflation', \App\Http\Livewire\Dashboard\Uploads\Economy\Inflation::class)->name('inflation');
            Route::get('/inpc-merida', \App\Http\Livewire\Dashboard\Uploads\Economy\InpcMerida::class)->name('inpc-merida');
            Route::get('/inpc-nacional', \App\Http\Livewire\Dashboard\Uploads\Economy\InpcNacional::class)->name('inpc-nacional');
            Route::get('/business-destinations', \App\Http\Livewire\Dashboard\Uploads\Economy\BusinessDestinations::class)->name('business-destinations');
        });

        Route::prefix('tourism')->name('tourism.')->group(function () {
            Route::get('/arrivals', \App\Http\Livewire\Dashboard\Uploads\Tourism\Arrivals::class)->name('arrivals');
            Route::get('/spend', \App\Http\Livewire\Dashboard\Uploads\Tourism\Spend::class)->name('spend');
            Route::get('/occupation', \App\Http\Livewire\Dashboard\Uploads\Tourism\Occupation::class)->name('occupation');
            Route::get('/stopover', \App\Http\Livewire\Dashboard\Uploads\Tourism\Stopover::class)->name('stopover');
            Route::get('/od', \App\Http\Livewire\Dashboard\Uploads\Tourism\Od::class)->name('od');
            Route::get('/operational', \App\Http\Livewire\Dashboard\Uploads\Tourism\Operational::class)->name('operational');

        });

        Route::prefix('maps')->name('maps.')->group(function () {
            Route::get('/airdna', \App\Http\Livewire\Dashboard\Uploads\Maps\Airdna::class)->name('airdna');
        });

        Route::get('/history', \App\Http\Livewire\Dashboard\Uploads\History::class)->name('history');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/profile', \App\Http\Livewire\Dashboard\Users\Profile::class)->name('profile');
        Route::middleware(['role:SuperAdmin|Admin|Cinco Consulting'])->group(function () {
            Route::get('/list',\App\Http\Livewire\Dashboard\Users\Lists::class)->name('list');
            Route::get('/create',[\App\Http\Controllers\Dashboard\UsersController::class,'create'])->name('create');
            Route::post('/store',[\App\Http\Controllers\Dashboard\UsersController::class,'store'])->name('store');
            Route::get('/edit/{id}',[\App\Http\Controllers\Dashboard\UsersController::class,'edit'])->name('edit');
            Route::patch('/update/{id}',[\App\Http\Controllers\Dashboard\UsersController::class,'update'])->name('update');
            Route::delete('/delete/{id}',[\App\Http\Controllers\Dashboard\UsersController::class,'destroy'])->name('delete');
        });
    });
});

