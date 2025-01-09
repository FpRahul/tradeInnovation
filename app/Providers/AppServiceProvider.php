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
                    //Check if it is sub menu or sub sub menu
                    $checkLinkedMenus = Menu::find($v->parentId);
                    if($checkLinkedMenus->parentId>0){
                        //it is sub sub menu
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['url'] = $v->url;
                        $serializeMenus[$checkLinkedMenus->parentId]['subSubMenu'][$checkLinkedMenus->id][$v->id]['icon'] = $v->icon;
                    }else{
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['name'] = $v->menuName;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['url'] = $v->url;
                        $serializeMenus[$v->parentId]['subMenu'][$v->id]['icon'] = $v->icon;
                    }
                }

                //Grouping routes per menu for active class
                if($v->parentId>0){
                    $groupedRoutes = explode(',',$v->actionRoutes);
                    if(!empty($groupedRoutes)){
                        foreach($groupedRoutes as $groupedRoute){
                            $menuSubMenuRoutes[$v->parentId][] = $groupedRoute;
                        }
                    }
                }
                //End
            }
        }
        
        View::share(compact('serializeMenus','menuSubMenuRoutes'));
    }
}
