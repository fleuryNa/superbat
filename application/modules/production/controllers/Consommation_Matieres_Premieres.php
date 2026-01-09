<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consommation_Matieres_Premieres extends MY_Controller {

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

		$data['title']='Liste des matières premières consommées';
		$this->load->view('Consommation_Matieres_Premieres_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="SELECT `ID_COMANDE_PROD`, smp.NUMERO_COLIS, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP,cpmp.ID_STATUT_CO_MP FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE cpmp.ID_STATUT_CO_MP=3" ;

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
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("production/Consommation_Matieres_Premieres/index_update/".$key->ID_COMANDE_PROD).'">
			<i class="fa fa-edit"></i> Modifier
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



	public function ajouter()

	{

		$data['title']='Enregistrer une consommation de matières premières';

		$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN commande_production_matieres_premiers ON commande_production_matieres_premiers.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE commande_production_matieres_premiers.QUANTITE_TONNE>0 AND commande_production_matieres_premiers.ID_STATUT_CO_MP=2 order by type_matieres.DESCRIPTION');

		$this->load->view('Consommation_Matieres_Premieres_Add_View',$data);



	}

	public function add()
	{

		
		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$QUANTITE_CONSOME=$this->input->post('QUANTITE_CONSOME');


		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Type matière', 'required');
		$this->form_validation->set_rules('QUANTITE_CONSOME', 'Quantite', 'required');
		
		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			la consommation  non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Enregistrer une consommation de matières premières';

			$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN commande_production_matieres_premiers ON commande_production_matieres_premiers.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE commande_production_matieres_premiers.QUANTITE_TONNE>0 AND commande_production_matieres_premiers.ID_STATUT_CO_MP=2 order by type_matieres.DESCRIPTION');

			$this->load->view('Consommation_Matieres_Premieres_Add_View',$data);

		}
		else{

			$donne=$this->Model->getRequeteOne('SELECT * FROM commande_production_matieres_premiers WHERE ID_TYPE_MATIERE='.$ID_TYPE_MATIERE.' AND QUANTITE_TONNE> '.$QUANTITE_CONSOME.'');


			if(!empty($donne)){
				$array = array(

					'QUANTITE_TONNE'=>$donne['QUANTITE_TONNE']-$QUANTITE_CONSOME,
					'QUANTITE_CONSOME'=>$QUANTITE_CONSOME,
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'ID_STATUT_CO_MP'=>3,
					'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
				);
				$this->Model->update('commande_production_matieres_premiers',array('ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE),$array);

				$datahisto = array(
					'ID_COMANDE_PROD'=>$donne['ID_COMANDE_PROD'],
					'QUANTITE_TONNE'=>$donne['QUANTITE_TONNE'],
					'QUANTITE_CONSOME'=>$QUANTITE_CONSOME,
					'ID_TYPE_MATIERE'=>$donne['ID_TYPE_MATIERE'],
					'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER'),
					'ID_STATUT_CO_MP'=>3
				);

				$this->Model->insert_last_id('histo_commande_production_matieres_premiers',$datahisto);



				$data['message']="<div class='alert alert-success text-center' id ='message'><b>Création d'un type de document fait avec succès</b>.</div>";
				$this->session->set_flashdata($data);
				redirect(base_url('production/Consommation_Matieres_Premieres'));

			}else{
				$message = "<div class='alert alert-danger'>

				on trouves des quantites vides

				<button type='button' class='close' data-dismiss='alert'>&times;</button>

				</div>";

				$this->session->set_flashdata(array('message'=>$message));
			}
		}


	}




	public function index_update($id)
	{

		$data['title']='Modifier une consommation de matières premières';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `Consommation_Matieres_Premieres` WHERE ID_Consommation_Matieres_Premieres = '.$id.'');

		$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN commande_production_matieres_premiers ON commande_production_matieres_premiers.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE commande_production_matieres_premiers.QUANTITE_TONNE>0 AND commande_production_matieres_premiers.ID_STATUT_CO_MP=2 order by type_matieres.DESCRIPTION');

		$this->load->view('Consommation_Matieres_Premieres_Update_View',$data);

	}




	public function update()
	{

		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$QUANTITE_CONSOME=$this->input->post('QUANTITE_CONSOME');
		$ID_COMANDE_PROD=$this->input->post('ID_COMANDE_PROD');


		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Type matière', 'required');
		$this->form_validation->set_rules('QUANTITE_CONSOME', 'Quantite', 'required');
		
		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			la consommation  non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Modifier une consommation de matières premières';
			$data['data']=$this->Model->getRequeteOne('SELECT * FROM `Consommation_Matieres_Premieres` WHERE ID_Consommation_Matieres_Premieres = '.$id.'');

			$data['type_matieres']=$this->Model->getRequete('SELECT type_matieres.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE,type_matieres.IS_ACTIF FROM type_matieres JOIN commande_production_matieres_premiers ON commande_production_matieres_premiers.ID_TYPE_MATIERE=type_matieres.ID_TYPE_MATIERE WHERE commande_production_matieres_premiers.QUANTITE_TONNE>0 AND commande_production_matieres_premiers.ID_STATUT_CO_MP=2 order by type_matieres.DESCRIPTION');

			$this->load->view('Consommation_Matieres_Premieres_Add_View',$data);

		}
		else{

			$donne=$this->Model->getRequeteOne('SELECT * FROM commande_production_matieres_premiers WHERE ID_TYPE_MATIERE='.$ID_TYPE_MATIERE.' AND QUANTITE_TONNE> '.$QUANTITE_CONSOME.'');


			if(!empty($donne)){

				if(!empty($donne['QUANTITE_CONSOME'])){
					$array_ancien = array(

						'QUANTITE_TONNE'=>$donne['QUANTITE_TONNE']+$donne['QUANTITE_CONSOME'],
						'QUANTITE_CONSOME'=>0,
						'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
						'ID_STATUT_CO_MP'=>3,
						'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
					);
					$this->Model->update('commande_production_matieres_premiers',array('ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE),$array_ancien);	
				}


				$array = array(

					'QUANTITE_TONNE'=>$donne['QUANTITE_TONNE']-$QUANTITE_CONSOME,
					'QUANTITE_CONSOME'=>$QUANTITE_CONSOME,
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'ID_STATUT_CO_MP'=>3,
					'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
				);
				$this->Model->update('commande_production_matieres_premiers',array('ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE),$array);

				$datahisto = array(
					'ID_COMANDE_PROD'=>$donne['ID_COMANDE_PROD'],
					'QUANTITE_TONNE'=>$donne['QUANTITE_TONNE'],
					'QUANTITE_CONSOME'=>$QUANTITE_CONSOME,
					'ID_TYPE_MATIERE'=>$donne['ID_TYPE_MATIERE'],
					'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER'),
					'ID_STATUT_CO_MP'=>3
				);

				$this->Model->insert_last_id('histo_commande_production_matieres_premiers',$datahisto);



				$data['message']="<div class='alert alert-success text-center' id ='message'><b>Création d'un type de document fait avec succès</b>.</div>";
				$this->session->set_flashdata($data);
				redirect(base_url('production/Consommation_Matieres_Premieres'));

			}else{
				$message = "<div class='alert alert-danger'>

				on trouves des quantites vides

				<button type='button' class='close' data-dismiss='alert'>&times;</button>

				</div>";

				$this->session->set_flashdata(array('message'=>$message));
			}
		}

	}

	public function effacer($id)
	{
		

		$this->Model->delete('Consommation_Matieres_Premieres',array('ID_COMANDE_PROD'=>$id));
		
		$message = "<div class='alert alert-success' id='message'>

		Profil & Droit Modifi&eacute; avec succés

		<button type='button' class='close' data-dismiss='alert'>&times;</button>

		</div>";

		$this->session->set_flashdata(array('message'=>$message));
		redirect(base_url('production/Consommation_Matieres_Premieres'));  

	}



}
