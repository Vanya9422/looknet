<?php

namespace App\Console\Commands;

use App\Models\Admin\Commercial\CommercialUsers;
use App\Models\Admin\Support\Ticket;
use App\Models\Payment\Subscription;
use App\Models\Products\Advertise;
use App\Models\Chat\Conversation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info('Тестовая задача выполнена!');
//        $arr = [
//            ['gep_up' => 5, 'days' => 10],
//            ['gep_up' => 14, 'days' => 20],
//            ['gep_up' => 9, 'days' => 15],
//            ['gep_up' => 10, 'days' => 15],
//            ['gep_up' => 20, 'days' => 70],
//        ];
//
//        foreach ($arr as $item) {
//            $ups = 0;
//
//            $id = rand(1, 140);
//
//            for ($i = 0; $i < $item['gep_up']; $i++) {
//                $ups += (int)round($item['days'] / $item['gep_up']);
//                DB::table('advertise_gep_ups')->insert([
//                    'up_date' => \Carbon\Carbon::now()->addDays($ups),
//                    'advertise_id' => $id,
//                ]);
//            }
//        }


//        Subscription::create([
//            'stripe_id' => Str::ulid(),
//            'status' => true,
//            'auto_renewal' => true,
//            'payload' => [
//                'ulid' => Str::ulid()
//            ],
//            'owner_id' => 1,
//            'owner_type' => (new Advertise())->getMorphClass(),
//            'plan_id' => 1,
//            'plan_type' => (new CommercialUsers())->getMorphClass(),
//            'expired_top_days' => Carbon::now()->addDays(7)
//        ]);

//        $plan = CommercialUsers::find(3);
//
//        Subscription::create([
//            'stripe_id' => Str::ulid(),
//            'status' => true,
//            'auto_renewal' => true,
//            'payload' => [
//                'ulid' => Str::ulid()
//            ],
//            'owner_id' => 1,
//            'owner_type' => (new Advertise())->getMorphClass(),
//            'plan_id' => 3,
//            'plan_type' => (new CommercialUsers())->getMorphClass(),
//            'expired_period_gep_up' => Carbon::now()->addDays($plan->period_days),
//        ]);
//
//        Subscription::create([
//            'stripe_id' => Str::ulid(),
//            'status' => true,
//            'auto_renewal' => true,
//            'payload' => [
//                'ulid' => Str::ulid()
//            ],
//            'owner_id' => 1,
//            'owner_type' => (new Advertise())->getMorphClass(),
//            'plan_id' => 1,
//            'plan_type' => (new CommercialUsers())->getMorphClass(),
//            'expired_period_gep_up' => Carbon::now()->addDays(7),
//            'expired_vip_days',
//            'expired_top_days',
//        ]);


    }

    public function updateFiltersProducts()
    {
        Advertise::query()->with('answers')->chunkById(100, function ($advertises) {
            foreach ($advertises as $advertise) {
                $advertise->answers->pluck('id')->toArray();
                $advertise->answer_ids = $advertise->answers->pluck('id')->toArray();
                $advertise->save();
            }
        });
    }

    public function createConversation(): void
    {
        $participants = [User::find(5), User::find(4)];
//        \Chat::getInstance()
//            ->setStarter(User::find(4))
//            ->createConversation($participants, [], 11);

        \Chat::getInstance()
            ->conversation(Conversation::find(25))
            ->message('message')
            ->attachFiles([])
            ->from(User::find(5))
            ->send();

        \Chat::getInstance()
            ->setStarter(User::find(2))
            ->createConversation($participants, [], null, true)
            ->ticket()
            ->associate(Ticket::find(1))
            ->save();

//        \Chat::getInstance()
//            ->conversation(Conversation::find(24))
//            ->serviceInstances(ChatServiceNamesEnum::CONVERSATIONS_SERVICE)
//            ->changeModerator(User::find(2), User::find(22));

//        \Chat::getInstance()
//            ->conversation(Conversation::find(24))
//            ->serviceInstances(ChatServiceNamesEnum::CONVERSATIONS_SERVICE)
//            ->setParticipant(User::find(2))
//            ->readAll();
    }
}
