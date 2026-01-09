<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production extends MY_Controller {

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
		$this->load->view('Production_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="
		SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, stock_production.ID_TYPE_TOLES,type_toles.DESCRIPTION_TOLES, `ID_USER_EXPEDITEUR`,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION`,statut_produits.DESCRIPTION_PRODUITS,stock_production.ID_STATUT_PRODUITS FROM stock_production JOIN type_toles ON type_toles.ID_TYPE_TOLES=stock_production.ID_TYPE_TOLES JOIN type_clous ON type_clous.ID_TYPE_CLOUS =stock_production.ID_TYPE_TOLES JOIN admin_user ON admin_user.ID_USER=stock_production.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=stock_production.ID_STATUT_PRODUITS WHERE ID_TYPE_PRODUIT =1" ;

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


			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
			$row[] = $key->produit;
			$row[] =  $key->DESCRIPTION_TOLES;
			$row[] = $key->NUMERO_COLIS.'('.$key->COULEUR.')' ;
			$row[] = $key->LONGUEUR ? $key->LONGUEUR : '-' ;
			$row[] = $key->NOMBRE_BANDE.'('.$key->NOMBRE_BONNET.')';
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->DESCRIPTION_PRODUITS ;

			if($key->ID_STATUT_PRODUITS==1){
				$row[] = '
				<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
				</button>
				<div class="dropdown-menu">
				<a class="dropdown-item" href="'.base_url("production/Production/index_update/".$key->ID_PRODUCTION).'">
				<i class="fa fa-edit"></i> Modifier
				</a>
				<a class="dropdown-item" onclick="get_histo('.$key->ID_PRODUCTION.')">
				<i class="fa fa-eye"></i> Details
				</a>
				</div>';
			}else{
				$row[] = '
				<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
				</button>
				<div class="dropdown-menu">
				
				<a class="dropdown-item" onclick="get_histo('.$key->ID_PRODUCTION.')">
				<i class="fa fa-eye"></i> Details
				</a>
				</div>';
			}
			


			
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

		$query_principal ="SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, stock_production.ID_TYPE_TOLES, `ID_USER_EXPEDITEUR`,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION`,statut_produits.DESCRIPTION_PRODUITS,stock_production.ID_STATUT_PRODUITS FROM stock_production JOIN type_clous ON type_clous.ID_TYPE_CLOUS =stock_production.ID_TYPE_CLOUS JOIN admin_user ON admin_user.ID_USER=stock_production.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=stock_production.ID_STATUT_PRODUITS WHERE ID_TYPE_PRODUIT =2" ;

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


			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
			$row[] = $key->produit;
			$row[] = $key->DESCRIPTION;
			$row[] = $key->QUANTITE ;
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->DESCRIPTION_PRODUITS ;


			if($key->ID_STATUT_PRODUITS==1){
				$row[] = '
				<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
				</button>
				<div class="dropdown-menu">
				<a class="dropdown-item" href="'.base_url("production/Production/index_update/".$key->ID_PRODUCTION).'">
				<i class="fa fa-edit"></i> Modifier
				</a>

				<a class="dropdown-item" onclick="get_histo_clous('.$key->ID_PRODUCTION.')">
				<i class="fa fa-eye"></i> Details
				</a>
				</div>';
			}else{
				$row[] = '
				<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
				</button>
				<div class="dropdown-menu">
				
				<a class="dropdown-item" onclick="get_histo_clous('.$key->ID_PRODUCTION.')">
				<i class="fa fa-eye"></i> Details
				</a>
				</div>';
			}
			

			
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



	public function ajouter($value='')
	{
		$data['title']='Ajouter dans stock production';
		$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` order by DESCRIPTION_TOLES');
		$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` order by DESCRIPTION');
		$this->load->view('Production_Add_View',$data);
	}

	public function addTypeToles()
	{

		$DESCRIPTION_TOLES=$this->input->post('DESCRIPTION_TOLES');


		$datas=array('DESCRIPTION_TOLES'=>$DESCRIPTION_TOLES);
		$ID_TYPE_TOLES = $this->Model->insert_last_id('type_toles',$datas);

		echo '<option value="'.$ID_TYPE_TOLES.'">'.$this->input->post('DESCRIPTION_TOLES').'</option>';

	}

	public function addTypeClous()
	{

		$DESCRIPTION=$this->input->post('DESCRIPTION');


		$datas=array('DESCRIPTION'=>$DESCRIPTION);
		$ID_TYPE_CLOUS = $this->Model->insert_last_id('type_clous',$datas);

		echo '<option value="'.$ID_TYPE_CLOUS.'">'.$this->input->post('DESCRIPTION').'</option>';

	}



	public function addsss()
	{

		$ID_TYPE_PRODUIT=$this->input->post('ID_TYPE_PRODUIT');

		$ID_TYPE_CLOUS=$this->input->post('ID_TYPE_CLOUS');
		$QUANTITE=$this->input->post('QUANTITE');

		$ID_TYPE_TOLES=$this->input->post('ID_TYPE_TOLES');
		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');
		$COULEUR=$this->input->post('COULEUR');
		$LONGUEUR=$this->input->post('LONGUEUR');
		$NOMBRE_BANDE=$this->input->post('NOMBRE_BANDE');
		$NOMBRE_BONNET=$this->input->post('NOMBRE_BONNET');

		if ($ID_TYPE_PRODUIT==1) {
			$this->form_validation->set_rules('ID_TYPE_TOLES', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NUMERO_COLIS', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('COULEUR', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('LONGUEUR', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NOMBRE_BANDE', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NOMBRE_BONNET', 'Nom du Profil', 'required');
		}
		if ($ID_TYPE_PRODUIT==2) {
			$this->form_validation->set_rules('ID_TYPE_CLOUS', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('QUANTITE', 'Nom du Profil', 'required');
		}

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Stock Production non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Ajouter dans stock production';
			$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` order by DESCRIPTION_TOLES');
			$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` order by DESCRIPTION');
			$this->load->view('Production_Add_View',$data);
		}else{

			$datas=array(
				'ID_TYPE_PRODUIT'=>$ID_TYPE_PRODUIT,
				'ID_TYPE_CLOUS'=>$ID_TYPE_CLOUS,
				'QUANTITE'=>$QUANTITE,
				'ID_TYPE_TOLES'=>$ID_TYPE_TOLES,
				'NUMERO_COLIS'=>$NUMERO_COLIS,
				'COULEUR'=>$COULEUR,
				'LONGUEUR'=>$LONGUEUR,
				'NOMBRE_BANDE'=>$NOMBRE_BANDE,
				'NOMBRE_BONNET'=>$NOMBRE_BONNET,
				'ID_USER_EXPEDITEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
			);




			$ID_STOCK_MATIERE = $this->Model->insert_last_id('stock_production',$datas);

			

		}



		$message = "<div class='alert alert-success' id='message'>

		Production enregistr&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('production/Production'));  

	}



	public function add()
	{
		$json = $this->input->post('cart_data');
		$cart = json_decode($json, true);

		if (empty($cart)) {
			redirect(base_url('production/Production'));
		}

		$this->db->trans_begin();

		foreach ($cart as $item) {

			/* ====================== TOLES ====================== */
			if ($item['type'] === "Tôles") {

				$donne_existant = $this->Model->getOne(
					'stock_production',
					[
						'ID_TYPE_TOLES' => $item['id_type'],
						'NUMERO_COLIS'  => $item['colis'],
						'COULEUR'       => $item['couleur'],
						'LONGUEUR'      => $item['longueur']
					]
				);

				if (!empty($donne_existant)) {

                // Mise à jour stock
					$this->Model->update(
						'stock_production',
						['ID_PRODUCTION' => $donne_existant['ID_PRODUCTION']],
						[
							'NOMBRE_BONNET' =>
							$donne_existant['NOMBRE_BONNET'] + $item['bonnet']
						]
					);

                // Historique
					$historiqueProduction = [
						'ID_PRODUCTION'   => $donne_existant['ID_PRODUCTION'],
						'ID_TYPE_PRODUIT' => 1,
						'ID_TYPE_TOLES'   => $donne_existant['ID_TYPE_TOLES'],
						'NUMERO_COLIS'    => $item['colis'],
						'COULEUR'         => $item['couleur'],
						'LONGUEUR'        => $item['longueur'],
						'NOMBRE_BANDE'    => $item['bande'],
						'NOMBRE_BONNET'   => $item['bonnet'],
						'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
						'DATE_INSERTION'  => date('Y-m-d H:i:s')
					];

				} else {

                // Nouveau stock
					$data = [
						'ID_TYPE_PRODUIT'     => 1,
						'ID_TYPE_TOLES'       => $item['id_type'],
						'NUMERO_COLIS'        => $item['colis'],
						'COULEUR'             => $item['couleur'],
						'LONGUEUR'            => $item['longueur'],
						'NOMBRE_BANDE'        => $item['bande'],
						'NOMBRE_BONNET'       => $item['bonnet'],
						'ID_USER_EXPEDITEUR'  => $this->session->userdata('SUPERBAT_ID_USER')
					];

					$id = $this->Model->insert_last_id('stock_production', $data);

					$historiqueProduction = [
						'ID_PRODUCTION'   => $id,
						'ID_TYPE_PRODUIT' => 1,
						'ID_TYPE_TOLES'   => $item['id_type'],
						'NUMERO_COLIS'    => $item['colis'],
						'COULEUR'         => $item['couleur'],
						'LONGUEUR'        => $item['longueur'],
						'NOMBRE_BANDE'    => $item['bande'],
						'NOMBRE_BONNET'   => $item['bonnet'],
						'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
						'DATE_INSERTION'  => date('Y-m-d H:i:s')
					];
				}

				$this->Model->create('historique_stock_production', $historiqueProduction);

			}
			/* ====================== CLOUS ====================== */
			else {

				$donne_existant = $this->Model->getOne(
					'stock_production',
					['ID_TYPE_CLOUS' => $item['id_type']]
				);

				if (!empty($donne_existant)) {

					$this->Model->update(
						'stock_production',
						['ID_PRODUCTION' => $donne_existant['ID_PRODUCTION']],
						[
							'QUANTITE' =>
							$donne_existant['QUANTITE'] + $item['quantite']
						]
					);

					$historiqueProduction = [
						'ID_PRODUCTION'   => $donne_existant['ID_PRODUCTION'],
						'ID_TYPE_PRODUIT' => 2,
						'ID_TYPE_CLOUS'   => $donne_existant['ID_TYPE_CLOUS'],
						'QUANTITE'        => $item['quantite'],
						'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
						'DATE_INSERTION'  => date('Y-m-d H:i:s')
					];

				} else {

					$data = [
						'ID_TYPE_PRODUIT'     => 2,
						'ID_TYPE_CLOUS'       => $item['id_type'],
						'QUANTITE'            => $item['quantite'],
						'ID_USER_EXPEDITEUR'  => $this->session->userdata('SUPERBAT_ID_USER')
					];

					$id = $this->Model->insert_last_id('stock_production', $data);

					$historiqueProduction = [
						'ID_PRODUCTION'   => $id,
						'ID_TYPE_PRODUIT' => 2,
						'ID_TYPE_CLOUS'   => $item['id_type'],
						'QUANTITE'        => $item['quantite'],
						'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
						'DATE_INSERTION'  => date('Y-m-d H:i:s')
					];
				}

				$this->Model->create('historique_stock_production', $historiqueProduction);
			}
		}

		/* ====================== TRANSACTION ====================== */
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$message = "
		<div class='alert alert-success' id='message'>
		Production enregistrée avec succès
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		</div>";

		$this->session->set_flashdata(['message' => $message]);
		redirect(base_url('production/Production'));
	}



	public function index_update($id)
	{

		$data['title']='Modifier une commande';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `stock_production` WHERE ID_PRODUCTION = '.$id.'');
		$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` order by DESCRIPTION_TOLES');
		$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` order by DESCRIPTION');
		$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN commande_production_matieres_premiers ON commande_production_matieres_premiers.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE commande_production_matieres_premiers.QUANTITE_TONNE>0 AND commande_production_matieres_premiers.ID_STATUT_CO_MP=2 order by type_matieres.DESCRIPTION');
		$this->load->view('Production_Update_View',$data);

	}

	public function update()
	{
		$ID_PRODUCTION=$this->input->post('ID_PRODUCTION');
		$ID_TYPE_PRODUIT=$this->input->post('ID_TYPE_PRODUIT');

		$ID_TYPE_CLOUS=$this->input->post('ID_TYPE_CLOUS');
		$QUANTITE=$this->input->post('QUANTITE');

		$ID_TYPE_TOLES=$this->input->post('ID_TYPE_TOLES');
		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');
		$COULEUR=$this->input->post('COULEUR');
		$LONGUEUR=$this->input->post('LONGUEUR');
		$NOMBRE_BANDE=$this->input->post('NOMBRE_BANDE');
		$NOMBRE_BONNET=$this->input->post('NOMBRE_BONNET');

		if ($ID_TYPE_PRODUIT==1) {
			$this->form_validation->set_rules('ID_TYPE_TOLES', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NUMERO_COLIS', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('COULEUR', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('LONGUEUR', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NOMBRE_BANDE', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('NOMBRE_BONNET', 'Nom du Profil', 'required');
		}
		if ($ID_TYPE_PRODUIT==2) {
			$this->form_validation->set_rules('ID_TYPE_CLOUS', 'Nom du Profil', 'required');
			$this->form_validation->set_rules('QUANTITE', 'Nom du Profil', 'required');
		}

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Commander non modifi&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Modifier une commande';
			$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN stock_matieres_premieres ON stock_matieres_premieres.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE  stock_matieres_premieres.QUANTITE_RECUE >0 order by type_matieres.DESCRIPTION');

			$data['data']=$this->Model->getRequeteOne('SELECT * FROM `commande_production_matieres_premiers` WHERE ID_COMANDE_PROD = '.$id.'');

			$this->load->view('Commander_Update_View',$data);

		}else{

			$datas=array(
				'ID_TYPE_PRODUIT'=>$ID_TYPE_PRODUIT,
				'ID_TYPE_CLOUS'=>$ID_TYPE_CLOUS,
				'QUANTITE'=>$QUANTITE,
				'ID_TYPE_TOLES'=>$ID_TYPE_TOLES,
				'NUMERO_COLIS'=>$NUMERO_COLIS,
				'COULEUR'=>$COULEUR,
				'LONGUEUR'=>$LONGUEUR,
				'NOMBRE_BANDE'=>$NOMBRE_BANDE,
				'NOMBRE_BONNET'=>$NOMBRE_BONNET,
				'ID_USER_EXPEDITEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
			);


			$this->Model->update('stock_production',array('ID_PRODUCTION'=>$ID_PRODUCTION),$datas);
		}

		$message = "<div class='alert alert-success' id='message'>

		Produit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('production/Production'));  

	}

	public function effacer($id)
	{


		$this->Model->delete('stock_production',array('ID_PRODUCTION'=>$id));
		$message = "<div class='alert alert-success' id='message'>

		{Produits} Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('production/Production'));  

	}


	public function get_details_clous()
	{
		$id=$this->input->post('id');


		$query_principal ="SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, historique_stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, historique_stock_production.ID_TYPE_TOLES, historique_stock_production.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION`,statut_produits.DESCRIPTION_PRODUITS,historique_stock_production.ID_STATUT_PRODUITS FROM historique_stock_production JOIN type_clous ON type_clous.ID_TYPE_CLOUS =historique_stock_production.ID_TYPE_CLOUS JOIN admin_user ON admin_user.ID_USER=historique_stock_production.ID_USER JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=historique_stock_production.ID_STATUT_PRODUITS WHERE ID_TYPE_PRODUIT =2 AND ID_PRODUCTION=".$id ;

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


			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
			$row[] = $key->produit;
			$row[] = $key->DESCRIPTION;
			$row[] = $key->QUANTITE ;
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->DESCRIPTION_PRODUITS ;


			
			
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


	public function get_details()
	{

		$id=$this->input->post('id');


		$query_principal ="
		SELECT `ID_PRODUCTION`, `NUMERO_COLIS`, `LONGUEUR`, `COULEUR`, `NOMBRE_BANDE`, `NOMBRE_BONNET`, `QUANTITE`, `ID_TYPE_PRODUIT`, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, historique_stock_production.ID_TYPE_CLOUS,type_clous.DESCRIPTION, historique_stock_production.ID_TYPE_TOLES,type_toles.DESCRIPTION_TOLES, historique_stock_production.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION`,statut_produits.DESCRIPTION_PRODUITS,historique_stock_production.ID_STATUT_PRODUITS FROM historique_stock_production JOIN type_toles ON type_toles.ID_TYPE_TOLES=historique_stock_production.ID_TYPE_TOLES JOIN type_clous ON type_clous.ID_TYPE_CLOUS =historique_stock_production.ID_TYPE_TOLES JOIN admin_user ON admin_user.ID_USER=historique_stock_production.ID_USER JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=historique_stock_production.ID_STATUT_PRODUITS WHERE ID_TYPE_PRODUIT =1 AND ID_PRODUCTION=".$id ;

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


			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
			$row[] = $key->produit;
			$row[] =  $key->DESCRIPTION_TOLES;
			$row[] = $key->NUMERO_COLIS.'('.$key->COULEUR.')' ;
			$row[] = $key->LONGUEUR ? $key->LONGUEUR : '-' ;
			$row[] = $key->NOMBRE_BANDE.'('.$key->NOMBRE_BONNET.')';
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->DESCRIPTION_PRODUITS ;

			

			
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