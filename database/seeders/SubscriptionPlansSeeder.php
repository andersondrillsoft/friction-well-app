<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Free',
                'monthly_price' => 0,
                'calculation_limit' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Premium',
                'monthly_price' => 2.99,
                'calculation_limit' => 25,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Premium+',
                'monthly_price' => 4.99,
                'calculation_limit' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
