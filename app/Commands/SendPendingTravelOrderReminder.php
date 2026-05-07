<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class SendPendingTravelOrderReminder extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'travel:reminder';
    protected $description = 'Send pending travel order reminder emails';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $email = Services::email();
        $today = date('Y-m-d');
        $twoDays = date('Y-m-d', strtotime('+2 days'));
        $twoDayReminders = $db->table('travel_orders')
            ->where('scheduled_date', $twoDays)
            ->where('reminder_2day_sent', 0)
            ->get()->getResult();
        foreach ($twoDayReminders as $item) {
            $email->setTo($item->technician_email);
            $email->setSubject('Travel Order Reminder - 2 Days Left');
            $email->setMessage("
                <h3>Maintenance Reminder</h3>
                <p>Equipment: {$item->property_no}</p>
                <p>Scheduled Date: {$item->scheduled_date}</p>
                <p>This maintenance is scheduled in 2 days.</p>
            ");
            if ($email->send()) {
                $db->table('maintenances')
                    ->where('maintenance_id', $item->maintenance_id)
                    ->update(['reminder_2day_sent' => 1]);
            }
            $email->clear();
        }
        // Exact Day Reminder
        $exactReminders = $db->table('maintenances')
            ->where('scheduled_date', $today)
            ->where('reminder_exact_sent', 0)
            ->get()->getResult();
        foreach ($exactReminders as $item) {
            $email->setTo($item->technician_email);
            $email->setSubject('Maintenance Reminder - Today');
            $email->setMessage("
                <h3>Maintenance Reminder</h3>
                <p>Equipment: {$item->property_no}</p>
                <p>Scheduled Today: {$item->scheduled_date}</p>
                <p>This maintenance is scheduled for today.</p>
            ");
            if ($email->send()) {
                $db->table('maintenances')
                    ->where('maintenance_id', $item->maintenance_id)
                    ->update(['reminder_exact_sent' => 1]);
            }
            $email->clear();
        }
        CLI::write('Maintenance reminders processed successfully.', 'green');
    }
}
