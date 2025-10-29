<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_statuses')->updateOrInsert(
            ['code' => 'rejected'],
            [
                'name' => 'Pagamento Recusado',
                'code' => 'rejected',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
