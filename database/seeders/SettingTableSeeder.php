<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::create([
            'name' => 'phone',
            'value' => '718-383-5372'
        ]);

        Setting::create([
            'name' => 'email',
            'value' => ' info@structurecompliance.com'
        ]);

        Setting::create([
            'name' => 'facebook',
            'value' => '#'
        ]);

        Setting::create([
            'name' => 'twitter',
            'value' => '#'
        ]);

        Setting::create([
            'name' => 'linkedin',
            'value' => '#'
        ]);
    }
}
