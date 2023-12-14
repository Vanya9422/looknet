<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAdvertisePublished extends Command
{
    protected $signature = 'publications:update-status';


    protected $description = 'если прошло 5 минут после добавлении публикации меняеть published на true';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Обновите статус публикаций, у которых прошло более 5 минут с момента создания
        \DB::table('advertises')
            ->where('created_at', '<=', now()->subMinutes(5))
            ->where('published', false)
            ->update(['published' => true]);

        $this->info('Publication statuses updated successfully.');
    }
}
