<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commander extends MY_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->Is_Connected();
        // $this->creationcarte();

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
		$data['title']='Ajouter une commande';
		$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN stock_matieres_premieres ON stock_matieres_premieres.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE  stock_matieres_premieres.QUANTITE_RECUE >0 AND stock_matieres_premieres.ID_STATUT_MATIERE=2 order by type_matieres.DESCRIPTION');
		$this->load->view('Commander_Add_View',$data);
	}


	function addcart()

	{

		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$QUANTITE_TONNE=$this->input->post('QUANTITE_TONNE');
		$nom=str_replace(' ', '_', 'pro');
		$id_cart=$ID_TYPE_MATIERE."".$QUANTITE_TONNE."".$nom;
		$datass = array(
			'id'      => $id_cart,
			'qty'     => 1,
			'price'   => 1,
			'name'    => 'prod',
			'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
			'QUANTITE_TONNE'=>$QUANTITE_TONNE,
		);

		$this->cart->insert($datass);

		$table='<div class="table-responsive"><table class="table table-bordered table-striped table-hover table-condensed">
		<tr>

		<td>#</td>
		<td>type de matieres</td>
		<td>Quantite</td>
		<td>Supprimer</td>
		</tr>
		';
		$i=0;
		$j=1;

		foreach ($this->cart->contents() as $items) {
			$i++;
			$j++;
			$type=$this->Model->getRequeteOne('SELECT * FROM `type_matieres` WHERE  `ID_TYPE_MATIERE`='.$items['ID_TYPE_MATIERE']);

			$table.='<tr>

			<td>'.$i.'</td>
			<td>'.$type["DESCRIPTION"].' ( '.$type["CARACTERISTIQUE"].' )</td>
			<td>'.$items["QUANTITE_TONNE"].'</td>
			<td><input type="hidden" id="rowid'.$j.'" value='.$items['rowid'].'>
			<button class="btn btn-danger btn-xs" type="button" onclick="remove_ct('.$j.')">x</button>
			</td>
			</tr>';
		}

		$table.='</table> <div>

		<div class="card-footer">
		<button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
		</div>

		</div></div>';

		echo $table;
	}


	function remove_cart()

	{

		$rowid = $this->input->post('rowid');
		$this->cart->remove($rowid);
		$table = null;
		$i = 0;
		$j=1;

		$table='<div class="table-responsive"><table class="table table-bordered table-striped table-hover table-condensed">
		<tr>
		<td>#</td>
		<td>type de matieres</td>
		<td>Quantite</td>
		<td>Supprimer</td>
		</tr>
		';
		$i=0;
		$j=1;

		foreach ($this->cart->contents() as $items) {
			$i++;
			$j++;
			$type=$this->Model->getRequeteOne('SELECT * FROM `type_matieres` WHERE  `ID_TYPE_MATIERE`='.$items['ID_TYPE_MATIERE']);

			$table.='<tr>

			<td>'.$i.'</td>
			<td>'.$type["DESCRIPTION"].' ( '.$type["DESCRIPTION"].' )</td>
			<td>'.$items["QUANTITE_TONNE"].'</td>
			<td><input type="hidden" id="rowid'.$j.'" value='.$items['rowid'].'>
			<button class="btn btn-danger btn-xs" type="button" onclick="remove_ct('.$j.')">x</button>
			</td>
			</tr>';
		}

		$table.='</table> <div>

		<div class="card-footer">
		<button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
		</div>

		</div></div>';

		echo $table;
	}


	public function ajouter()
	{

		foreach ($this->cart->contents() as $items)

		{


			
			$id_user= $this->session->userdata('SUPERBAT_ID_USER');

			$array = array(
				
				'QUANTITE_TONNE'=>$items['QUANTITE_TONNE'],
				'ID_TYPE_MATIERE'=>$items['ID_TYPE_MATIERE'],
				'ID_USER_DEMANDEUR'=>$id_user

			);
			$id_commande_mp = $this->Model->insert_last_id('commande_production_matieres_premiers',$array);

			$datahisto = array(
				'ID_COMANDE_PROD'=>$id_commande_mp,
				'QUANTITE_TONNE'=>$items['QUANTITE_TONNE'],
				'ID_TYPE_MATIERE'=>$items['ID_TYPE_MATIERE'],
				'ID_USER_DEMANDEUR'=>$id_user,
				'ID_STATUT_CO_MP'=>1
			);

			$this->Model->insert_last_id('histo_commande_production_matieres_premiers',$datahisto);

		}

		$data['message']="<div class='alert alert-success text-center' id ='message'><b>Création d'un type de document fait avec succès</b>.</div>";
		$this->session->set_flashdata($data);
		$this->cart->destroy();
		redirect(base_url('production/Commander'));

	}

	function liste(){
		$data['title']='Liste commande';
		
		$this->load->view('Commander_List_View',$data);
	}

	public function listing()
	{

		$query_principal ="SELECT `ID_COMANDE_PROD`, smp.NUMERO_COLIS, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP,cpmp.ID_STATUT_CO_MP FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE scm.ID_STATUT_CO_MP=1" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_COMANDE_PROD','user_demandeur.NOM','type_matieres.DESCRIPTION', 'NOMBRE_COLIS','QUANTITE_TONNE','DATE_INSERTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_COMANDE_PROD ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND user_demandeur.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR QUANTITE_TONNE LIKE '%$var_search%' OR type_matieres.DESCRIPTION LIKE '%$var_search%'  OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = $key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')';
			$row[] = $key->NUMERO_COLIS;
			$row[] = $key->QUANTITE_TONNE;	
			$row[] = $key->DESCRIPTION_CO_MP;	
			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));
			
			$options = '
			<div class="modal fade" id="rendreeff'.$key->ID_COMANDE_PROD.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Effacer</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<form id="FormData" action="'.base_url("production/Commander/effacer/".$key->ID_COMANDE_PROD).'" >
			<div class="modal-body">

			voulez vous supprimer le fournisseur '.$key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')
			</div>
			<div class="modal-footer">
			<button type="submit" class="btn btn-secondary" >Supprimer</button>
			</div>
			</div>
			</div>
			</div>

			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">';
			if($key->ID_STATUT_CO_MP==1){
				$options .= '<a class="dropdown-item" href="'.base_url("production/Commander/index_update/".$key->ID_COMANDE_PROD).'">
				<i class="fa fa-edit"></i> Modifier
				</a>
				<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_COMANDE_PROD.'">
				<i class="fa fa-trash"></i> Supprimer
				</a>

				';
			}
			$options .='
			<a class="dropdown-item" onclick="get_histo('.$key->ID_COMANDE_PROD.')">
			<i class="fa fa-eye"></i> Détails
			</a>
			</div>
			</div>';


			$row[] =$options;
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


	public function listing_livrer()
	{

		$query_principal ="SELECT `ID_COMANDE_PROD`, smp.NUMERO_COLIS, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,QUANTITE_CONSOME,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP,cpmp.ID_STATUT_CO_MP FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE cpmp.ID_STATUT_CO_MP!=1" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_COMANDE_PROD','user_demandeur.NOM','type_matieres.DESCRIPTION', 'NOMBRE_COLIS','QUANTITE_TONNE','DATE_INSERTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_COMANDE_PROD ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND user_demandeur.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR QUANTITE_TONNE LIKE '%$var_search%' OR type_matieres.DESCRIPTION LIKE '%$var_search%'  OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = $key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')';
			$row[] = $key->NUMERO_COLIS;
			$row[] = $key->QUANTITE_TONNE ? : 0;
			$row[] = $key->QUANTITE_CONSOME ? $key->QUANTITE_CONSOME  : 0;
			$row[] = $key->DESCRIPTION_CO_MP;		
			$row[] = date("d/m/Y", strtotime($key->DATE_INSERTION));

			$options = '
			<div class="modal fade" id="rendreeff'.$key->ID_COMANDE_PROD.'" tabindex="-1" role="dialog" aria-labelledby="modalLabel'.$key->ID_COMANDE_PROD.'" aria-hidden="true">
			<div class="modal-dialog" role="document">
			<div class="modal-content">

			<!-- HEADER -->
			<div class="modal-header">
			<h5 class="modal-title" id="modalLabel'.$key->ID_COMANDE_PROD.'">Modifier réception</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>

			<!-- BODY -->
			<form id="FormData'.$key->ID_COMANDE_PROD.'" method="post" action="'.base_url("stock_matieres/Stock_matieres/modifi_reception").'">
			<div class="modal-body">
			<input type="hidden" name="ID_COMANDE_PROD" value="'.$key->ID_COMANDE_PROD.'">

			<div class="form-group col-sm-10">
			<label for="MOTIF'.$key->ID_COMANDE_PROD.'">Motif <span class="text-danger">*</span></label>
			<textarea 
			id="MOTIF'.$key->ID_COMANDE_PROD.'"
			name="MOTIF"
			class="form-control"
			rows="4"
			required
			placeholder="Saisir le motif de la modification..."
			></textarea>
			<small id="erreurMotif'.$key->ID_COMANDE_PROD.'" class="text-danger"></small>
			</div>
			</div>

			<!-- FOOTER -->
			<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
			<button type="submit" class="btn btn-primary">Retourner</button>
			</div>
			</form>

			</div>
			</div>
			</div>

			<!-- ACTIONS DROPDOWN -->
			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" onclick="get_histo('.$key->ID_COMANDE_PROD.')">
			<i class="fa fa-eye"></i> Détails
			</a>
			</div>
			</div>';
			$row[] =$options;

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


	public function index_update($id)
	{

		$data['title']='Modifier une commande';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `commande_production_matieres_premiers` WHERE ID_COMANDE_PROD = '.$id.'');
		$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN stock_matieres_premieres ON stock_matieres_premieres.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE  stock_matieres_premieres.QUANTITE_RECUE >0 order by type_matieres.DESCRIPTION');
		$this->load->view('Commander_Update_View',$data);

	}

	public function update()
	{
		$QUANTITE_TONNE=$this->input->post('QUANTITE_TONNE');
		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		// $ID_USER_DEMANDEUR=$this->input->post('ID_USER_DEMANDEUR');
		$ID_COMANDE_PROD=$this->input->post('ID_COMANDE_PROD');

		$this->form_validation->set_rules('QUANTITE_TONNE', 'Quantite', 'required');
		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Type de matieres', 'required');


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

			$datasprofil=array(
				
				'QUANTITE_TONNE'=>$QUANTITE_TONNE,
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
			);

			$this->Model->update('commande_production_matieres_premiers',array('ID_COMANDE_PROD'=>$ID_COMANDE_PROD),$datasprofil);
		}

		$message = "<div class='alert alert-success' id='message'>

		Commande Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('production/Commander'));  

	}

	public function effacer($id)
	{


		$this->Model->delete('commande_production_matieres_premiers',array('ID_COMANDE_PROD'=>$id));
		$message = "<div class='alert alert-success' id='message'>

		Commande Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('production/Commander'));  

	}



	public function get_details()
	{

		$id=$this->input->post('id');
		$query_principal ="SELECT `ID_COMANDE_PROD`, smp.NUMERO_COLIS, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP FROM `histo_commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE cpmp.ID_COMANDE_PROD=".$id ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_COMANDE_PROD','user_demandeur.NOM','type_matieres.DESCRIPTION', 'NOMBRE_COLIS','QUANTITE_TONNE','DATE_INSERTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_COMANDE_PROD ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND user_demandeur.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR QUANTITE_TONNE LIKE '%$var_search%' OR type_matieres.DESCRIPTION LIKE '%$var_search%'  OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = $key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')';
			$row[] = $key->NUMERO_COLIS;
			$row[] = $key->QUANTITE_TONNE;
			$row[] = $key->DESCRIPTION_CO_MP;		
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
