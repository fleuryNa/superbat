<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_Droit extends MY_Controller {

	
	public function index()
	{
		$data['title']="Liste de Profils";
		$this->load->view('Profil_Droit_View',$data);
	}



	public function listing()
	{

		$query_principal ="SELECT config_profil.PROFIL_ID, config_profil.DESCRIPTION, COUNT(config_profil_droit.ID_DROIT) AS NUMBER FROM `config_profil` JOIN config_profil_droit ON config_profil_droit.PROFIL_ID = config_profil.PROFIL_ID GROUP BY config_profil.PROFIL_ID, config_profil.DESCRIPTION " ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('PROFIL_ID', 'DESCRIPTION');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY PROFIL_ID ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND DESCRIPTION LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();


			$droits = $this->Model->getRequete('SELECT config_droits.DESCRIPTION AS DROIT FROM `config_profil` JOIN config_profil_droit ON config_profil_droit.PROFIL_ID = config_profil.PROFIL_ID JOIN config_droits ON config_droits.ID_DROIT = config_profil_droit.ID_DROIT WHERE config_profil_droit.PROFIL_ID = '.$key->PROFIL_ID.' ORDER BY config_droits.DESCRIPTION');
			$resdroit ="<table class='table'>";
			foreach ($droits as $value) {
				$resdroit.="<tr><td>".$value['DROIT']."</td></tr>";
			}
			$resdroit.="</table>";

			$row[] = $key->DESCRIPTION;
			$row[] = "<a class='btn btn-primary btn-xs' href='#' data-toggle='modal' data-target='#rendreeff".$key->PROFIL_ID."'> ".$key->NUMBER." </a>";

			$row[] = '
			<div class="modal fade" id="rendreeff'.$key->PROFIL_ID.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">'.$key->DESCRIPTION.'</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			'.$resdroit.'
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
			</div>
			</div>
			</div>
			</div>

			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("administration/Profil_Droit/index_update/".$key->PROFIL_ID).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->PROFIL_ID.'">
			<i class="fa fa-eye"></i> Voir détails
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

		$data['title']='Profil & Droit';

		$data['droits']=$this->Model->getRequete('SELECT * FROM `config_droits` order by DESCRIPTION');

		$this->load->view('Profil_Droit_Add_View',$data);



	}


	public function add()
	{

		$DESCRIPTION=$this->input->post('DESCRIPTION');

		$ID_DROIT=$this->input->post('ID_DROIT');

		$this->form_validation->set_rules('DESCRIPTION', 'Nom du Profil', 'required|is_unique[config_profil.DESCRIPTION]');

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Profil est droit non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Profil & Droit';

			$data['droits']=$this->Model->getRequete('SELECT * FROM `config_droits` order by DESCRIPTION');

			$this->load->view('Profil_Droit_Add_View',$data);

		}else{

			$datasprofil=array('DESCRIPTION'=>$DESCRIPTION);

			$PROFIL_ID = $this->Model->insert_last_id('config_profil',$datasprofil);

			foreach ($ID_DROIT as $ID_DROIT) {


				$datadroitprofil = array(

					'PROFIL_ID' => $PROFIL_ID,

					'ID_DROIT' =>$ID_DROIT ,

				);

				$this->Model->insert_last_id('config_profil_droit',$datadroitprofil);

			}





			$message = "<div class='alert alert-success' id='message'>

			Profil & Droit enregistr&eacute; avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('administration/Profil_Droit'));  

		}

	}


	public function index_update($id)
	{

		$data['title']='Profil & Droit';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `config_profil` WHERE PROFIL_ID = '.$id.'');

		$data['droits']=$this->Model->getRequete('SELECT * FROM `config_droits` order by DESCRIPTION');

		$this->load->view('Profil_Droit_Update_View',$data);

	}




	public function update()
	{
		$DESCRIPTION=$this->input->post('DESCRIPTION');

		$ID_DROIT=$this->input->post('ID_DROIT');

		$PROFIL_ID=$this->input->post('PROFIL_ID');

		$this->form_validation->set_rules('DESCRIPTION', 'Description', 'required');

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Profil est droit non modifi&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Profil & Droit';

			$data['data']=$this->Model->getRequeteOne('SELECT * FROM `config_profil` WHERE PROFIL_ID = '.$PROFIL_ID.'');

			$data['droits']=$this->Model->getRequete('SELECT * FROM `config_droits` order by DESCRIPTION');

			$this->load->view('Profil_Droit_Update_View',$data);

		}
		else{
			$datasprofil=array('DESCRIPTION'=>$DESCRIPTION);

			$this->Model->update('config_profil',array('PROFIL_ID'=>$PROFIL_ID),$datasprofil);

			$this->Model->delete('config_profil_droit',array('PROFIL_ID'=>$PROFIL_ID));

			foreach ($ID_DROIT as $ID_DROIT) {
				$datadroitprofil = array(

					'PROFIL_ID' => $PROFIL_ID,

					'ID_DROIT' =>$ID_DROIT ,

				);

				$this->Model->insert_last_id('config_profil_droit',$datadroitprofil);

			}

			$message = "<div class='alert alert-success' id='message'>

			Profil & Droit Modifi&eacute; avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('administration/Profil_Droit'));  

		}



	}




}