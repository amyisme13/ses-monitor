<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email to the given email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = explode(',', $this->argument('email'));

        if (count($email) > 0) {
            $this->info('Sending email to ' . $this->argument('email'));

            Mail::to($email)->send(new TestMail());
        }

        return 0;
    }
}
