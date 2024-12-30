<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $systemMenus = Menu::get();
        $serializeMenus = [];
        if($systemMenus->isNotEmpty()){
            foreach($systemMenus as $k =>$v){
                if($v->parentId==0){

                }
                //if(!isset($serializeMenus[$v->]))
            }
        }
        View::share('systemMenus', $systemMenus);
    }
}
