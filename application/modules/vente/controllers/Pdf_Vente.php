<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_Vente extends MY_Controller {


    public function __construct() {

        parent::__construct();
        
           
        $this->Is_Connected();
    }

    public function Is_Connected()
    {

        if (empty($this->session->userdata('SUPERBAT_ID_USER')))
        {
            redirect(base_url('Login/'));
        }
    }

    public function index(){




        $this->generate_with_helper();
    }


    public function pdf_facture()
    {
        // Load the PDF library
         $this->load->helper('pdf');
        
        $html = 'IDENTIFICATION DU VENDEUR                                          IDENTIFICATION DU CLIENT</h1>';
        
        $html .= '<h1>Facture N0 #12345</h1>';
        $html .= '<p>Date: ' . date('Y-m-d') . '</p>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th></tr>';
        $html .= '<tr><td>Product 1</td><td>2</td><td>$10.00</td><td>$20.00</td></tr>';
        $html .= '<tr><td>Product 2</td><td>1</td><td>$15.00</td><td>$15.00</td></tr>';
        $html .= '</table>';
        $html .= '<h3>Total Amount: $35.00</h3>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Output the PDF
        $pdf->Output('invoice.pdf', 'F'); // 'I' sends to browser, 'D' forces download, 'F' saves to file
    }






    public function generate_with_helper()
{
    require_once APPPATH . 'libraries/tcpdf/tcpdf.php';
    
    // Créer l'instance TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // ========== DÉSACTIVER L'EN-TÊTE PAR DÉFAUT ==========
    $pdf->setPrintHeader(false);  // Désactive l'en-tête
    $pdf->setPrintFooter(false);  // Désactive le pied de page (optionnel)
    
    // ========== CONFIGURATION ==========
    $pdf->SetCreator('Superbat System');
    $pdf->SetAuthor('Superbat');
    $pdf->SetTitle('Facture');
    $pdf->SetSubject('Facture PDF');
    
    // Ajouter une page
    $pdf->AddPage();
    
    // ========== CONTENU PERSONNALISÉ ==========
    $html = '
    <style>
        .facture-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .vendeur, .client {
            width: 48%;
        }
        .table-container {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
    
    <div class="facture-header">
        <h1>FACTURE N° #12345</h1>
    </div>
    
    <div class="info-container">
        <div class="vendeur">
            <h3>IDENTIFICATION DU VENDEUR</h3>
            <p><strong>SUPERBAT Entreprise</strong><br>
            Adresse: QUARTIER INDUSTRIEL<br>
            Ville: BUJUMBURA<br>
            Téléphone: 71246149<br>
            Email: contact@superbat.com<br>
            </p>
        </div>
        
        <div class="client">
            <h3>IDENTIFICATION DU CLIENT</h3>
            <p><strong>NTWARI RAOUL</strong><br>
            Adresse: BWIZA No 46<br>
            
            Email: rantwari@gmail.com</p>
        </div>
    </div>
    
    <p><strong>Date: ' . date('Y-m-d') . '</strong></p>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product 1</td>
                    <td>2</td>
                    <td>10.00 BIF </td>
                    <td>20.00 BIF </td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td>1</td>
                    <td>15.00 BIF </td>
                    <td>15.00 BIF </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="total">
        <p>Total Amount: 35.00 BIF</p>
    </div>
    ';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Afficher dans le navigateur
    $pdf->Output('facture.pdf', 'I');
}
    
    public function generate_with_helperold()
    {
        $this->load->helper('pdf');

         // Add content
         $html = '<h1>Facture N0 #12345</h1>';
        $html .= 'IDENTIFICATION DU VENDEUR                                          IDENTIFICATION DU CLIENT</h1>';
        
       
        $html .= '<p>Date: ' . date('Y-m-d') . '</p>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th></tr>';
        $html .= '<tr><td>Product 1</td><td>2</td><td>$10.00</td><td>$20.00</td></tr>';
        $html .= '<tr><td>Product 2</td><td>1</td><td>$15.00</td><td>$15.00</td></tr>';
        $html .= '</table>';
        $html .= '<h3>Total Amount: $35.00</h3>';
        
        
        
        generate_pdf($html, 'fichier.pdf', 'D'); // Force download
    }
}
