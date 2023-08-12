<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // add entry in parameters table
        // DB::table('parameters')->insert([
        //     ["parameter_key" =>"mail_driver","parameter_value"=>"smtp"],
        //     ["parameter_key" =>"mail_host","parameter_value"=>"email-smtp.us-east-2.amazonaws.com"],
        //     ["parameter_key" =>"mail_port","parameter_value"=>"587"],
        //     ["parameter_key" =>"mail_username","parameter_value"=>"AKIATZLVKCR2VR7TTD2E"],
        //     ["parameter_key" =>"mail_password","parameter_value"=>"BKHGyxX2NkFkvscs7dvCj81d4fvHayptaqJaA3xnkigq"],
        //     ["parameter_key" =>"mail_encryption","parameter_value"=>"tls"],
        //     ["parameter_key" =>"mail_from_address","parameter_value"=>"no-reply@coveragewizard.com"],
        //     ["parameter_key" =>"mail_from_name","parameter_value"=>"Coverage Wizard"],
        //     ["parameter_key" =>"aws_access_key_id","parameter_value"=>"GHGJtest"],
        //     ["parameter_key" =>"aws_secret_access_key","parameter_value"=>"FSDF123"],
        //     ["parameter_key" =>"aws_region","parameter_value"=>"us-east-2"],
        //     ["parameter_key" =>"aws_bucket","parameter_value"=>"s3"],
        //     ["parameter_key" =>"aws_bucket","parameter_value"=>"s3"],
        //     ["parameter_key" =>"admin_email","parameter_value"=>"kumudini.itworks@gmail.com"],
        //     ["parameter_key" =>"portal_version","parameter_value"=>"1.0.0"],
        // ]);

        // add entry in users table
        $user = \App\Models\User::create([
           'user_fname' => 'Dev',
           'user_lname' => 'Team',
           'email' => 'devteam@bigkittylabs.com',
           'password' => bcrypt('S$e79bEB'),
           'user_type'  => 'SA',
           'user_status' => 'Active',
           'created_at'=> round(microtime(true) * 1000),
           'modified_at'=> round(microtime(true) * 1000)
       ]);

        $user = \App\Models\User::create([
           'user_fname' => 'Admin',
           'email' => 'admin@coveragewizard.com',
           'password' => bcrypt('S$e79bEB'),
           'user_type'  => 'A',
           'user_status' => 'Active',
           'created_at'=> round(microtime(true) * 1000),
           'modified_at'=> round(microtime(true) * 1000)
       ]);

    }
}
