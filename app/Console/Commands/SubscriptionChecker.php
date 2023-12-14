<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SubscriptionChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'следит за сроками подписок чтобы отключить подписку если срок прошел';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('subscriptions')
            ->where('status', '=', true)
            ->orWhere(function($q) { // gep_up
                $q->where('expired_period_gep_up', '<=', Carbon::now());
                $q->whereNull('expired_vip_days');
                $q->whereNull('expired_top_days');
            })
            ->orWhere(function($q) { // vio and gep_up
                $q->where('expired_period_gep_up', '<=', Carbon::now());
                $q->where('expired_vip_days', '<=', Carbon::now());
                $q->whereNull('expired_top_days');
            })
            ->orWhere(function($q) { // top and gep_up
                $q->where('expired_period_gep_up', '<=', Carbon::now());
                $q->where('expired_top_days', '<=', Carbon::now());
                $q->whereNull('expired_vip_days');
            })
            ->orWhere(function($q) { // vip and top
                $q->where('expired_vip_days', '<=', Carbon::now());
                $q->where('expired_top_days', '<=', Carbon::now());
                $q->whereNull('expired_period_gep_up');
            })
            ->orWhere(function($q) { // vip
                $q->where('expired_vip_days', '<=', Carbon::now());
                $q->whereNull('expired_top_days');
                $q->whereNull('expired_period_gep_up');
            })
            ->orWhere(function($q) { // top
                $q->where('expired_top_days', '<=', Carbon::now());
                $q->whereNull('expired_vip_days');
                $q->whereNull('expired_period_gep_up');
            })
            ->orWhere(function($q) { // full
                $q->where('expired_vip_days', '<=', Carbon::now());
                $q->where('expired_top_days', '<=', Carbon::now());
                $q->where('expired_period_gep_up', '<=', Carbon::now());
            })
            ->update(['status' => false]);

        return 0;
    }
}
