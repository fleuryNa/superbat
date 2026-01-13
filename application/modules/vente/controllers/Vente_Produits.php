<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vente_Produits extends MY_Controller {

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

		print_r($this->session->userdata('SUPERBAT_ID_USER'));die();
	}

	function index($value='')
	{
		$data['title']='Commande';
		$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` JOIN transferer_prod_stock ON transferer_prod_stock.ID_TYPE_TOLES=type_toles.ID_TYPE_TOLES  WHERE transferer_prod_stock.ID_STATUT_PRODUITS=2 AND transferer_prod_stock.QUANTITE>0 order by DESCRIPTION_TOLES');
		$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` JOIN transferer_prod_stock ON transferer_prod_stock.ID_TYPE_CLOUS=type_clous.ID_TYPE_CLOUS WHERE transferer_prod_stock.ID_STATUT_PRODUITS=2 AND transferer_prod_stock.QUANTITE>0 order by DESCRIPTION');
		$data['provinces']=$this->Model->getRequete('SELECT * FROM `provinces` order by PROVINCE_NAME');
		$this->load->view('Vente_Produits_Add_View',$data);
	}

	public function get_commune()
	{
		$province_id = $this->input->post('provine_id');
		$commune_sel = $this->input->post('commune_id'); 

		$communes = $this->Model->getList("communes", array('PROVINCE_ID' => $province_id));

		$datas = '<option value="">-- Sélectionner --</option>';
		foreach ($communes as $commune) {
			$selected = ($commune_sel && $commune_sel == $commune["COMMUNE_ID"]) ? ' selected' : '';
			$datas .= '<option value="'.$commune["COMMUNE_ID"].'"'.$selected.'>'.$commune["COMMUNE_NAME"].'</option>';
		}

		echo $datas;
	}

	public function get_zone()
	{
		$commune_id = $this->input->post('commune_id');
		$zone_sel   = $this->input->post('zone_id'); 
		$zones = $this->Model->getList("zones", array('COMMUNE_ID' => $commune_id));

		$datas = '<option value="">-- Sélectionner --</option>';
		foreach ($zones as $zone) {
			$selected = ($zone_sel && $zone_sel == $zone["ZONE_ID"]) ? ' selected' : '';
			$datas .= '<option value="'.$zone["ZONE_ID"].'"'.$selected.'>'.$zone["ZONE_NAME"].'</option>';
		}

		echo $datas;
	}


	public function get_colline()
	{
		$zone_id     = $this->input->post('zone_id');
		$colline_sel = $this->input->post('colline_id'); 

		$collines = $this->Model->getList("collines", array('ZONE_ID' => $zone_id));

		$datas = '<option value="">-- Sélectionner --</option>';
		foreach ($collines as $colline) {
			$selected = ($colline_sel && $colline_sel == $colline["COLLINE_ID"]) ? ' selected' : '';
			$datas .= '<option value="'.$colline["COLLINE_ID"].'"'.$selected.'>'.$colline["COLLINE_NAME"].'</option>';
		}

		echo $datas;
	}


	public function save_order()
	{
		header('Content-Type: application/json');

		$panier = $this->input->post('panier');
		$client = $this->input->post('client');

    /* =========================
     * VALIDATION BASIQUE
    ========================= */
    if (empty($panier) || !is_array($panier)) {
    	echo json_encode([
    		'status'  => 'error',
    		'message' => 'Le panier est vide ou invalide.'
    	]);
    	return;
    }

    if (empty($client) || !is_array($client)) {
    	echo json_encode([
    		'status'  => 'error',
    		'message' => 'Les informations client sont manquantes.'
    	]);
    	return;
    }

    $this->db->trans_begin();

    try {

        /* =========================
         * INSERT CLIENT
        ========================= */
        $client_data = [
        	'NOM'              => trim($client['NOM'] ?? ''),
        	'TELEPHONE'        => trim($client['TELEPHONE'] ?? ''),
        	'NIF'              => trim($client['NIF'] ?? ''),
        	'PROVINCE_ID'      => (int) ($client['PROVINCE_ID'] ?? 0),
        	'COMMUNE_ID'       => (int) ($client['COMMUNE_ID'] ?? 0),
        	'ZONE_ID'          => (int) ($client['ZONE_ID'] ?? 0),
        	'COLLINE_ID'       => (int) ($client['COLLINE_ID'] ?? 0),
        	'ADRESSE_COMPLETE' => trim($client['ADRESSE_COMPLETE'] ?? ''),
        	'ASSUJETTI'        => $client['ASSUJETI'] ?? 0
        ];

        if (empty($client_data['NOM']) || empty($client_data['TELEPHONE'])) {
        	throw new Exception('Nom ou téléphone du client manquant.');
        }

        $client_id = $this->Model->insert_last_id('client_acheteur', $client_data);

        if (!$client_id) {
        	throw new Exception('Impossible de créer le client.');
        }

        /* =========================
         * INSERT COMMANDES
        ========================= */
        foreach ($panier as $p) {

        	if (
        		empty($p['type_id']) ||
        		empty($p['qty']) ||
        		empty($p['prix']) ||
        		$p['qty'] <= 0 ||
        		$p['prix'] <= 0
        	) {
        		throw new Exception('Produit invalide dans le panier.');
        	}

        	$matiere_toles = ($p['type_id'] == 1) ? ($p['matiere_id'] ?? null) : null;
        	$matiere_clous = ($p['type_id'] == 2) ? ($p['matiere_id'] ?? null) : null;
        	$year  = date('Y');
        	$month = date('m'); 
        	$prefix = $year . $month; 

        	$lastFacture = $this->Model->getRequeteOne("
        		SELECT NUMERO_FATURE
        		FROM vente_commande
        		WHERE NUMERO_FATURE LIKE '{$prefix}-%'
        		ORDER BY NUMERO_FATURE DESC
        		LIMIT 1
        		");

        	if ($lastFacture) {
        		$parts = explode('-', $lastFacture['NUMERO_FATURE']);
        		$numero = (int)$parts[1] + 1;
        	} else {
        		$numero = 1;
        	}
        	$max = $prefix . '-' . $numeroFormate;

        	$data_commande = [
        		'NUMERO_FATURE'=>$max,
        		'ID_TYPE_PRODUIT' => (int) $p['type_id'],
        		'ID_TYPE_TOLES'   => $matiere_toles,
        		'ID_TYPE_CLOUS'   => $matiere_clous,
        		'QUANTITE_VENTE'  => (int) $p['qty'],
        		'PRIX_UNITAIRE'   => (float) $p['prix'],
        		'ID_CLIENT'       => $client_id,
        		'ID_STATUT_VENTE' => 1,
        		'NUMERO_COLIS'    => $p['colis'],
        		'COULEUR'         => $p['couleur'],
        		'LONGUEUR'        => $p['longueur'],
        		'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
        		'DATE_INSERTION'  => date('Y-m-d H:i:s')
        	];

        	$id_commande = $this->Model->insert_last_id('vente_commande', $data_commande);





        	$data_histo = [
        		'ID_VENTE' =>$id_commande,
        		'NUMERO_FATURE'=>$max,
        		'ID_TYPE_PRODUIT' => (int) $p['type_id'],
        		'ID_TYPE_TOLES'   => $matiere_toles,
        		'ID_TYPE_CLOUS'   => $matiere_clous,
        		'QUANTITE_VENTE'  => (int) $p['qty'],
        		'PRIX_UNITAIRE'   => (float) $p['prix'],
        		'ID_CLIENT'       => $client_id,
        		'ID_STATUT_VENTE' => 1,
        		'NUMERO_COLIS'    => $p['colis'],
        		'COULEUR'         => $p['couleur'],
        		'LONGUEUR'        => $p['longueur'],
        		'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
        		'DATE_INSERTION'  => date('Y-m-d H:i:s')
        	];

        	$this->Model->create('historique_vente_commande',$data_histo);

        	if (!$id_commande) {
        		throw new Exception('Erreur lors de l’enregistrement d’une commande.');
        	}

            /* =========================
             * HISTORIQUE
            ========================= */
            $data_histo = $data_commande;
            $data_histo['ID_VENTE'] = $id_commande;

            $this->Model->create('historique_vente_commande', $data_histo);
        }

        /* =========================
         * COMMIT
        ========================= */
        if ($this->db->trans_status() === FALSE) {
        	throw new Exception('Erreur transaction base de données.');
        }

        $this->db->trans_commit();

        echo json_encode([
        	'status'  => 'success',
        	'message' => 'Commande enregistrée avec succès.'
        ]);

    } catch (Exception $e) {

    	$this->db->trans_rollback();

    	echo json_encode([
    		'status'  => 'error',
    		'message' => $e->getMessage()
    	]);
    }
}



public function getProduitInfo()
{
	$type_id = $this->input->post('type_id');
	$matiere_id = $this->input->post('matiere_id');

	if(!$type_id || !$matiere_id){
		echo json_encode([
			'status' => 'error',
			'message' => 'Paramètres manquants'
		]);
		return;
	}

	if($type_id == 1){
        // TOLES
		$row = $this->db->get_where('type_toles', [
			'ID_TYPE_TOLES' => $matiere_id
		])->row_array();

		$description = $row['DESCRIPTION_TOLES'];
		$type_libelle = 'Tôles';
	}
	else{
        // CLOUS
		$row = $this->db->get_where('type_clous', [
			'ID_TYPE_CLOUS' => $matiere_id
		])->row_array();

		$description = $row['DESCRIPTION'];
		$type_libelle = 'Clous';
	}

	echo json_encode([
		'status' => 'success',
		'type_libelle' => $type_libelle,
		'description' => $description
	]);
}



function liste(){
	$data['title']='Liste commande';
	$this->load->view('Vente_Produits_List_View',$data);
}

public function listing()
{

	$query_principal ="SELECT `ID_VENTE`,NUMERO_FATURE, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, type_toles.ID_TYPE_TOLES, `ID_TYPE_CLOUS`,type_toles.DESCRIPTION_TOLES, `QUANTITE_VENTE`, `PRIX_UNITAIRE`, vente_commande.ID_CLIENT,client_acheteur.NOM as client,client_acheteur.TELEPHONE, `ID_STATUT_VENTE`, vente_commande.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION` FROM `vente_commande` JOIN type_toles ON type_toles.ID_TYPE_TOLES= vente_commande.ID_TYPE_TOLES JOIN client_acheteur ON client_acheteur.ID_CLIENT=vente_commande.ID_CLIENT JOIN admin_user ON admin_user.ID_USER=vente_commande.ID_USER WHERE ID_STATUT_VENTE =1" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_VENTE','NUMERO_FATURE', 'produit','client_acheteur.NOM', 'type_toles.DESCRIPTION_TOLES','admin_user.NOM', 'admin_user.PRENOM', 'QUANTITE_VENTE', 'DATE_INSERTION');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND NUMERO_FATURE LIKE '%$var_search%'  OR produit LIKE '%$var_search%'  OR type_toles.DESCRIPTION_TOLES LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR client_acheteur.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
	: '';

	$critaire = '';

	$groupeby = ' GROUP BY NUMERO_FATURE';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search .' '.$groupeby . ' ' . $order_by . '   ' . $limit ;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$statut=$this->Model->getOne('statut_vente',['ID_STATUT_VENTE' => $key->ID_STATUT_VENTE]);

		$row[] = $key->NUMERO_FATURE;
		$row[] = $key->NOM." ".$key->PRENOM;
		$row[] = $key->client.'('.$key->TELEPHONE.')' ;
		$row[] = $key->produit;
		$row[] =  $key->DESCRIPTION_TOLES;
		$row[] =  $key->QUANTITE_VENTE;
		$row[] =  $key->PRIX_UNITAIRE;
		$row[] =  $statut['DESCRIPTION_VENTE'];

		$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
		$facture=$key->NUMERO_FATURE;
		$facture='202601-00001';



		$row[] = '
		<div class="btn-group">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
		</button>
		<div class="dropdown-menu">
		<a class="dropdown-item" href="'.base_url("vente/Vente_Produits/effacer/".$key->ID_VENTE).'">
		<i class="fa fa-edit"></i> Annuler
		</a>
		<a class="dropdown-item" 
		href="'.base_url("vente/Pdf_Vente/pdf_facture/".$key->NUMERO_FATURE).'" 
		target="_blank">
		<i class="fa fa-file-pdf-o"></i> Proforma
		</a>

		<a class="dropdown-item" onclick="get_histo_toles(\''.htmlspecialchars($facture, ENT_QUOTES).'\')">
		<i class="fa fa-eye"></i> Details
		</a>
		</div>
		</div>';




		$data[] = $row;
	}

	$output = array(
		"draw" => intval($_POST['draw']),
		"recordsTotal" => $this->Model->all_data($query_principal),
		"recordsFiltered" => $this->Model->filtrer($query_filter),
		"data" => $data
	);

	echo json_encode($output);
	exit;
}


public function listing_clous()
{

	$query_principal ="SELECT `ID_VENTE`,NUMERO_FATURE, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, vente_commande.ID_TYPE_TOLES, vente_commande.ID_TYPE_CLOUS, `QUANTITE_VENTE`, `PRIX_UNITAIRE`, type_clous.DESCRIPTION,vente_commande.ID_CLIENT,client_acheteur.NOM as client,client_acheteur.TELEPHONE, `ID_STATUT_VENTE`, vente_commande.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION` FROM `vente_commande` JOIN client_acheteur ON client_acheteur.ID_CLIENT=vente_commande.ID_CLIENT JOIN admin_user ON admin_user.ID_USER=vente_commande.ID_USER JOIN type_clous ON type_clous.ID_TYPE_CLOUS=vente_commande.ID_TYPE_CLOUS WHERE  ID_STATUT_VENTE =2" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_VENTE','NUMERO_FATURE', 'produit','type_clous.DESCRIPTION','admin_user.NOM', 'admin_user.PRENOM', 'client_acheteur.NOM', 'QUANTITE_VENTE', 'DATE_INSERTION');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND NUMERO_FATURE LIKE '%$var_search%'  OR produit LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR client_acheteur.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
	: '';

	$critaire = '';
	$groupeby = ' GROUP BY NUMERO_FATURE';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search .' '.$groupeby . ' ' . $order_by . '   ' . $limit ;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$statut=$this->Model->getOne('statut_vente',['ID_STATUT_VENTE' => $key->ID_STATUT_VENTE]);
		$row[] = $key->NUMERO_FATURE;
		$row[] = $key->NOM." ".$key->PRENOM;
		$row[] = $key->client.'('.$key->TELEPHONE.')' ;
		$row[] = $key->produit;
		$row[] =  $key->DESCRIPTION;
		$row[] =  $key->QUANTITE_VENTE;
		$row[] =  $key->PRIX_UNITAIRE;
		$row[] =  $statut['DESCRIPTION_VENTE'];
		$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));

		$row[] = '
		<div class="btn-group">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
		</button>
		<div class="dropdown-menu">
		<a class="dropdown-item" href="'.base_url("vente/Vente_Produits/effacer/".$key->ID_VENTE).'">
		<i class="fa fa-edit"></i> Modifier
		</a>
		<a class="dropdown-item" href="'.base_url("vente/Pdf_Vente/pdf_facture/".$key->NUMERO_FATURE).'">
		<i class="fa fa-file-pdf-o"></i> proforma
		</a>
		<a class="dropdown-item" onclick="get_histo_clous(\''.htmlspecialchars($facture, ENT_QUOTES).'\')">
		<i class="fa fa-eye"></i> Details
		</a>
		</div>
		</div>
		';



		$data[] = $row;
	}

	$output = array(
		"draw" => intval($_POST['draw']),
		"recordsTotal" => $this->Model->all_data($query_principal),
		"recordsFiltered" => $this->Model->filtrer($query_filter),
		"data" => $data
	);

	echo json_encode($output);
	exit;
}





public function effacer($id)
{


	$this->Model->update('vente_commande',array('ID_VENTE'=>$id),['ID_STATUT_VENTE'=>4]);
	$message = "<div class='alert alert-success' id='message'>

	La commande est annul&eacute; avec succés

	<button type='button' class='close' data-dismiss='alert'>&times;</button>

	</div>";

	$this->session->set_flashdata(array('success'=>$message));
	redirect(base_url('vente/Vente_Produits'));  

}




public function get_details()
{
	$NUMERO_FATURE=$this->input->post('NUMERO_FATURE');


	$query_principal ="SELECT 
	vc.ID_VENTE,
	vc.NUMERO_FATURE,
	IF(vc.ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit,
	vc.ID_TYPE_TOLES,
	vc.ID_TYPE_CLOUS,
	vc.QUANTITE_VENTE,
	vc.PRIX_UNITAIRE,
	tc.DESCRIPTION,
	tt.DESCRIPTION_TOLES,
	vc.ID_CLIENT,
	ca.NOM as client,
	ca.TELEPHONE,
	vc.ID_STATUT_VENTE,
	vc.ID_USER,
	au.NOM AS user_nom,
	au.PRENOM AS user_prenom,
	vc.DATE_INSERTION
	FROM vente_commande vc
	JOIN client_acheteur ca ON ca.ID_CLIENT = vc.ID_CLIENT
	JOIN admin_user au ON au.ID_USER = vc.ID_USER
	LEFT JOIN type_clous tc ON tc.ID_TYPE_CLOUS = vc.ID_TYPE_CLOUS
	LEFT JOIN type_toles tt ON tt.ID_TYPE_TOLES = vc.ID_TYPE_TOLES
	WHERE vc.NUMERO_FATURE ='".$NUMERO_FATURE."'" ;

	// print_r($query_principal);exit();



	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_VENTE', 'produit','ca.NOM','tt.DESCRIPTION_TOLES','au.NOM','au.PRENOM','QUANTITE_VENTE','DATE_INSERTION');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND  produit LIKE '%$var_search%'  OR tt.DESCRIPTION_TOLES LIKE '%$var_search%' OR au.NOM LIKE '%$var_search%' OR au.PRENOM LIKE '%$var_search%' OR ca.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$statut=$this->Model->getOne('statut_vente',['ID_STATUT_VENTE' => $key->ID_STATUT_VENTE]);
		$row[] = $key->NUMERO_FATURE;
		$row[] = $key->user_nom." ".$key->user_prenom;
		$row[] = $key->client.'('.$key->TELEPHONE.')' ;
		$row[] = $key->produit;
		$row[] =  $key->DESCRIPTION_TOLES;
		$row[] =  $key->QUANTITE_VENTE;
		$row[] =  $key->PRIX_UNITAIRE;
		$row[] =  $statut['DESCRIPTION_VENTE'];

		$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));


		$data[] = $row;
	}

	$output = array(
		"draw" => intval($_POST['draw']),
		"recordsTotal" => $this->Model->all_data($query_principal),
		"recordsFiltered" => $this->Model->filtrer($query_filter),
		"data" => $data
	);

	echo json_encode($output);
	exit;
}


public function get_details_clous()
{
	$NUMERO_FATURE=$this->input->post('NUMERO_FATURE');

	
	$query_principal ="SELECT 
	vc.ID_VENTE,
	vc.NUMERO_FATURE,
	IF(vc.ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit,
	vc.ID_TYPE_TOLES,
	vc.ID_TYPE_CLOUS,
	vc.QUANTITE_VENTE,
	vc.PRIX_UNITAIRE,
	tc.DESCRIPTION,
	tt.DESCRIPTION_TOLES,
	vc.ID_CLIENT,
	ca.NOM as client,
	ca.TELEPHONE,
	vc.ID_STATUT_VENTE,
	vc.ID_USER,
	au.NOM AS user_nom,
	au.PRENOM AS user_prenom,
	vc.DATE_INSERTION
	FROM vente_commande vc
	JOIN client_acheteur ca ON ca.ID_CLIENT = vc.ID_CLIENT
	JOIN admin_user au ON au.ID_USER = vc.ID_USER
	LEFT JOIN type_clous tc ON tc.ID_TYPE_CLOUS = vc.ID_TYPE_CLOUS
	LEFT JOIN type_toles tt ON tt.ID_TYPE_TOLES = vc.ID_TYPE_TOLES
	WHERE vc.NUMERO_FATURE ='".$NUMERO_FATURE."'" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_VENTE', 'produit','ca.NOM','tt.DESCRIPTION_TOLES','au.NOM','au.PRENOM','QUANTITE_VENTE','DATE_INSERTION');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND  produit LIKE '%$var_search%'  OR tt.DESCRIPTION_TOLES LIKE '%$var_search%' OR au.NOM LIKE '%$var_search%' OR au.PRENOM LIKE '%$var_search%' OR ca.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$statut=$this->Model->getOne('statut_vente',['ID_STATUT_VENTE' => $key->ID_STATUT_VENTE]);
		$row[] = $key->NUMERO_FATURE;
		$row[] = $key->user_nom." ".$key->user_prenom;
		$row[] = $key->client.'('.$key->TELEPHONE.')' ;
		$row[] = $key->produit;
		$row[] =  $key->DESCRIPTION_TOLES;
		$row[] =  $key->QUANTITE_VENTE;
		$row[] =  $key->PRIX_UNITAIRE;
		$row[] =  $statut['DESCRIPTION_VENTE'];

		$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));


		$data[] = $row;
	}

	$output = array(
		"draw" => intval($_POST['draw']),
		"recordsTotal" => $this->Model->all_data($query_principal),
		"recordsFiltered" => $this->Model->filtrer($query_filter),
		"data" => $data
	);

	echo json_encode($output);
	exit;
}



}
