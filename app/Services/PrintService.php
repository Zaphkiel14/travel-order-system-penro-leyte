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
     * Generates print preview for a Travel Order
     *
     * @param int $travel_order_id
     * @return string Rendered HTML for print preview
     */
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
