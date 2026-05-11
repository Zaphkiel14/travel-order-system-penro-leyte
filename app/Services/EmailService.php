<?php

namespace App\Services;

use Config\Services;


class EmailService
{
    /**
     * Generic email sender
     * 
     * @param string $to       Recipient email
     * @param string $subject  Email subject
     * @param string $view     View file under app/Views/emails (without .php)
     * @param array  $data     Data to pass to the email view
     */
    protected $email;
    public function __construct()
    {
        $this->email = Services::email(); // Loads Email library
    }
    /**
     * Send email via SMTP
     *
     * @param string|array $to      Recipient email or array of emails
     * @param string       $subject Email subject
     * @param string       $message HTML or plain text message
     * @param string|null  $from    Optional: from email
     * @param string|null  $fromName Optional: sender name
     * @return bool
     */
    public function sendEmail($to, string $subject, string $message, $from = null, $fromName = null): bool
    {
        $this->email->clear();
        // Use the SMTPUser as sender if not specified
        $this->email->setFrom(
            $from ?? config('Email')->SMTPUser,
            $fromName ?? 'PENRO TRAVEL ORDER SYSTEM'
        );
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        // Required for Gmail
        $this->email->setNewline("\r\n");
        $this->email->setCRLF("\r\n");
        try {
            return $this->email->send();
        } catch (\Exception $e) {
            log_message('error', 'EmailServices failed: ' . $e->getMessage());
            return false;
        }
    }


    public function sendPendingTravelOrderEmail(string $to, string $full_name, string $position, string $managed_unit_div_org, string $pending_count): bool
    {
        log_message('debug', '[EmailService] sendPending called');
        log_message('debug', '[EmailService] Parameters: to=' . $to . ', name=' . $full_name);

        $email = Services::email();
        $email->setFrom('no-reply@penr-travel-order-system.com', 'Travel Order System - PENRO Leyte');

        $message = view('emails/pending_reminder', [
            'full_name' => $full_name,
            'position' => $position,
            'managed_unit_div_org' => $managed_unit_div_org,
            'pending_count' => $pending_count,
        ]);

        $email->setTo($to);
        $email->setSubject('Pending Travel Order Reminder — PENRO Leyte');
        $email->setMessage($message);
        $email->setNewline("\r\n");
        $email->setCRLF("\r\n");

        log_message('debug', '[EmailService] Attempting to send pending travel order reminder email...');
        if ($email->send()) {
            log_message('debug', '[EmailService] Pending travel order reminder email sent successfully to ' . $to);
            return true;
        }

        log_message('error', '[EmailService] Pending travel order reminder email failed: ' . $email->printDebugger(['headers', 'subject', 'body']));
        return false;
    }
}
