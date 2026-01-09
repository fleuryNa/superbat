<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_Vente extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function pdf_facture($id)
    {
        $this->load->library('pdf');

        $pdf = new Pdf();
        $pdf->AddPage();

        $html = $this->load->view('Pdf_vente_view', [], true);
        $pdf->writeHTML($html);

        $pdf->Output('exemple.pdf', 'I');
    }
}
