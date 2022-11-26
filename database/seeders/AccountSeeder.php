<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        $account = new Account();
        $account->account_no = $account_no;
        $account->name = 'Sales Account';
        $account->initial_balance = 0;
        $account->amount_added = 0;
        $account->total_balance = 0;
        $account->note = 'For Sales';
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();
    }
}
