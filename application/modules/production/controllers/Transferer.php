<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transferer extends MY_Controller {

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

	function index($value='')
	{
		$data['title']='Transferer dans stock des produits finis';
		$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` JOIN stock_production ON stock_production.ID_TYPE_TOLES=type_toles.ID_TYPE_TOLES WHERE stock_production.NOMBRE_BONNET>0 order by DESCRIPTION_TOLES');
		$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` JOIN stock_production ON stock_production.ID_TYPE_CLOUS=type_clous.ID_TYPE_CLOUS WHERE stock_production.QUANTITE>0  order by DESCRIPTION');
		$this->load->view('Transferer_Add_View',$data);
	}

// Retourne le contenu du panier pour AJAX
	public function get_cart()
	{
		$data = [];

		foreach ($this->cart->contents() as $item) {
			$data[] = [
				'rowid'        => $item['rowid'],
				'type_produit' => ($item['ID_TYPE_PRODUIT'] == 1) ? 'Tôles' : 'Clous',
				'matiere'      => $item['ID_TYPE_MATIERE'],
				'numero_colis' => $item['NUMERO_COLIS'] ?? '',
				'couleur'      => $item['COULEUR'] ?? '',
				'longueur'     => $item['LONGUEUR'] ?? '',
				'quantite'     => (int) $item['qty']
			];
		}

		return $this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}


// Update quantité
	public function update_cart()
	{
		$rowid = $this->input->post('rowid', true);
		$qty   = (int) $this->input->post('QUANTITE');

		if (!$rowid || $qty <= 0) {
			return $this->output->set_output(json_encode([
				'status' => false,
				'message' => 'Données invalides'
			]));
		}

		$this->cart->update([
			'rowid' => $rowid,
			'qty'   => $qty
		]);

		return $this->output->set_output(json_encode(['status' => true]));
	}



// Remove item
	public function remove_cart()
	{
		$rowid = $this->input->post('rowid', true);

		if (!$rowid) {
			return $this->output->set_output(json_encode([
				'status' => false
			]));
		}

		$this->cart->remove($rowid);

		return $this->output->set_output(json_encode(['status' => true]));
	}

	public function addcart()
	{
		$data = $this->input->post(NULL, true);

		if (empty($data['ID_TYPE_PRODUIT']) || empty($data['ID_TYPE_MATIERE']) || empty($data['QUANTITE'])) {
			return $this->output->set_output(json_encode([
				'status' => false,
				'message' => 'Données manquantes'
			]));
		}

		$this->cart->insert([
			'id'      => uniqid(),
			'qty'     => (int) $data['QUANTITE'],
			'price'   => 0,
			'name'    => 'Produit',
			'ID_TYPE_PRODUIT' => $data['ID_TYPE_PRODUIT'],
			'ID_TYPE_MATIERE' => $data['ID_TYPE_MATIERE'],
			'NUMERO_COLIS'    => $data['NUMERO_COLIS'] ?? null,
			'COULEUR'         => $data['COULEUR'] ?? null,
			'LONGUEUR'        => $data['LONGUEUR'] ?? null
		]);

		return $this->output->set_output(json_encode(['status' => true]));
	}




	public function save_transfer()
	{
    /* =========================
     * 1. Vérifications initiales
    ========================= */
    if (empty($this->cart->contents())) {
    	$this->session->set_flashdata(
    		'error',
    		"<div class='alert alert-danger text-center'>Aucun produit dans le panier.</div>"
    	);
    	return redirect('production/Transferer');
    }

    $id_user = (int) $this->session->userdata('SUPERBAT_ID_USER');
    if ($id_user <= 0) {
    	$this->session->set_flashdata(
    		'error',
    		"<div class='alert alert-danger text-center'>Utilisateur non authentifié.</div>"
    	);
    	return redirect('production/Transferer');
    }

    /* =========================
     * 2. Transaction DB
    ========================= */
    $this->db->trans_begin();

    foreach ($this->cart->contents() as $item) {


    	// print_r($item);exit();

    	$id_type_produit = (int) ($item['ID_TYPE_PRODUIT'] ?? 0);
    	$id_matiere      = (int) ($item['ID_TYPE_MATIERE'] ?? 0);
    	$quantite        = (int) ($item['qty'] ?? 0);

    	if ($id_type_produit <= 0 || $id_matiere <= 0 || $quantite <= 0) {
    		$this->db->trans_rollback();
    		$this->session->set_flashdata(
    			'error',
    			"<div class='alert alert-danger text-center'>Données du panier invalides.</div>"
    		);
    		return redirect('production/Transferer');
    	}

        /* =========================
         * 3. Construction condition
        ========================= */
        $where = [
        	'ID_TYPE_PRODUIT' => $id_type_produit,
        	'ID_STATUT_PRODUITS' => 1
        ];

        if ($id_type_produit === 1) { // TOLES
        	$where += [
        		'ID_TYPE_TOLES' => $id_matiere,
        		'NUMERO_COLIS'  => $item['NUMERO_COLIS'] ?? NULL,
        		'COULEUR'       => $item['COULEUR'] ?? NULL,
        		'LONGUEUR'      => $item['LONGUEUR'] ?? NULL
        	];
        } elseif ($id_type_produit === 2) { // CLOUS
        	$where['ID_TYPE_CLOUS'] = $id_matiere;
        } else {
        	$this->db->trans_rollback();
        	$this->session->set_flashdata(
        		'error',
        		"<div class='alert alert-danger text-center'>Type de produit invalide.</div>"
        	);
        	return redirect('production/Transferer');
        }

        /* =========================
         * 4. Insert / Update
        ========================= */
        $existing = $this->Model->getOne('transferer_prod_stock', $where);

        if ($existing) {
        	$this->Model->update(
        		'transferer_prod_stock',
        		['ID_TRANSFERER_STOCK' => $existing['ID_TRANSFERER_STOCK']],
        		[
        			'QUANTITE' => $existing['QUANTITE'] + $quantite,
        			'DATE_INSERT_TRANSFER' => date('Y-m-d H:i:s')
        		]
        	);
        } else {
        	$this->Model->create('transferer_prod_stock', [
        		'ID_TYPE_PRODUIT'      => $id_type_produit,
        		'ID_TYPE_TOLES'        => ($id_type_produit === 1) ? $id_matiere : NULL,
        		'ID_TYPE_CLOUS'        => ($id_type_produit === 2) ? $id_matiere : NULL,
        		'QUANTITE'             => $quantite,
        		'NUMERO_COLIS'         => ($id_type_produit === 1) ? ($item['NUMERO_COLIS'] ?? NULL) : NULL,
        		'COULEUR'              => ($id_type_produit === 1) ? ($item['COULEUR'] ?? NULL) : NULL,
        		'LONGUEUR'             => ($id_type_produit === 1) ? ($item['LONGUEUR'] ?? NULL) : NULL,
        		'ID_STATUT_PRODUITS'      => 1,
        		'ID_USER_EXPEDITEUR'   => $id_user,
        		'DATE_INSERT_TRANSFER' => date('Y-m-d H:i:s')
        	]);
        }

        if ($this->db->trans_status() === FALSE) {
        	$this->db->trans_rollback();
        	$this->session->set_flashdata(
        		'error',
        		"<div class='alert alert-danger text-center'>Erreur base de données.</div>"
        	);
        	return redirect('production/Transferer');
        }
    }

    /* =========================
     * 5. Finalisation
    ========================= */
    $this->db->trans_commit();
    $this->cart->destroy();

    $this->session->set_flashdata(
    	'success',
    	"<div class='alert alert-success text-center'>Transfert enregistré avec succès.</div>"
    );

    redirect('production/Transferer');
}




function liste(){
	$data['title']='Liste des transferes';

	$this->load->view('Transferer_Liste_View',$data);
}

public function listing()
{

	$query_principal ="SELECT ID_TRANSFERER_STOCK,ID_TYPE_PRODUIT ,IF (ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit, type_toles.DESCRIPTION_TOLES,type_clous.DESCRIPTION AS clous, tps.ID_TYPE_CLOUS, QUANTITE, DATE_INSERT_TRANSFER, NUMERO_COLIS,COULEUR, user.NOM,user.PRENOM, ID_USER_RECEPTEUR,statut_produits.DESCRIPTION_PRODUITS  FROM transferer_prod_stock tps LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES=tps.ID_TYPE_TOLES LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=tps.ID_TYPE_CLOUS LEFT JOIN admin_user user ON user.ID_USER=tps.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=tps.ID_STATUT_PRODUITS WHERE tps.ID_STATUT_PRODUITS=1" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_TRANSFERER_STOCK','type_toles.DESCRIPTION_TOLES','type_clous.DESCRIPTION', 'NUMERO_COLIS','user.NOM','QUANTITE','DATE_INSERT_TRANSFER');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_TRANSFERER_STOCK ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND user.NOM LIKE '%$var_search%' OR user.PRENOM LIKE '%$var_search%' OR QUANTITE_TONNE LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%'  OR type_toles.DESCRIPTION_TOLES LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERT_TRANSFER, '%d/%m/%Y') LIKE '%$var_search%'  "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$row[] = $key->NOM .' '.$key->PRENOM;
		$row[] = $key->produit;
		$row[] = $key->ID_TYPE_PRODUIT==1 ? $key->DESCRIPTION_TOLES .'('.$key->NUMERO_COLIS.')' : $key->clous;
		$row[] = $key->QUANTITE;		
		$row[] = date("d/m/Y", strtotime($key->DATE_INSERT_TRANSFER));


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


public function listing_reception()
{

	$query_principal ="SELECT ID_TRANSFERER_STOCK,ID_TYPE_PRODUIT ,IF (ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit, type_toles.DESCRIPTION_TOLES,type_clous.DESCRIPTION AS clous, tps.ID_TYPE_CLOUS, QUANTITE, DATE_INSERT_TRANSFER, NUMERO_COLIS, user.NOM,user.PRENOM, ID_USER_RECEPTEUR,statut_produits.DESCRIPTION_PRODUITS  FROM transferer_prod_stock tps LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES=tps.ID_TYPE_TOLES LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=tps.ID_TYPE_CLOUS LEFT JOIN admin_user user ON user.ID_USER=tps.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=tps.ID_STATUT_PRODUITS WHERE tps.ID_STATUT_PRODUITS=2" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_TRANSFERER_STOCK','type_toles.DESCRIPTION_TOLES','type_clous.DESCRIPTION', 'NUMERO_COLIS','user.NOM','QUANTITE','DATE_INSERT_TRANSFER');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_TRANSFERER_STOCK ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND user.NOM LIKE '%$var_search%' OR user.PRENOM LIKE '%$var_search%' OR QUANTITE_TONNE LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%'  OR type_toles.DESCRIPTION_TOLES LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERT_TRANSFER, '%d/%m/%Y') LIKE '%$var_search%'  "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$row[] = $key->NOM .' '.$key->PRENOM;
		$row[] = $key->produit;
		$row[] = $key->ID_TYPE_PRODUIT=1 ? $key->DESCRIPTION_TOLES .'('.$key->NUMERO_COLIS.')' : $key->DESCRIPTION;
		$row[] = $key->QUANTITE;		
		$row[] = date("d/m/Y", strtotime($key->DATE_INSERT_TRANSFER));


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

