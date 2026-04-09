<?php

namespace App\Services;

use App\Models\PropertyAcknowledgementModel;

class PrintService
{
    protected $parModel;

    public function __construct()
    {
        $this->parModel = new PropertyAcknowledgementModel();
    }
    /**
     * Generates print preview for a PAR
     *
     * @param int $par_id
     * @return string Rendered HTML for print preview
     */
public function previewPAR(int $par_id)
{
    $par = $this->parModel->printPAR($par_id);

    if (!$par) {
        return "<h3>No PAR found for ID: {$par_id}</h3>";
    }

    return view('template/PAR_form', [
        'par' => $par
    ]);
}
}
