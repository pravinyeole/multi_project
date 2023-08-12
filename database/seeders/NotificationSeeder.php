<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('notifications')->insert([
            'notification_type' => 'email', 
            'notification_for' => 'user_invitation',
            'subject' => 'User Invitation',
            'cc_email' => 'support@coveragewizard.com',
            'start_label_1' => 'Dear',
            'notification_content_1' => 'You have been invited to join CW Team.',
            'notification_content_2' => 'Please click on below link to register with us.<br>
            Website link:-coveragewizard.com<br>
            You will be asked to provide a payment method before running your first set of audits. 
            Don’t forget to set a new password and enjoy using Coverage Wizard!',
            'end_label_1' => 'Thank You',
            'end_label_2' => 'The CW Team.',
            'active_yn' => 'Y'

        ]);
        DB::table('notifications')->insert([
            'notification_type' => 'email', 
            'notification_for' => 'user_mapped',
            'subject' => 'Added to Agency',
            'cc_email' => 'support@coveragewizard.com',
            'start_label_1' => 'Dear',
            'start_label_2' => 'user_name',
            'notification_content_1' => 'You are all set!
            <br><br>
            As you are already part of CW Team, so you have been added to {agency} Insurance Agency by Team Admin/Agency Admin.',
            'end_label_1' => 'Thank You.<br>
            The CW Team.',
            'active_yn' => 'Y'

        ]);
    }
}
