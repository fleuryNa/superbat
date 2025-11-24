<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produits_Finis extends MY_Controller {

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

	public function index()
	{
		$data['title']="Liste de Produits";
		$this->load->view('Produits_Finis_List_View',$data);
	}

	
	public function listing()
	{

		$query_principal ="SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, stock_production.ID_TYPE_TOLES,type_toles.DESCRIPTION_TOLES, `ID_USER_EXPEDITEUR`,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION` FROM stock_production JOIN type_toles ON type_toles.ID_TYPE_TOLES=stock_production.ID_TYPE_TOLES JOIN type_clous ON type_clous.ID_TYPE_CLOUS =stock_production.ID_TYPE_TOLES JOIN admin_user ON admin_user.ID_USER=stock_production.ID_USER_EXPEDITEUR WHERE ID_TYPE_PRODUIT =1" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_PRODUCTION', 'produit','type_clous.DESCRIPTION', 'type_toles.DESCRIPTION_TOLES','admin_user.NOM', 'admin_user.PRENOM', 'NUMERO_COLIS', 'LONGUEUR', 'NOMBRE_BANDE', 'NOMBRE_BONNET', 'QUANTITE', 'DATE_INSERTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND produit LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%' OR type_toles.DESCRIPTION_TOLES LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR admin_user.NUMERO_COLIS LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();



			$row[] = $key->produit;
			$row[] =  $key->DESCRIPTION_TOLES;
			
			$row[] = $key->NUMERO_COLIS.'('.$key->COULEUR.')' ;
			$row[] = $key->LONGUEUR ? $key->LONGUEUR : '-' ;
			$row[] = $key->NOMBRE_BANDE.'('.$key->NOMBRE_BONNET.')';
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));


			$row[] = '
			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("production/Production/index_update/".$key->ID_PRODUCTION).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
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

		$query_principal ="SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, stock_production.ID_TYPE_TOLES, `ID_USER_EXPEDITEUR`,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION` FROM stock_production JOIN type_clous ON type_clous.ID_TYPE_CLOUS =stock_production.ID_TYPE_CLOUS JOIN admin_user ON admin_user.ID_USER=stock_production.ID_USER_EXPEDITEUR WHERE ID_TYPE_PRODUIT =2" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_PRODUCTION', 'produit','type_clous.DESCRIPTION','admin_user.NOM', 'admin_user.PRENOM', 'NUMERO_COLIS', 'LONGUEUR', 'QUANTITE', 'DATE_INSERTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND produit LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%'  LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();



			$row[] = $key->produit;
			$row[] = $key->DESCRIPTION;
			$row[] = $key->QUANTITE ;
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));


			$row[] = '
			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("production/Production/index_update/".$key->ID_PRODUCTION).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
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


}

