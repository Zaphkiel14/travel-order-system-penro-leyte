<?php

namespace App\Services;

use App\Models\TravelOrderModel;

class PrintService
{
    protected $travelOrderModel;

    public function __construct()
    {
        $this->travelOrderModel = new TravelOrderModel();
    }
    /**
     * Generates print preview for a PAR
     *
     * @param int $par_id
     * @return string Rendered HTML for print preview
     */
    // public function previewPAR(int $par_id)
    // {
    //     $par = $this->parModel->printPAR($par_id);

    //     if (!$par) {
    //         return "<h3>No PAR found for ID: {$par_id}</h3>";
    //     }

    //     return view('template/PAR_form', [
    //         'par' => $par
    //     ]);
    // }


    public function previewPrintTO(int $travel_order_id)
    {
        $travel_order = $this->travelOrderModel->printTO($travel_order_id);
        if (!$travel_order) {
            return "<h3>No Travel Order found for ID: {$travel_order_id}</h3>";
        }

        return view('templates/travel_order_form', [
            'travel_order' => $travel_order
        ]);
    }
}
