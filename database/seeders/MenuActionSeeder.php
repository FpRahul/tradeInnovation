<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MenuActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_actions')->insert(array(
            array('id' => '1','menuId' => '3','actionName' => 'Add / Edit','route' => 'users.adduser','created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','menuId' => '3','actionName' => 'Update Status','route' => 'users.status','created_at' => NULL,'updated_at' => NULL),
            //array('id' => '3','menuId' => '3','actionName' => 'Delete','route' => 'users.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','menuId' => '4','actionName' => 'Add / Edit','route' => 'users.addclient','created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','menuId' => '4','actionName' => 'Update Status','route' => 'client.status','created_at' => NULL,'updated_at' => NULL),
            //array('id' => '6','menuId' => '4','actionName' => 'Delete','route' => 'users.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '7','menuId' => '5','actionName' => 'Add / Edit','route' => 'users.addassociate','created_at' => NULL,'updated_at' => NULL),
            array('id' => '8','menuId' => '5','actionName' => 'Update Status','route' => 'associate.status','created_at' => NULL,'updated_at' => NULL),
            //array('id' => '9','menuId' => '5','actionName' => 'Delete','route' => 'users.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '10','menuId' => '15','actionName' => 'Add / Edit','route' => 'leads.add','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '11','menuId' => '15','actionName' => 'Assign','route' => 'leads.assign','created_at' => NULL,'updated_at' => NULL),
            array('id' => '12','menuId' => '15','actionName' => 'Archive','route' => 'leads.archive','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '13','menuId' => '15','actionName' => 'Send Quote','route' => 'leads.quote','created_at' => NULL,'updated_at' => NULL),
            array('id' => '14','menuId' => '15','actionName' => 'Logs','route' => 'leads.logs','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '15','menuId' => '15','actionName' => 'Delete','route' => 'leads.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '16','menuId' => '7','actionName' => 'Add / Edit','route' => 'service.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '17','menuId' => '7','actionName' => 'Update Status','route' => 'service.status','created_at' => NULL,'updated_at' => NULL),
            array('id' => '18','menuId' => '7','actionName' => 'Delete','route' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '19','menuId' => '11','actionName' => 'Add / Edit','route' => 'professions.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '20','menuId' => '11','actionName' => 'Update Status','route' => 'professions.status','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '21','menuId' => '11','actionName' => 'Delete','route' => 'professions.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '22','menuId' => '12','actionName' => 'Add / Edit','route' => 'incorporations.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '23','menuId' => '12','actionName' => 'Update Status','route' => 'incorporations.status','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '24','menuId' => '12','actionName' => 'Delete','route' => 'incorporations.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '25','menuId' => '13','actionName' => 'Add / Edit','route' => 'referral.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '26','menuId' => '13','actionName' => 'Update Status','route' => 'referral.status','created_at' => NULL,'updated_at' => NULL),
            // array('id' => '27','menuId' => '13','actionName' => 'Delete','route' => 'referral.delete','created_at' => NULL,'updated_at' => NULL),
            array('id' => '28','menuId' => '14','actionName' => 'View','route' => 'logs.view','created_at' => NULL,'updated_at' => NULL),
            array('id' => '29','menuId' => '16','actionName' => 'View','route' => 'leadLogs.index','created_at' => NULL,'updated_at' => NULL),
            array('id' => '30','menuId' => '17','actionName' => 'Follow up','route' => 'task.followup','created_at' => NULL,'updated_at' => NULL),
            array('id' => '31','menuId' => '17','actionName' => 'Task Logs','route' => 'task.log','created_at' => NULL,'updated_at' => NULL),
            array('id' => '32','menuId' => '17','actionName' => 'Hold Task','route' => 'task.hold','created_at' => NULL,'updated_at' => NULL),
            array('id' => '33','menuId' => '18','actionName' => 'Add / Edit','route' => 'partner.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '34','menuId' => '18','actionName' => 'Update Status','route' => 'partner.status','created_at' => NULL,'updated_at' => NULL),
            array('id' => '35','menuId' => '19','actionName' => 'Add / Edit','route' => 'firm.add','created_at' => NULL,'updated_at' => NULL),
            array('id' => '36','menuId' => '19','actionName' => 'Update Status','route' => 'firm.status','created_at' => NULL,'updated_at' => NULL),
            array('id' => '37','menuId' => '15','actionName' => 'Invoice','route' => 'lead.invoice','created_at' => NULL,'updated_at' => NULL),
            array('id' => '38','menuId' => '20','actionName' => 'Payment','route' => 'lead.paymentStatus','created_at' => NULL,'updated_at' => NULL),
          ));
    }
}
