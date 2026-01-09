<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Type_matieres extends MY_Controller {

	public function index()
	{ 

		$data['title']='Liste Type de matieres';
		$this->load->view('Type_Matieres_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="SELECT `ID_TYPE_MATIERE`, `DESCRIPTION`, `CARACTERISTIQUE`, `IS_ACTIF` FROM `type_matieres` WHERE 1 " ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('DESCRIPTION', 'CARACTERISTIQUE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_TYPE_MATIERE ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND DESCRIPTION LIKE '%$var_search%' OR CARACTERISTIQUE LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->DESCRIPTION;
			$row[] = $key->CARACTERISTIQUE;
			// $row[] = $key->IS_ACTIF ? "Actif" : "Inactif";
			
			$row[] = '
			<div class="modal fade" id="rendreeff'.$key->ID_TYPE_MATIERE.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Effacer</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<form id="FormData" action="'.base_url("stock_matieres/Type_matieres/effacer/".$key->ID_TYPE_MATIERE).'" >
			<div class="modal-body">

			voulez vous supprimer le fournisseur '.$key->DESCRIPTION.'
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
			<a class="dropdown-item" href="'.base_url("stock_matieres/Type_matieres/index_update/".$key->ID_TYPE_MATIERE).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_TYPE_MATIERE.'">
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

		$this->load->view('Type_Matieres_Add_View',$data);



	}


	public function add()
	{

		$DESCRIPTION=$this->input->post('DESCRIPTION');
		$CARACTERISTIQUE=$this->input->post('CARACTERISTIQUE');
		

		$this->form_validation->set_rules('DESCRIPTION', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('CARACTERISTIQUE', 'Nom du Profil', 'required');



		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Type de matieres non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Ajouter type de matieres';

			$this->load->view('Type_Matieres_Add_View',$data);
		}else{

			$datasprofil=array(
				'DESCRIPTION'=>$DESCRIPTION,
				'CARACTERISTIQUE'=>$CARACTERISTIQUE,
				'IS_ACTIF'=>1
				
			);

			$FOURNISSEUR_ID = $this->Model->insert_last_id('type_matieres',$datasprofil);

			

		}



		$message = "<div class='alert alert-success' id='message'>

		Type de matieres enregistr&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('stock_matieres/Type_matieres'));  

	}



	public function index_update($id)
	{

		$data['title']='Modifier type de matiere';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `type_matieres` WHERE ID_TYPE_MATIERE = '.$id.'');

		$this->load->view('Type_Matieres_Update_View',$data);

	}




	public function update()
	{
		$DESCRIPTION=$this->input->post('DESCRIPTION');
		$CARACTERISTIQUE=$this->input->post('CARACTERISTIQUE');


		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');

		$this->form_validation->set_rules('DESCRIPTION', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('CARACTERISTIQUE', 'Nom du Profil', 'required');




		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Profil est droit non modifi&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Modifier type de matiere';

			$this->load->view('Fournisseur_Update_View',$data);

		}
		else{
			$datasprofil=array(
				'DESCRIPTION'=>$DESCRIPTION,
				'CARACTERISTIQUE'=>$CARACTERISTIQUE
			);

			$this->Model->update('type_matieres',array('ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE),$datasprofil);
		}

		$message = "<div class='alert alert-success' id='message'>

		Profil & Droit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('stock_matieres/Type_matieres'));  

	}

	public function effacer($id)
	{
		

		$this->Model->delete('type_matieres',array('ID_TYPE_MATIERE'=>$id));
		$message = "<div class='alert alert-success' id='message'>

		Profil & Droit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('stock_matieres/Type_matieres'));  

	}



}
