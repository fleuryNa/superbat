<?php 
/**
 * Prime Entreprises Team
 */

class Mylibrary {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('email');
        $this->CI->load->library('upload');
        // $this->numfact();
    }

    public function upload_image($input_image_post)
    {
        $folder_location = './Factures/'.date('Y');

        if (!is_dir($folder_location)) {
            mkdir($folder_location,777,TRUE);
        }

        $file_name = date('ymdHis');

        $config['upload_path']          = $folder_location;
        $config['file_name']            = $file_name;
        $config['allowed_types']        = 'png|jpeg|jpg';
        $config['max_width']            = 2048;
        $config['max_height']           = 2048;
        $config['overwrite']            = TRUE;

        //$this->CI->load->library('upload', $config);

        $this->CI->upload->initialize($config);

        if(!$this->CI->upload->do_upload($input_image_post)){
           return $this->CI->upload->display_errors();
        }else{
          $extension = explode('.', $_FILES[$input_image_post]['name']);
          return $folder_location.'/'.$file_name.'.'.$extension[sizeof($extension)-1];
        }
    }


    // public function get_permission($url)
    // {
    //     //echo $url;
    //     $autorised = 0;
    //     if(empty($this->CI->Model->getOne('admin_droit',array('FONCTIONNALITE_URL'=>$url)))){
    //       $autorised =1;
    //     }else{
    //       $data = $this->CI->Model->get_permission($url);
    //       if(!empty($data)){
    //         $autorised =1;
    //       }
    //   }

    //   return $autorised;
    // }


    public function get_petitfacture($id)
    {



$printableArea = 'printableArea'.$id;
$detai =$this->CI->Model->getRequeteOne('SELECT restau_commande.ID_COMMANDE, masque_table_restaurant.NUM_TABLE, masque_table_restaurant.DESCRIPTION_EMPLACEMENT, restau_commande.DATE_TIME_COMMANDE, ser.COLLABORATEUR_NOM as SERCN, ser.COLLABORATEUR_PRENOM as SERCP, caiss.COLLABORATEUR_NOM AS CAISCN, caiss.COLLABORATEUR_PRENOM AS CAISCP, masque_clients.CLIENT_NOM ,masque_clients.NIF_CLIENT, masque_clients.ADRESSE_CLIENT FROM restau_commande LEFT JOIN admin_collaborateurs AS ser ON ser.COLLABORATEUR_ID = restau_commande.COLLABORATEUR_ID_COMMANDE LEFT JOIN restau_paiement_commande ON restau_paiement_commande.ID_COMMANDE = restau_commande.ID_COMMANDE LEFT JOIN admin_collaborateurs AS caiss on caiss.COLLABORATEUR_ID = restau_paiement_commande.COLLABORATEUR_ID_PAIEMENT JOIN masque_table_restaurant ON masque_table_restaurant.TABLE_ID = restau_commande.ID_TABLE LEFT JOIN masque_clients ON masque_clients.CLIENT_ID = restau_paiement_commande.CLIENT_ID WHERE restau_commande.ID_COMMANDE = 1');
$newDate = date("d-m-Y H:i", strtotime($detai['DATE_TIME_COMMANDE']));  


 $i=0;
                 $n=0;

  $prod =$this->CI->Model->getRequete('SELECT restau_commande_details.PRODUIT_ID, ID_COMMANDE_DETAIL, QUANTITE_LIVRE,masque_produit.DESCRIPTION as NOM_PRODUIT, QUANTITE_COMMANDE, PRIX_UNITAIRE, PRIX_TOTAL, restau_commande_details.COMMENT FROM restau_commande_details JOIN masque_produit ON masque_produit.PRODUIT_ID = restau_commande_details.PRODUIT_ID LEFT JOIN restau_cause_annulation ON restau_cause_annulation.CAUSE_ANNULATION_ID = restau_commande_details.CAUSE_ANNULATION_ID WHERE ID_COMMANDE = 1 AND restau_commande_details.COLLABORATEUR_ID_ANNULE is null AND restau_commande_details.DATE_TIME_ANNULATION is null');
  $prodnb =$this->CI->Model->getRequeteOne('SELECT count(*) as nx FROM restau_commande_details WHERE ID_COMMANDE = 1 AND restau_commande_details.COLLABORATEUR_ID_ANNULE is null AND restau_commande_details.DATE_TIME_ANNULATION is null');
                  $x = $prodnb['nx'] + 1;    
                  $tests = '';    
          foreach ($prod as $produits) {
              $tests.='<div style="width:1cm!important;text-align:right; float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">'.$produits['QUANTITE_COMMANDE'].'&nbsp;&nbsp;</div>
                          <div style="width:4.5cm!important;text-align:left;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">'.$produits['NOM_PRODUIT'].'</div>
                          <div style="width:2.1cm!important;text-align:right;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">'.number_format($produits['PRIX_UNITAIRE'], 2, ',', ' ').'</div>
                          <div style="width:2.5cm!important;text-align:right;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">'.number_format($produits['PRIX_TOTAL'], 2, ',', ' ').'</div>';
                            $i+=$produits['PRIX_TOTAL'];
        }

while($x <= 18) {
  $tests.='<div style="width:1cm!important;text-align:center; float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">_</div>
    <div style="width:4.5cm!important;text-align:center;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">_</div>
    <div style="width:2.1cm!important;text-align:center;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;">_</div>
    <div style="width:2.5cm!important;text-align:center;float:left;border-bottom-style: solid;border-bottom: 1px solid black; border-bottom-style: dotted;"> _</div>';
  $x++;
}

$fact ='<div id="'.$printableArea.'" class="abc">
<div class="book">
    <div style="width: 10.5cm; min-height: 14.85cm; max-height: 14.85cm; padding: 0.5cm; margin: 0.5cm auto;border: 1px #D3D3D3 solid;background: white; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);">
    <div style="width: 10.5cm;font: 14pt "Tahoma";">
    <b>KIBIRA PARK LODGE / BUGARAMA</b>
    </div>
    <div style="width: 10cm; min-height: 2.7cm;max-height: 2.7cm;font: 9pt "Tahoma";">
    NIF: <b>400019263</b> - RC: <b>00390</b> - Tel: <b>75735176 / 75781985</b><br>
    Commune MURAMVYA - BUGARAMA, RN1<br>
    Assujetti à la TVA: Oui &#9744; Non &#9746;<br>
    Centre Fiscal: DPMC<br>
    Secteur d\'activité: HOTEL<br>
    Forme juridique: SURL<br>
    </div> 
    <div style="width: 6cm; min-height: 0.7cm;max-height: 0.7cm;
        font: 10pt "Tahoma";float: left;">
    Client:<b> '.$detai['CLIENT_NOM'].' </b> <br>
    </div>
    <div style="width: 4cm; min-height: 0.7cm;
        max-height: 0.7cm;font: 10pt "Tahoma";float: right;">
    NIF: <b>'.$detai['NIF_CLIENT'].' </b> <br>
    </div>
    <div style="width: 4cm; min-height: 0.7cm; max-height: 0.7cm; font: 9pt "Tahoma";float: right;">
    Facture N°:<b> '.$detai['ID_COMMANDE'].'</b> <br>
    Table N°:<b> '.$detai['NUM_TABLE'].' </b> <br>
    </div>
    <div style="width: 6cm;
        min-height: 0.7cm;
        max-height: 0.7cm;
        font: 9pt "Tahoma";
        float: left;">
    Serveur: <b> '.$detai['SERCN'].' '.$detai['SERCP'].'</b> <br>
    Date: <b>'.$newDate.' </b> <br>
    </div>
    <div style="width: 10.5cm;
        padding-top: 1.5cm;
        font: 9pt "Tahoma";"><br>
    <table>
    <tr>
    <td style="width:1cm!important;text-align:center">Qte</td>
    <td style="width:4.5cm!important;text-align:center">Nature de l\'article</td>
    <td style="width:2.1cm!important;text-align:center">PU</td>
    <td style="width:2.5cm!important;text-align:center">PV - HTVA</td>
    </tr>
    </table>'.$tests.'
    <table>
    <tr>
    <td style="width:7.7cm!important">TOTAL HTVA</td>
    <td style="width:2.6cm!important;text-align:right">'.number_format($i, 2, ',', ' ').'</td>
    </tr>
    </table>
    <table>
    <tr>
    <td style="width:10.4cm!important;text-align:center">kibiraparklodge15@gmail.com</td>
    </tr>
    <tr>
    <td style="width:10.4cm!important;text-align:center">Merci et à bientôt</td>
    </tr>
    </table>   
    </div>
    </div>
</div>';
                                    
                  $fact.='
<script type="text/javascript">
  function printDiv'.$id.'('.$printableArea.') {
    
     var printContents = document.getElementById("'.$printableArea.'").innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;




}

window.onafterprint = function(){
      window.location.reload(true);
 }
</script>
';

// echo $fact;







//       $printableArea = 'printableArea'.$id;
// $detai =$this->CI->Model->getRequeteOne('SELECT restau_commande.ID_COMMANDE, masque_table_restaurant.NUM_TABLE, masque_table_restaurant.DESCRIPTION_EMPLACEMENT, restau_commande.DATE_TIME_COMMANDE, ser.COLLABORATEUR_NOM as SERCN, ser.COLLABORATEUR_PRENOM as SERCP, caiss.COLLABORATEUR_NOM AS CAISCN, caiss.COLLABORATEUR_PRENOM AS CAISCP, masque_clients.CLIENT_NOM ,masque_clients.NIF_CLIENT, masque_clients.ADRESSE_CLIENT FROM restau_commande JOIN admin_collaborateurs AS ser ON ser.COLLABORATEUR_ID = restau_commande.COLLABORATEUR_ID_LIVRAISON LEFT JOIN restau_paiement_commande ON restau_paiement_commande.ID_COMMANDE = restau_commande.ID_COMMANDE LEFT JOIN admin_collaborateurs AS caiss on caiss.COLLABORATEUR_ID = restau_paiement_commande.COLLABORATEUR_ID_PAIEMENT JOIN masque_table_restaurant ON masque_table_restaurant.TABLE_ID = restau_commande.ID_TABLE LEFT JOIN masque_clients ON masque_clients.CLIENT_ID = restau_paiement_commande.CLIENT_ID WHERE restau_commande.ID_COMMANDE = '.$id.'');
// $newDate = date("d-m-Y H:i", strtotime($detai['DATE_TIME_COMMANDE']));  
// $fact ='<div id="'.$printableArea.'" class="abc container-fluid row">
// <h5 style="margin-left: 20px"><b>Facture N° '.$detai['ID_COMMANDE'].' du '.$newDate.'</b></h5>
// <table style="width: 300px; font-size: 12px,margin-left: 15px!important">
// <tr>
// <td colspan="2">&nbsp;&nbsp;A.Identification du vendeur</td>
// </tr>
// <tr>
// <td colspan="2">&nbsp;&nbsp;Pearl Residence Hotel</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Centre Fiscale:</td>
// <td>D.MC</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Secteur d\'activité:</td>
// <td>Commercial</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Forme juridique:</td>
// <td>S.A</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;NIF:</td>
// <td>4000217820</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;RC N°:</td>
// <td>0076712</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;BP :</td>
// <td>6984, Bujumbura</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Serveur:</td>
// <td>'.$detai['SERCN'].' '.$detai['SERCP'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Caissier:</td>
// <td>'.$detai['CAISCN'].' '.$detai['CAISCP'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Table:</td>
// <td>'.$detai['NUM_TABLE'].' '.$detai['DESCRIPTION_EMPLACEMENT'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Tel: </td>
// <td>(+257) 22222219/18</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Adresse:</td>
// <td>Av de la Plage</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Assujetti à la TVA:</td>
// <td>OUI</td>
// </tr>
// <tr>
// <td colspan="2">&nbsp;&nbsp;B. Identification du client</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Client:</td>
// <td>'.$detai['CLIENT_NOM'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;NIF:</td>
// <td>'.$detai['NIF_CLIENT'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Resident à:</td>
// <td>'.$detai['ADRESSE_CLIENT'].'</td>
// </tr>
// <tr>
// <td>&nbsp;&nbsp;Assujetti à la TVA:</td>
// <td>OUI ----- NON-----</td>
// </tr>
// <table>
//            <br>
//            &nbsp;&nbsp;Doit ce qui suit
//                   <table  style="width: 300px; font-size: 12px,margin-left: 15px!important">
//            <tr>
//                     <th>&nbsp;&nbsp;Qte</th>
//                     <th>&nbsp;&nbsp;Produits</th>
//                     <th>&nbsp;&nbsp;PU HVA</th>
//                     <th>&nbsp;&nbsp;PT HTA</th>
//             </tr> ';
//                  $i=0;
//                  $n=0;

//   $prod =$this->CI->Model->getRequete('SELECT restau_commande_details.PRODUIT_ID, ID_COMMANDE_DETAIL, QUANTITE_LIVRE,masque_produit.DESCRIPTION as NOM_PRODUIT, QUANTITE_COMMANDE, PRIX_UNITAIRE, PRIX_TOTAL, restau_commande_details.COMMENT FROM restau_commande_details JOIN masque_produit ON masque_produit.PRODUIT_ID = restau_commande_details.PRODUIT_ID LEFT JOIN restau_cause_annulation ON restau_cause_annulation.CAUSE_ANNULATION_ID = restau_commande_details.CAUSE_ANNULATION_ID WHERE ID_COMMANDE = '.$id.' AND restau_commande_details.COLLABORATEUR_ID_ANNULE is null AND restau_commande_details.DATE_TIME_ANNULATION is null');
                          
//           foreach ($prod as $produits) {
//                         $fact.='<tr>
//                           <td class="text-right">'.$produits['QUANTITE_COMMANDE'].'</td>
//                             <td>&nbsp;'.$produits['NOM_PRODUIT'].' </td>
//                             <td class="text-right">'.number_format($produits['PRIX_UNITAIRE'], 2, ',', ' ').'</td>
//                             <td class="text-right">'.number_format($produits['PRIX_TOTAL'], 2, ',', ' ').'</td>
//                             </tr>';
//                             $i+=$produits['PRIX_TOTAL'];
//           }                 
                  
//                   $tva =$this->CI->Model->getRequeteOne('SELECT * FROM masque_tva WHERE masque_tva.STATUS = 1');
//                   $tv= $i * $tva['VALEUR_TVA'] / 100 ;
                  
//                   $fact.='<tr>
//                     <td colspan="3">&nbsp;&nbsp;TOTAL HTVA </td>
//                     <td class="text-right">'.number_format($i, 2, ',', ' ').'</td>
//                   </tr>
//                   <tr>
//                     <td colspan="3">&nbsp;&nbsp;TVA </td>
//                     <td class="text-right">'.number_format($tv, 2, ',', ' ').'</td>
//                   </tr>
//                   <tr>
//                     <td colspan="3">&nbsp;&nbsp;TOTAL TVAC </td>
//                     <td class="text-right">'.number_format($tv+$i, 2, ',', ' ').'</td>
//                   </tr>
//                   <tr>
//                     <td colspan="3">&nbsp;&nbsp;Devise </td>
//                     <td class="text-right">BIF</td>
//                   </tr>
//                   <tr>
//                     <td colspan="4" class="text-center">---------------------------------------------- </td>
//                   </tr>
//                   <tr>
//                     <td colspan="4" class="text-center">MERCI A BIENTOT </td>
//                   </tr>
//                   </table>
// </div>
// <script type="text/javascript">
//   function printDiv'.$id.'('.$printableArea.') {
    
//      var printContents = document.getElementById("'.$printableArea.'").innerHTML;
//      var originalContents = document.body.innerHTML;
//      document.body.innerHTML = printContents;
//      window.print();
//      document.body.innerHTML = originalContents;




// }

// window.onafterprint = function(){
//       window.location.reload(true);
//  }
// </script>
// ';

echo $fact;
    }

  public function numfact()
  {
    // echo 'Mac';
     $prod =$this->CI->Model->getRequete('SELECT * FROM restau_commande_details JOIN masque_produit ON masque_produit.PRODUIT_ID = restau_commande_details.PRODUIT_ID LEFT JOIN restau_cause_annulation ON restau_cause_annulation.CAUSE_ANNULATION_ID = restau_commande_details.CAUSE_ANNULATION_ID WHERE restau_commande_details.COLLABORATEUR_ID_ANNULE is null AND restau_commande_details.DATE_TIME_ANNULATION is null');
    print_r($prod);
  }




}


?>