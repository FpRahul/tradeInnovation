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
        $menuSubMenuRoutes = [];
        if($systemMenus->isNotEmpty()){
            foreach($systemMenus as $k =>$v){
                if($v->parentId==0){
                    $serializeMenus[$v->id]['menu']['name'] = $v->menuName;
                    $serializeMenus[$v->id]['menu']['url'] = $v->url;
                    $serializeMenus[$v->id]['menu']['icon'] = $v->icon;
                }
                if($v->parentId>0){
                    $serializeMenus[$v->parentId]['subMenu'][$v->id]['name'] = $v->menuName;
                    $serializeMenus[$v->parentId]['subMenu'][$v->id]['url'] = $v->url;
                    $serializeMenus[$v->parentId]['subMenu'][$v->id]['icon'] = $v->icon;
                    $menuSubMenuRoutes[$v->parentId][] = $v->url;
                }
            }
        }
        View::share(compact('serializeMenus','menuSubMenuRoutes'));
    }
}
