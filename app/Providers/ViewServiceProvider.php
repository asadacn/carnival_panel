<?php

namespace App\Providers;

use App\Models\HotspotZone;
use App\Models\CardSeller;
use Illuminate\Support\ServiceProvider;
use View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['card_sellers.fields'], function ($view) {
            $hotspot_zoneItems = HotspotZone::pluck('zone_title', 'id')->toArray();
            $view->with('hotspot_zoneItems', $hotspot_zoneItems);
        });

        // Inject $card_sellerItems into any hotspot zone view that needs it
        View::composer(
            ['hotspot_zones.fields', 'hotspot_zones.create', 'hotspot_zones.edit'],
            function ($view) {
                $card_sellerItems = CardSeller::pluck('name', 'id')->toArray();
                $view->with('card_sellerItems', $card_sellerItems);
            }
        );
    }
}