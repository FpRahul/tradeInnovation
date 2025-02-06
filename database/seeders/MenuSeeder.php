<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert(array(
            array('id' => '1','parentId' => '0','menuName' => 'Dashboard','icon' => 'dashboard-icon','url' => 'dashboard','actionRoutes' => 'dashboard','sequence' => '1','created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','parentId' => '0','menuName' => 'Users','icon' => 'user-icon','url' => 'javascript:void(0);','actionRoutes' => 'users.listing,users.adduser,client.listing,users.addclient,associate.listing,users.addassociate','sequence' => '2','created_at' => NULL,'updated_at' => NULL),
            array('id' => '3','parentId' => '2','menuName' => 'Internal Users','icon' => NULL,'url' => 'users.listing','actionRoutes' => 'users.listing,users.adduser','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','parentId' => '2','menuName' => 'Clients','icon' => NULL,'url' => 'client.listing','actionRoutes' => 'client.listing,users.addclient','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','parentId' => '2','menuName' => 'Associates','icon' => NULL,'url' => 'associate.listing','actionRoutes' => 'associate.listing,users.addassociate','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '6','parentId' => '0','menuName' => 'Leads','icon' => 'leads-icon','url' => 'javascript:void(0);','actionRoutes' => 'leads.index,leads.add,leads.assign,leads.archive,leads.quote,leads.logs,leads.delete','sequence' => '3','created_at' => NULL,'updated_at' => NULL),
            array('id' => '7','parentId' => '0','menuName' => 'Services','icon' => 'setting-icon','url' => 'services.index','actionRoutes' => 'services.index,service.add,service.status,services.subService.add','sequence' => '5','created_at' => NULL,'updated_at' => NULL),
            array('id' => '8','parentId' => '0','menuName' => 'Panel Settings','icon' => 'setting-icon','url' => 'javascript:void(0);','actionRoutes' => 'settings.roles,settings.addrole','sequence' => '6','created_at' => NULL,'updated_at' => NULL),
            array('id' => '9','parentId' => '8','menuName' => 'Roles','icon' => NULL,'url' => 'settings.roles','actionRoutes' => 'settings.roles,settings.addrole','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '10','parentId' => '2','menuName' => 'Settings','icon' => NULL,'url' => 'javascript:void(0);','actionRoutes' => 'professions.index,professions.add,professions.status,incorporation.index,incorporations.add,incorporations.status,referral.index,referral.add,referral.status','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '11','parentId' => '10','menuName' => 'Professions','icon' => NULL,'url' => 'professions.index','actionRoutes' => 'professions.index,professions.add,professions.status','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '12','parentId' => '10','menuName' => 'Incorporation type','icon' => NULL,'url' => 'incorporation.index','actionRoutes' => 'incorporation.index,incorporations.add,incorporations.status','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '13','parentId' => '10','menuName' => 'Referral partner','icon' => NULL,'url' => 'referral.index','actionRoutes' => 'referral.index,referral.add,referral.status','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '14','parentId' => '8','menuName' => 'Logs','icon' => NULL,'url' => 'logs.index','actionRoutes' => 'logs.index,logs.view','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '15','parentId' => '6','menuName' => 'Listing','icon' => NULL,'url' => 'leads.index','actionRoutes' => 'leads.index,leads.add,leads.assign,leads.archive,leads.quote,leads.logs,leads.delete','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '16','parentId' => '6','menuName' => 'Logs','icon' => NULL,'url' => 'leadLogs.index','actionRoutes' => 'leadLogs.index','sequence' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '17','parentId' => '0','menuName' => 'Tasks','icon' => 'leads-icon','url' => 'task.index','actionRoutes' => 'task.index,task.log,task.detail,task.followup','sequence' => '4','created_at' => NULL,'updated_at' => NULL)
        ));
    }
}
