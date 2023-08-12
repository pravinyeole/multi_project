<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use App\Models\Help;

class MenuServiceProvider extends ServiceProvider
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
        // get all data from menu.json file
        $orgVerticalMenuJson = file_get_contents(base_path('resources/data/menu-data/organizationVerticalMenu.json'));
        $orgVerticalMenuData = json_decode($orgVerticalMenuJson);

        $verticalMenuJson = file_get_contents(base_path('resources/data/menu-data/verticalMenu.json'));
        $verticalMenuData = json_decode($verticalMenuJson);

        $adminVerticalMenuJson = file_get_contents(base_path('resources/data/menu-data/ceoVerticalMenu.json'));
        $adminVerticalMenuData = json_decode($adminVerticalMenuJson);

        $userVerticalMenuJson = file_get_contents(base_path('resources/data/menu-data/userVerticalMenu.json'));
        $userVerticalMenuData = json_decode($userVerticalMenuJson);

        $horizontalMenuJson = file_get_contents(base_path('resources/data/menu-data/horizontalMenu.json'));
        $horizontalMenuData = json_decode($horizontalMenuJson);

        $teamMenuJson = file_get_contents(base_path('resources/data/menu-data/teamMenu.json'));
        $teamMenuData = json_decode($teamMenuJson);


         // Share all menuData to all the views
        \View::share('menuData',[$verticalMenuData, $horizontalMenuData, $adminVerticalMenuData, $orgVerticalMenuData, $userVerticalMenuData,$teamMenuData]);
    }
}
