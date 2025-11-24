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



	public function add() {

		$json = $this->input->post('cart_data');
		$cart = json_decode($json, true);

		foreach($cart as $item){

			if($item['type'] == "Tôles"){
				$data = [
					'ID_TYPE_PRODUIT' => 1,
					'ID_TYPE_TOLES' => $item['id_type'],
					'NUMERO_COLIS'  => $item['colis'],
					'COULEUR'       => $item['couleur'],
					'LONGUEUR'      => $item['longueur'],
					'NOMBRE_BANDE'  => $item['bande'],
					'NOMBRE_BONNET' => $item['bonnet'],
					'ID_USER_EXPEDITEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
				];
			}
        else { // clous
        	$data = [
        		'ID_TYPE_PRODUIT' => 2,
        		'ID_TYPE_CLOUS' => $item['id_type'],
        		'QUANTITE'      => $item['quantite'],
        		'ID_USER_EXPEDITEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
        	];
        }

        $this->db->insert('stock_production', $data);
    }

    $message = "<div class='alert alert-success' id='message'>

    Production enregistr&eacute; avec succés

    <button type='button' class='close' data-dismiss='alert'>&times;</button>

    </div>";

    $this->session->set_flashdata(array('message'=>$message));

    redirect(base_url('production/Production')); 
}


function Transferer($value='')
{
	$data['title']='Transferer dans stock des produits finis';
	$data['type_toles']=$this->Model->getRequete('SELECT * FROM `type_toles` order by DESCRIPTION_TOLES');
	$data['type_clous']=$this->Model->getRequete('SELECT * FROM `type_clous` order by DESCRIPTION');
	$this->load->view('Transferer_Add_View',$data);
}



public function addcart()
{
	$ID_TYPE_PRODUIT = $this->input->post('ID_TYPE_PRODUIT');
	$ID_TYPE_MATIERE = $this->input->post('ID_TYPE_MATIERE');
	$NUMERO_COLIS    = $this->input->post('NUMERO_COLIS');
	$QUANTITE        = $this->input->post('QUANTITE');


	$id_cart = $ID_TYPE_PRODUIT."_".$ID_TYPE_MATIERE."_".$QUANTITE;



	$datass = array(
		'id'      => $id_cart,
		'qty'     => 1,
		'price'   => 1,
		'name'    => 'cmd',
		'ID_TYPE_PRODUIT' => $ID_TYPE_PRODUIT,
		'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
		'NUMERO_COLIS'    => $NUMERO_COLIS,
		'QUANTITE'        => $QUANTITE
	);

	$this->cart->insert($datass);

    // construction du tableau
	$table = '
	<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover">
	<tr>
	<th>#</th>
	<th>Type produit</th>
	<th>Type matière</th>
	<th>Colis</th>
	<th>Quantité</th>
	<th>Supprimer</th>
	</tr>';

	$i = 0; $j = 1;

	foreach ($this->cart->contents() as $items) {
		$i++; $j++;

        if ($items["ID_TYPE_PRODUIT"] == 1) { // TOLES
        	$type = $this->Model->getRequeteOne("
        		SELECT DESCRIPTION_TOLES 
        		FROM type_toles 
        		WHERE ID_TYPE_TOLES=".$items['ID_TYPE_MATIERE']
        	);
        	$produit = "Tôles";
        	$description = $type["DESCRIPTION_TOLES"];
        } else { // CLOUS
        	$type = $this->Model->getRequeteOne("
        		SELECT DESCRIPTION 
        		FROM type_clous 
        		WHERE ID_TYPE_CLOUS=".$items['ID_TYPE_MATIERE']
        	);
        	$produit = "Clous";
        	$description = $type["DESCRIPTION"];
        }

        $table .= '
        <tr>
        <td>'.$i.'</td>
        <td>'.$produit.'</td>
        <td>'.$description.'</td>
        <td>'.$items["NUMERO_COLIS"].'</td>
        <td>'.$items["QUANTITE"].'</td>
        <td>
        <input type="hidden" id="rowid'.$j.'" value="'.$items['rowid'].'">
        <button class="btn btn-danger btn-xs" onclick="remove_ct('.$j.')">x</button>
        </td>
        </tr>';
    }

    $table .= "</table></div>";

    if(count($this->cart->contents()) > 0){
    	$table .= '
    	<div class="card-footer">
    	<button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
    	</div>';

    }

    echo $table;
}



function remove_cart()
{

	$rowid = $this->input->post('rowid');
	$this->cart->remove($rowid);
	$table = null;
	$i = 0;
	$j=1;


	$table = '
	<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover">
	<tr>
	<th>#</th>
	<th>Type produit</th>
	<th>Type matière</th>
	<th>Colis</th>
	<th>Quantité</th>
	<th>Supprimer</th>
	</tr>';

	foreach ($this->cart->contents() as $items) {
		$i++; $j++;

        if ($items["ID_TYPE_PRODUIT"] == 1) { // TOLES
        	$type = $this->Model->getRequeteOne("
        		SELECT DESCRIPTION_TOLES 
        		FROM type_toles 
        		WHERE ID_TYPE_TOLES=".$items['ID_TYPE_MATIERE']
        	);
        	$produit = "Tôles";
        	$description = $type["DESCRIPTION_TOLES"];
        } else { // CLOUS
        	$type = $this->Model->getRequeteOne("
        		SELECT DESCRIPTION 
        		FROM type_clous 
        		WHERE ID_TYPE_CLOUS=".$items['ID_TYPE_MATIERE']
        	);
        	$produit = "Clous";
        	$description = $type["DESCRIPTION"];
        }

        $table .= '
        <tr>
        <td>'.$i.'</td>
        <td>'.$produit.'</td>
        <td>'.$description.'</td>
        <td>'.$items["NUMERO_COLIS"].'</td>
        <td>'.$items["QUANTITE"].'</td>
        <td>
        <input type="hidden" id="rowid'.$j.'" value="'.$items['rowid'].'">
        <button class="btn btn-danger btn-xs" onclick="remove_ct('.$j.')">x</button>
        </td>
        </tr>';
    }

    $table .= "</table></div>";

    if(count($this->cart->contents()) > 0){
    	$table .= '
    	<div class="card-footer">
    	<button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
    	</div>';
    }

    // print_r($table);exit();

    echo $table;
}



public function save_transfer()
{
	foreach ($this->cart->contents() as $item) {

        // Si produit = TOLES
		$ID_TYPE_TOLES = ($item["ID_TYPE_PRODUIT"] == 1) ? $item["ID_TYPE_MATIERE"] : NULL;

        // Si produit = CLOUS
		$ID_TYPE_CLOUS = ($item["ID_TYPE_PRODUIT"] == 2) ? $item["ID_TYPE_MATIERE"] : NULL;

		$data = array(
			'ID_TYPE_PRODUIT'     => $item["ID_TYPE_PRODUIT"],
			'ID_TYPE_TOLES'       => $ID_TYPE_TOLES,
			'ID_TYPE_CLOUS'       => $ID_TYPE_CLOUS,
			'QUANTITE'            => $item["QUANTITE"],
			'NUMERO_COLIS'        => $item["NUMERO_COLIS"],
			'DATE_INSERT_TRANSFER'=> date('Y-m-d H:i:s')
		);

		$this->Model->create('transferer_prod_stock', $data);
	}
	$data['message']="<div class='alert alert-success text-center' id ='message'><b>Création d'un type de document fait avec succès</b>.</div>";
	$this->session->set_flashdata($data);
	$this->cart->destroy();
	redirect(base_url('production/Production'));
}






}