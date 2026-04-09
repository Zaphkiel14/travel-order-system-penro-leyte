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
            $fromName ?? 'DICT ICT INVENTORY SYSTEM'
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
}
