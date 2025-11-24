<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends MY_Controller {

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

		$data['title']='Ajouter un Fournisseur';
		$this->load->view('Fournisseur_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="SELECT `ID_FOURNISSEUR`, `NOM`, `EMAIL`, `TELEPHONE`, `LOCALITE` FROM `fournisseur` WHERE 1 " ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('NOM', 'EMAIL','TELEPHONE','LOCALITE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND NOM LIKE '%$var_search%' OR EMAIL LIKE '%$var_search%' OR TELEPHONE LIKE '%$var_search%' OR LOCALITE LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->NOM;
			$row[] = $key->EMAIL;
			$row[] = $key->TELEPHONE;
			$row[] = $key->LOCALITE;
			
			$row[] = '
			<div class="modal fade" id="rendreeff'.$key->ID_FOURNISSEUR.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Effacer</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<form id="FormData" action="'.base_url("stock_matieres/Fournisseur/effacer/".$key->ID_FOURNISSEUR).'" >
			<div class="modal-body">

			voulez vous supprimer le fournisseur '.$key->NOM.'
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
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("stock_matieres/Fournisseur/index_update/".$key->ID_FOURNISSEUR).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_FOURNISSEUR.'">
			<i class="fa fa-eye"></i> Supprimer
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




	public function ajouter()

	{

		$data['title']='Ajouter un Fournisseur';

		$this->load->view('Fournisseur_Add_View',$data);



	}


	public function add()
	{

		$NOM=$this->input->post('NOM');
		$EMAIL=$this->input->post('EMAIL');
		$TELEPHONE=$this->input->post('TELEPHONE');
		$LOCALITE=$this->input->post('LOCALITE');

		$this->form_validation->set_rules('NOM', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('TELEPHONE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('LOCALITE', 'Nom du Profil', 'required');


		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Profil est droit non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Ajouter un Fournisseur';

			$this->load->view('Fournisseur_Add_View',$data);
		}else{

			$datasprofil=array(
				'NOM'=>$NOM,
				'EMAIL'=>$EMAIL,
				'TELEPHONE'=>$TELEPHONE,
				'LOCALITE'=>$LOCALITE
			);

			$FOURNISSEUR_ID = $this->Model->insert_last_id('fournisseur',$datasprofil);

			

		}



		$message = "<div class='alert alert-success' id='message'>

		Fournisseur enregistr&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('stock_matieres/Fournisseur'));  

	}



	public function index_update($id)
	{

		$data['title']='Modifier';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `fournisseur` WHERE ID_FOURNISSEUR = '.$id.'');

		$this->load->view('Fournisseur_Update_View',$data);

	}




	public function update()
	{
		$NOM=$this->input->post('NOM');
		$EMAIL=$this->input->post('EMAIL');
		$TELEPHONE=$this->input->post('TELEPHONE');
		$LOCALITE=$this->input->post('LOCALITE');


		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');

		$this->form_validation->set_rules('NOM', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('TELEPHONE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('LOCALITE', 'Nom du Profil', 'required');



		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Profil est droit non modifi&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Modifier un Fournisseur';

			$this->load->view('Fournisseur_Update_View',$data);

		}
		else{
			$datasprofil=array(
				'NOM'=>$NOM,
				'EMAIL'=>$EMAIL,
				'TELEPHONE'=>$TELEPHONE,
				'LOCALITE'=>$LOCALITE
			);

			$this->Model->update('fournisseur',array('ID_FOURNISSEUR'=>$ID_FOURNISSEUR),$datasprofil);
		}

		$message = "<div class='alert alert-success' id='message'>

		Profil & Droit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('stock_matieres/Fournisseur'));  

	}

	public function effacer($id)
	{
		

		$this->Model->delete('fournisseur',array('ID_FOURNISSEUR'=>$id));
		$message = "<div class='alert alert-success' id='message'>

		Profil & Droit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('stock_matieres/Fournisseur'));  

	}



}
