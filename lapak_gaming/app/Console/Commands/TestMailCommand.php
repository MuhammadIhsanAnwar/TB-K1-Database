<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test {email? : Email address to send test email to}';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';

        $this->info('Testing email configuration...');
        $this->info('Mail Mailer: ' . config('mail.default'));
        $this->info('Mail From: ' . config('mail.from.address'));
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('Sending test email to: ' . $email);

        try {
            Mail::raw('This is a test email from Lapak Gaming', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - Lapak Gaming');
            });

            $this->info('✓ Email sent successfully!');
            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error('✗ Email failed to send!');
            $this->error('Error: ' . $exception->getMessage());
            $this->error('Trace: ' . $exception->getTraceAsString());
            return self::FAILURE;
        }
    }
}
