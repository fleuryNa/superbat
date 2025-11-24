<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_matieres extends MY_Controller {

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

		$data['title']='Liste de Stock matieres premieres';
		$this->load->view('Stock_Matieres_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `NUMERO_COLIS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT` FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER WHERE 1" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION', 'NUMERO_COLIS','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR NUMERO_COLIS LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')';
			$row[] = $key->NUMERO_COLIS.'('.$key->COULEUR.')';
			$row[] = $key->LONGEUR;
			$row[] = $key->QUANTITE_COMMANDE;
			$row[] = $key->QUANTITE_RECUE;
			$row[] = $key->NOM .' de '.$key->LOCALITE ;
			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_ENTREE));
			
			$row[] = '
			<div class="modal fade" id="rendreeff'.$key->ID_STOCK_MATIERE.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Effacer</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<form id="FormData" action="'.base_url("stock_matieres/Stock_matieres/effacer/".$key->ID_STOCK_MATIERE).'" >
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
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("stock_matieres/Stock_matieres/index_update/".$key->ID_STOCK_MATIERE).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_STOCK_MATIERE.'">
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

		$data['title']='Ajouter au Stock des matieres';
		$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
		$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');

		$this->load->view('Stock_Matieres_Add_View',$data);



	}


	public function add()
	{

		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');
		$LONGEUR=$this->input->post('LONGEUR');
		$COULEUR=$this->input->post('COULEUR');
		$QUANTITE_COMMANDE=$this->input->post('QUANTITE_COMMANDE');
		$QUANTITE_RECUE=$this->input->post('QUANTITE_RECUE');
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');


		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('NUMERO_COLIS', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('LONGEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('COULEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE_COMMANDE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE_RECUE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('ID_FOURNISSEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Nom du Profil', 'required');
		

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Stock de matieres non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Ajouter au Stock des matieres';

			$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
			$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');

			$this->load->view('Stock_Matieres_Add_View',$data);
		}else{

			$datas=array(
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'NUMERO_COLIS'=>$NUMERO_COLIS,
				'LONGEUR'=>$LONGEUR,
				'COULEUR'=>$COULEUR,
				'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,
				'QUANTITE_RECUE'=>$QUANTITE_RECUE,
				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'DATE_ENTREE'=>$DATE_ENTREE,
				'ID_USER'=>2
			);

			$ID_STOCK_MATIERE = $this->Model->insert_last_id('stock_matieres_premieres',$datas);

			

		}



		$message = "<div class='alert alert-success' id='message'>

		Stock de matieres enregistr&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));

		redirect(base_url('stock_matieres/Stock_matieres'));  

	}



	public function index_update($id)
	{

		$data['title']='Modifier';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `stock_matieres_premieres` WHERE ID_STOCK_MATIERE = '.$id.'');
		$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
		$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');

		$this->load->view('Stock_Matieres_Update_View',$data);

	}




	public function update()
	{
		$ID_STOCK_MATIERE=$this->input->post('ID_STOCK_MATIERE');
		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');
		$LONGEUR=$this->input->post('LONGEUR');
		$COULEUR=$this->input->post('COULEUR');
		$QUANTITE_COMMANDE=$this->input->post('QUANTITE_COMMANDE');
		$QUANTITE_RECUE=$this->input->post('QUANTITE_RECUE');
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');


		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('NUMERO_COLIS', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('LONGEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('COULEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE_COMMANDE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE_RECUE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('ID_FOURNISSEUR', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Nom du Profil', 'required');
		
		

		

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Stock de matieres non modifi&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Modifier un Fournisseur';
			$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
			$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');
			$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
			$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');
			$data['data']=$this->Model->getRequeteOne('SELECT * FROM `stock_matieres_premieres` WHERE ID_STOCK_MATIERE = '.$ID_STOCK_MATIERE.'');

			$this->load->view('Stock_Matieres_Update_View',$data);

		}
		else{
			$datas=array(
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'NUMERO_COLIS'=>$NUMERO_COLIS,
				'LONGEUR'=>$LONGEUR,
				'COULEUR'=>$COULEUR,
				'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,
				'QUANTITE_RECUE'=>$QUANTITE_RECUE,
				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'DATE_ENTREE'=>$DATE_ENTREE,
				'ID_USER'=>2
			);

			$update=$this->Model->update('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE),$datas);
			if($update){

				$datashisto=array(
					'ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE,
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'NUMERO_COLIS'=>$NUMERO_COLIS,
					'LONGEUR'=>$LONGEUR,
					'COULEUR'=>$COULEUR,
					'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,
					'QUANTITE_RECUE'=>$QUANTITE_RECUE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_USER'=>2
				);



				$this->Model->insert_last_id('historique_stock_matieres_premieres',$datashisto);
			} 

			$message = "<div class='alert alert-success' id='message'>

			Stock de matieres Modifi&eacute; avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('stock_matieres/Stock_matieres')); 

		}

		

	}

	public function effacer($id)
	{
		

		$this->Model->delete('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$id));
		$message = "<div class='alert alert-success' id='message'>

		Stock de matieres Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('stock_matieres/Stock_matieres'));  

	}



}
