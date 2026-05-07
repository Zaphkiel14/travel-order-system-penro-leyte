<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TravelOrderModel;
use App\Models\SelectModel;
use App\Services\GoogleDriveService;



class DashboardController extends BaseController
{
    // public function index()
    // {
    //     $model = new SelectModel();

    //     $data = [
    //         'title' => 'Travel Order | Dashboard',
    //         'page' => 'Dashboard',
    //         'newTravelOrderNumber' => $model->generateNextTravelOrderID(),
    //         'divunits' => $model->selectDivisionUnit()
    //     ];
    //     $role = session()->get('role');
    //     if ($role === 'admin') {
    //         return view('admin/dashboard', $data);
    //     } else if ($role === 'records') {
    //         return view('records/dashboard', $data);
    //     } else if ($role === 'penro') {
    //         return view('organization/dashboard', $data);
    //     } else if ($role === 'division') {
    //         return view('division/dashboard', $data);
    //     } else if ($role === 'unit') {
    //         return view('unit/dashboard', $data);
    //     } else if ($role === 'employee') {
    //         return view('client/dashboard', $data);
    //     } else {
    //         return redirect()->to('/login');
    //     }
    // }

    // public function travelOrderDetails(int $travelOrderId)
    // {
    //     if (!$this->request->isAJAX()) {
    //         return $this->response->setStatusCode(403)->setBody('Forbidden');
    //     }

    //     $userId = session()->get('user_id');
    //     $model  = new TravelOrderModel();
    //     $order  = $model->getTravelOrderDetails($travelOrderId);

    //     // Security: only owner can view their own travel order
    //     if (!$order || (int)$order['user_id'] !== (int)$userId) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Travel order not found.'
    //         ])->setStatusCode(404);
    //     }

    //     return $this->response->setJSON([
    //         'success' => true,
    //         'data'    => $order,
    //     ]);
    // }
}
