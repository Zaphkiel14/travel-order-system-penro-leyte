<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SelectModel;

class AnalyticsController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->renderError($this->errorHandler->unauthorized());
        }

        if (session()->get('role') !== "admin") {
            return $this->renderError($this->errorHandler->forbidden('Admins only.'));
        }

        $select = new SelectModel();

        $stats = $select->getAllTravelOrderStats();

        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');

        $chartStats = $select->getTravelOrderStatsByDate($month, $year);

        $labels = [];
        $pending = [];
        $approved = [];
        $rejected = [];
        $total = [];

        foreach ($chartStats as $row) {
            $labels[]   = $row['date'];
            $pending[]  = $row['pending'];
            $approved[] = $row['approved'];
            $rejected[] = $row['rejected'];
            $total[]    = $row['total'];
        }

        return view('admin/analytics', [
            'title' => 'Travel Order | Analytics',
            'page'  => 'Analytics',
            'stats' => $stats,
            'month' => $month,
            'year'  => $year,
            'labels'   => $labels,
            'pending'  => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'total'    => $total,
        ]);
    }
}