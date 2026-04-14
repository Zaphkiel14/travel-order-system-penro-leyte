<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
<<<<<<< Updated upstream
    {    
=======
    {
      $model = new TravelOrderModel();
>>>>>>> Stashed changes
        $data = [
            'title' => 'ICT Inventory | Dashboard',
            'page' => 'Dashboard',
              'travelOrders' => $model->displayAllTravelOrders(),
        ];
        $role = session()->get('role');
        if($role === 'admin'){
            return view('admin/dashboard', $data);
        }
<<<<<<< Updated upstream
        return view('client/dashboard', $data);
=======
    }


    public function createTravelOrder()
    {

        $folderId = getenv('drive.folderId');
        // Get user ID
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('toast', [
                'type' => 'warning',
                'message' => 'User not logged in.'
            ]);
        }

        // Generate new travel order number
        $newTravelOrderNumber = (new SelectModel())->generateNextInventoryID();

        // Initialize GDriveService
        $drive = new GoogleDriveService();
        try {
            $drive->setUser($userId);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'type' => 'warning',
                'message' => 'Google Drive not connected.'
            ]);
        }

        // TEMPORARY DEBUG - remove after fixing
$files = $this->request->getFiles();
log_message('debug', 'FILES RECEIVED: ' . print_r(array_map(function($f) {
    if (is_array($f)) {
        return array_map(fn($ff) => [
            'name'    => $ff->getClientName(),
            'size'    => $ff->getSize(),
            'valid'   => $ff->isValid(),
            'error'   => $ff->getError(),
        ], $f);
    }
    return [
        'name'  => $f->getClientName(),
        'size'  => $f->getSize(),
        'valid' => $f->isValid(),
        'error' => $f->getError(),
    ];
}, $files), true));

        $requestMemo = $this->request->getFile('request_memo');
        $requestMemoFileId = null;
        if ($requestMemo && $requestMemo->isValid()) {
            $content = file_get_contents($requestMemo->getTempName());
            $extension = $requestMemo->getExtension();
            $fileName = $newTravelOrderNumber . '_REQUEST_MEMO.' . $extension;
            $requestMemoFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $requestMemo->getMimeType(),
                '1EzPQVobKIZy7NvndgDzTObZ0Lj3UBuEP'
            );
        }
        log_message('debug', 'Request Memo File ID: ' . $requestMemoFileId);

        $specialOrder = $this->request->getFile('special_order');
        $specialOrderFileId = null;
        if ($specialOrder && $specialOrder->isValid()) {
            $content = file_get_contents($specialOrder->getTempName());
            $extension = $specialOrder->getExtension();
            $fileName = $newTravelOrderNumber . '_SPECIAL_ORDER.' . $extension;
            $specialOrderFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $specialOrder->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Special Order File ID: ' . $specialOrderFileId);

        $requestLetter = $this->request->getFile('request_letter');
        $requestLetterFileId = null;
        if ($requestLetter && $requestLetter->isValid()) {
            $content = file_get_contents($requestLetter->getTempName());
            $extension = $requestLetter->getExtension();
            $fileName = $newTravelOrderNumber . '_REQUEST_LETTER.' . $extension;
            $requestLetterFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $requestLetter->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Request Letter File ID: ' . $requestLetterFileId);

        $invitationLetter = $this->request->getFile('invitation_letter');
        $invitationLetterFileId = null;
        if ($invitationLetter && $invitationLetter->isValid()) {
            $content = file_get_contents($invitationLetter->getTempName());
            $extension = $invitationLetter->getExtension();
            $fileName = $newTravelOrderNumber . '_INVITATION_LETTER.' . $extension;
            $invitationLetterFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $invitationLetter->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Invitation Letter File ID: ' . $invitationLetterFileId);


        $trainingNotification = $this->request->getFile('training_notification');
        $trainingNotificationFileId = null;
        if ($trainingNotification && $trainingNotification->isValid()) {
            $content = file_get_contents($trainingNotification->getTempName());
            $extension = $trainingNotification->getExtension();
            $fileName = $newTravelOrderNumber . '_TRAINING_NOTIFICATION.' . $extension;
            $trainingNotificationFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $trainingNotification->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Training Notification File ID: ' . $trainingNotificationFileId);

        $meetingNotice = $this->request->getFile('meeting_notice');
        $meetingNoticeFileId = null;
        if ($meetingNotice && $meetingNotice->isValid()) {
            $content = file_get_contents($meetingNotice->getTempName());
            $extension = $meetingNotice->getExtension();
            $fileName = $newTravelOrderNumber . '_MEETING_NOTICE.' . $extension;
            $meetingNoticeFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $meetingNotice->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Meeting Notice File ID: ' . $meetingNoticeFileId);

        $conferenceProgram = $this->request->getFile('conference_program');
        $conferenceProgramFileId = null;
        if ($conferenceProgram && $conferenceProgram->isValid()) {
            $content = file_get_contents($conferenceProgram->getTempName());
            $extension = $conferenceProgram->getExtension();
            $fileName = $newTravelOrderNumber . '_CONFERENCE_PROGRAM.' . $extension;
            $conferenceProgramFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $conferenceProgram->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Conference Program File ID: ' . $conferenceProgramFileId);

        $otherDocument = $this->request->getFile('other_document');
        $otherDocumentFileId = null;
        if ($otherDocument && $otherDocument->isValid()) {
            $content = file_get_contents($otherDocument->getTempName());
            $extension = $otherDocument->getExtension();
            $fileName = $newTravelOrderNumber . '_OTHER_DOCUMENT.' . $extension;
            $otherDocumentFileId = $drive->uploadFileFromContent(
                $content,
                $fileName,
                $otherDocument->getClientMimeType(),
                $folderId
            );
        }
        log_message('debug', 'Other Document File ID: ' . $otherDocumentFileId);


        $model = new TravelOrderModel();
        $data = [
            // Travel Order Number
            'travel_order_number' => $newTravelOrderNumber,
            // Personal Information
            'persons' => $this->request->getPost('persons'),
            // Travel Details
            'departure_date' => $this->request->getPost('departure_date'),
            'arrival_date' => $this->request->getPost('arrival_date'),
            'destination' => $this->request->getPost('destination'),
            'travel_purpose' => $this->request->getPost('travel_purpose'),
            // Supporting Documents
            'request_memo' => $requestMemoFileId ?? null,
            'special_order' => $specialOrderFileId ?? null,
            'request_letter' => $requestLetterFileId ?? null,
            'invitation_letter' => $invitationLetterFileId ?? null,
            'training_notification' => $trainingNotificationFileId ?? null,
            'meeting_notice' => $meetingNoticeFileId ?? null,
            'conference_program' => $conferenceProgramFileId ?? null,
            'other_document' => $otherDocumentFileId ?? null
        ];

        $result = $model->insertTravelOrder(
            $data['travel_order_number'],
            $data['persons'],
            $data['departure_date'],
            $data['arrival_date'],
            $data['destination'],
            $data['travel_purpose'],
            $data['request_memo'],
            $data['special_order'],
            $data['request_letter'],
            $data['invitation_letter'],
            $data['training_notification'],
            $data['meeting_notice'],
            $data['conference_program'],
            $data['other_document']
        );
        return redirect()->back()->with('toast', [
            'type' => $result ? 'success' : 'danger',
            'message' => $result
                ? "'{$newTravelOrderNumber}' created successfully."
                : "Failed to create '{$newTravelOrderNumber}'."
        ]);

>>>>>>> Stashed changes
    }


}
