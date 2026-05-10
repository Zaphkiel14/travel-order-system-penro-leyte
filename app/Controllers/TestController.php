<?php

namespace App\Controllers;

use Config\Services;
use App\Services\GoogleDriveService;
use App\Services\EmailService;
use App\Services\QrService;

class TestController extends BaseController
{
    public function index(): string
    {
        return view('dashboard');
    }
    public function testEmailtest()
    {
        $email = \Config\Services::email();
        dd(config('Email'));
        $email->setTo('vincenteleazar.uykieng@evsu.edu.ph');
        $email->setSubject('Test');
        $email->setMessage('Hello world');
        if ($email->send()) {
            echo 'Sent';
        } else {
            echo $email->printDebugger(['headers']);
        }
    }
    public function testEmail()
    {
        $request = service('request');
        $name        = trim($request->getPost('name'));
        $userEmail   = trim($request->getPost('email'));
        $subject     = trim($request->getPost('subject')) ?: 'Contact Inquiry';
        $messageText = trim($request->getPost('message'));
        $email = new EmailService();
        $email->sendEmail($userEmail, $subject, $messageText, null, $name);
        return "Email sent successfully!";
    }
    public function testDashboard(): string
    {
        $data = ['title' => 'ICT Inventory | Dashboard', 'page' => 'Dashboard',];
        return view('tests/test', $data);
    }




    public function testReminder()
    {
            $emailService = new EmailService();
            $emailService->sendPendingTravelOrderEmail(
                'vincenteleazar.uykieng@evsu.edu.ph',
                'test name',
                'test position',
                'test unit/div/org',
                'test count');
    }

}
