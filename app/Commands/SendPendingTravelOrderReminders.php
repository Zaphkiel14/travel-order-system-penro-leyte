<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\SelectModel;
use App\Services\EmailService;

class SendPendingTravelOrderReminders extends BaseCommand
{
    protected $group       = 'Email';
    protected $name        = 'email:send-pending-tos';
    protected $description = 'Sends pending travel order reminder emails to all responsible users';

    public function run(array $params)
    {
        CLI::write('Fetching pending travel order recipients...', 'yellow');

        $model = new SelectModel();
        $recipients = $model->getAllPendingReminderRecipients();

        if (empty($recipients)) {
            CLI::write('No pending travel order reminders found.', 'green');
            return;
        }

        $emailService = new EmailService(); // adjust if using Services::emailService()

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $row) {

            $to = $row['email'] ?? null;

            if (!$to) {
                CLI::write("Skipped: missing email for {$row['full_name']}", 'red');
                $failed++;
                continue;
            }

            $result = $emailService->sendPendingTravelOrderEmail(
                $to,
                $row['full_name'],
                $row['position'],
                $row['managed_unit_div_org'],
                $row['pending_count']
            );

            if ($result) {
                CLI::write("Sent to: {$to}", 'green');
                $sent++;
            } else {
                CLI::write("Failed to send to: {$to}", 'red');
                $failed++;
            }
        }

        CLI::newLine();
        CLI::write("DONE", 'yellow');
        CLI::write("Sent: {$sent}", 'green');
        CLI::write("Failed: {$failed}", 'red');
    }
}