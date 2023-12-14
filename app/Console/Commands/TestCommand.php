<?php

namespace App\Console\Commands;

use App\Contracts\UserSettingsCodeConfirmationHandlerInterface;
use App\Enums\Mails\SendCodeContentTextEnum;
use App\Models\User;
use App\Notifications\V1\EmailConfirmation;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

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

        app(UserSettingsCodeConfirmationHandlerInterface::class)->handle(
            User::find(1),
            [
                'code_type' => SendCodeContentTextEnum::CHANGE_EMAIL_OR_PHONE_SETTINGS_CONFIRMATION_CODE,
                'event' => 'isValidPhone',
                'confirmation_type' => EmailConfirmation::class,
                'confirmation_auth' => 'grigoryan366@gmail.com',
                'field' => 'email',
                'email' => 'grigoryan366@gmail.com',
            ]
        );

        return Command::SUCCESS;
    }
}
