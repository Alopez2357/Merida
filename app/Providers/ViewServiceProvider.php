<?php

namespace App\Providers;

use App\View\Composers\ConstantsComposer;
use App\View\Composers\MenuComposer;
use App\View\Composers\SourceComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['partials.sidebar.*'],MenuComposer::class);

        View::composer(['livewire.dashboard.maps.*',
                        'livewire.dashboard.main'],ConstantsComposer::class);

        View::composer(['livewire.dashboard.uploads.*'],SourceComposer::class);
    }
}
