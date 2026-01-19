<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Matieres_New extends MY_Controller {

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
		$this->load->view('Stock_Matieres_New_List_View',$data);
	}


	public function listing()
	{

		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.DESCRIPTION, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE 1 AND stock_matieres_premieres.LOT_MP IS NULL" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->DESCRIPTION.'('.$key->DESCRIPTION.')';
			// $row[] = $key->NUMERO_COLIS ? $key->NUMERO_COLIS.'('.$key->COULEUR.')' : "N/A" ;
			// $row[] = $key->LONGEUR ? $key->LONGEUR : "-";
			// $row[] = $key->QUANTITE_COMMANDE;
			$row[] = $key->QUANTITE_RECUE;
			$row[] = $key->NOM .' de '.$key->LOCALITE ;
			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_ENTREE));
			$row[] = $key->DESCRIPTION_STATUT_MATIERE;
			$options = '
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
			<div class="dropdown-menu">';
			if($key->ID_STATUT_MATIERE ==1){
				$options .='<a class="dropdown-item" href="'.base_url("stock_matieres/Stock_Matieres_New/index_update/".$key->ID_STOCK_MATIERE).'/0">
				<i class="fa fa-edit"></i> Modifier
				</a>
				<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_STOCK_MATIERE.'">
				<i class="fa fa-trash"></i> Supprimer
				</a>
				';
			}
			$options .='
			<a class="dropdown-item" onclick="get_histo('.$key->ID_STOCK_MATIERE.')">
			<i class="fa fa-eye"></i> Details
			</a>
			</div>
			</div>';

			$row[]=$options ;
			
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



	public function listing2()
	{

		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.DESCRIPTION, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE,stock_matieres_premieres.LOT_MP FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE 1 AND stock_matieres_premieres.LOT_MP IS NOT NULL" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('LOT_MP','ID_STOCK_MATIERE','type_matieres.DESCRIPTION','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR LOT_MP LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();
            $row[] ='<span class="badge badge-success">'.$key->LOT_MP.'</span>';
			$row[] = $key->DESCRIPTION.'('.$key->DESCRIPTION.')';
			// $row[] = $key->NUMERO_COLIS ? $key->NUMERO_COLIS.'('.$key->COULEUR.')' : "N/A" ;
			// $row[] = $key->LONGEUR ? $key->LONGEUR : "-";
			// $row[] = $key->QUANTITE_COMMANDE;
			$row[] = $key->QUANTITE_RECUE;
			$row[] = $key->NOM .' de '.$key->LOCALITE ;
			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_ENTREE));
			$row[] = $key->DESCRIPTION_STATUT_MATIERE;
			$options = '
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
			<div class="dropdown-menu">';
			if($key->ID_STATUT_MATIERE ==1){
				$options .='<a class="dropdown-item" href="'.base_url("stock_matieres/Stock_Matieres_New/index_update/".$key->ID_STOCK_MATIERE).'/0">
				<i class="fa fa-edit"></i> Modifier
				</a>
				<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_STOCK_MATIERE.'">
				<i class="fa fa-trash"></i> Supprimer
				</a>
				';
			}
			$options .='
			<a class="dropdown-item" onclick="get_histo('.$key->ID_STOCK_MATIERE.')">
			<i class="fa fa-eye"></i> Details
			</a>
			</div>
			</div>';

			$row[]=$options ;
			
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

		$this->load->view('Stock_Matieres_New_Add_View',$data);



	}


	public function add()
	{

		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		$NUMERO_COLIS=$this->input->post('DESCRIPTION');
		
		$QUANTITE=$this->input->post('QUANTITE');
		
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');


		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		// $this->form_validation->set_rules('NUMERO_COLIS', 'Nom du Profil', 'required');
		// $this->form_validation->set_rules('LONGEUR', 'Nom du Profil', 'required');
		// $this->form_validation->set_rules('COULEUR', 'Nom du Profil', 'required');
		//$this->form_validation->set_rules('QUANTITE_COMMANDE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE', 'Quantite', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Date entree', 'required');
		

		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Stock de matieres non enregistr&eacute; de cong&eacute; non enregistr&eacute;

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			$data['title']='Ajouter au Stock des matieres';

			$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
			$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');

			$this->load->view('Stock_Matieres_New_Add_View',$data);
		}else{


			$donne_matiere=$this->Model->getOne('stock_matieres_premieres',array('ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE));


			if(!empty($donne_matiere)){


				$this->Model->update('stock_matieres_premieres', ['ID_TYPE_MATIERE' => $ID_TYPE_MATIERE], array('QUANTITE_RECUE'=>($donne_matiere['QUANTITE_RECUE']+$QUANTITE)));

				$datahisto=array(
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'ID_STOCK_MATIERE'=>$donne_matiere['ID_STOCK_MATIERE'],
					'DESCRIPTION'=>$this->input->post('DESCRIPTION'),

					
					'QUANTITE_RECUE'=>$QUANTITE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_STATUT_MATIERE'=>1,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);

				$this->Model->create('historique_stock_matieres_premieres',$datahisto);



			}else{
				$datas=array(
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'DESCRIPTION'=>$this->input->post('DESCRIPTION'),

					'QUANTITE_RECUE'=>$QUANTITE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);

				$ID_STOCK_MATIERE = $this->Model->insert_last_id('stock_matieres_premieres',$datas);



				$datahisto=array(
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE,
					'DESCRIPTION'=>$this->input->post('DESCRIPTION'),

					
					'QUANTITE_RECUE'=>$QUANTITE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_STATUT_MATIERE'=>1,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);
				$this->Model->create('historique_stock_matieres_premieres',$datahisto);

			}

			


			$message = "<div class='alert alert-success' id='message'>

			Stock de matieres enregistr&eacute; avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('stock_matieres/Stock_Matieres_New')); 
			

		}





	}



	public function index_update($id,$val)
	{

		$data['title']='Modifier';

		$data['data']=$this->Model->getRequeteOne('SELECT * FROM `stock_matieres_premieres` WHERE ID_STOCK_MATIERE = '.$id.'');
		
		$data['data2']=$this->Model->getRequeteOne('SELECT * FROM `historique_stock_matieres_premieres` WHERE ID_HISTO_STOCK_MATIERE = '.$val.'');
		
		$data['type_matieres']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');
		$data['fournisseur']=$this->Model->getRequete('SELECT * FROM `fournisseur` order by NOM');

		$this->load->view('Stock_Matieres_New_Update_View',$data);

	}




	public function update()
	{
		$ID_STOCK_MATIERE=$this->input->post('ID_STOCK_MATIERE');
		$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');
		
		$QUANTITE=$this->input->post('QUANTITE');
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');

		$ID_HISTO_STOCK_MATIERE=$this->input->post('ID_HISTO_STOCK_MATIERE');



		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		
		$this->form_validation->set_rules('QUANTITE', 'QUANTITE', 'required');
		$this->form_validation->set_rules('ID_FOURNISSEUR', 'Fournisseur', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Date', 'required');
		
		

		

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

			$this->load->view('Stock_Matieres_New_Update_View',$data);

		}
		else{
			$datas=array(
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'DESCRIPTION'=>$this->input->post('DESCRIPTION'),
				
				'QUANTITE_RECUE'=>$QUANTITE,
				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'DATE_ENTREE'=>$DATE_ENTREE,
				'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
			);


			if ($ID_HISTO_STOCK_MATIERE==0) {
			$update=$this->Model->update('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE),$datas);
		

           if($update){

				$datashisto=array(
					'ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE,
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'DESCRIPTION'=>$this->input->post('DESCRIPTION'),

					
					'QUANTITE_RECUE'=>$QUANTITE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_STATUT_MATIERE'=>1,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);



				$this->Model->insert_last_id('historique_stock_matieres_premieres',$datashisto);
			} 


			}else{

           $data=$this->Model->getRequeteOne('SELECT * FROM `historique_stock_matieres_premieres` WHERE ID_HISTO_STOCK_MATIERE = '.$ID_HISTO_STOCK_MATIERE.'');
           $don=$this->Model->getOne('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE));

           $QUANTITE2=$data['QUANTITE_RECUE'];

           $quant=0;

           if ($QUANTITE<$QUANTITE2) {
           $quant=$don['QUANTITE_RECUE']-($QUANTITE2-$QUANTITE);
           }


           if ($QUANTITE2<$QUANTITE) {
           $quant=$don['QUANTITE_RECUE']+($QUANTITE-$QUANTITE2);
           }



           $datas=array(
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'DESCRIPTION'=>$this->input->post('DESCRIPTION'),
				
				'QUANTITE_RECUE'=>$quant,
				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'DATE_ENTREE'=>$DATE_ENTREE,
				'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
			);

          $update=$this->Model->update('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE),$datas);


           if($update){

				$datashisto=array(
					'ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE,
					'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
					'DESCRIPTION'=>$this->input->post('DESCRIPTION'),

					
					'QUANTITE_RECUE'=>$QUANTITE,
					'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
					'DATE_ENTREE'=>$DATE_ENTREE,
					'ID_STATUT_MATIERE'=>1,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);



				$this->Model->update('historique_stock_matieres_premieres',array('ID_HISTO_STOCK_MATIERE'=>$ID_HISTO_STOCK_MATIERE),$datashisto);
			} 
		






			}

			

			
			$message = "<div class='alert alert-success' id='message'>

			Stock de matieres Modifi&eacute; avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('stock_matieres/Stock_Matieres_New')); 

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



	function liste_reception(){
		$data['title']='Réception des matières premières';
		$commandes = $this->Model->getRequete("SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `NUMERO_COLIS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM AS user,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE stock_matieres_premieres.ID_STATUT_MATIERE!=2");
		$data['commandes'] = $commandes;
		$this->load->view('Stock_Matieres_List_Reception_View',$data);
	}




	public function listing_reception()
	{

		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `NUMERO_COLIS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE stock_matieres_premieres.ID_STATUT_MATIERE=2" ;

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
			$row[] = $key->NUMERO_COLIS ? $key->NUMERO_COLIS.'('.$key->COULEUR.')' : "N/A" ;
			$row[] = $key->LONGEUR ? $key->LONGEUR : "-";
			$row[] = $key->QUANTITE_COMMANDE;
			$row[] = $key->QUANTITE_RECUE;
			$row[] = $key->NOM .' de '.$key->LOCALITE ;
			$row[] = $key->NOM .' '.$key->PRENOM;
			$row[] = date("d/m/Y", strtotime($key->DATE_ENTREE));
			$row[] = $key->DESCRIPTION_STATUT_MATIERE;
			$options = '
			<div class="modal fade" id="rendreeff'.$key->ID_STOCK_MATIERE.'" tabindex="-1" role="dialog" aria-labelledby="modalLabel'.$key->ID_STOCK_MATIERE.'" aria-hidden="true">
			<div class="modal-dialog" role="document">
			<div class="modal-content">

			<!-- HEADER -->
			<div class="modal-header">
			<h5 class="modal-title" id="modalLabel'.$key->ID_STOCK_MATIERE.'">Modifier réception</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>

			<!-- BODY -->
			<form id="FormData'.$key->ID_STOCK_MATIERE.'" method="post" action="'.base_url("stock_matieres/Stock_matieres/modifi_reception").'">
			<div class="modal-body">
			<input type="hidden" name="ID_STOCK_MATIERE" value="'.$key->ID_STOCK_MATIERE.'">

			<div class="form-group col-sm-10">
			<label for="MOTIF'.$key->ID_STOCK_MATIERE.'">Motif <span class="text-danger">*</span></label>
			<textarea 
			id="MOTIF'.$key->ID_STOCK_MATIERE.'"
			name="MOTIF"
			class="form-control"
			rows="4"
			required
			placeholder="Saisir le motif de la modification..."
			></textarea>
			<small id="erreurMotif'.$key->ID_STOCK_MATIERE.'" class="text-danger"></small>
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

			<div class="dropdown-menu">';
			$data_histo=$this->Model->getOne('historique_stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$key->ID_STOCK_MATIERE,'ID_STATUT_MATIERE'=>4));
			if(empty($data_histo)){
				$options .='
				<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_STOCK_MATIERE.'">
				<i class="fa fa-edit"></i> Modifier
				</a>';
			}
			$options .='<a class="dropdown-item" onclick="get_histo('.$key->ID_STOCK_MATIERE.')">
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


	public function traitement()
	{
    // Récupération des données du formulaire
    $id_stocks        = $this->input->post('id_stock');        // tableau
    $id_matieres      = $this->input->post('id_matiere');      // tableau
    $quantites_recues = $this->input->post('quantite_recu');   // tableau
    $select_stocks    = $this->input->post('select_stock');    // seulement cochés

    // Sécurité
    if (empty($select_stocks)) {
    	$this->session->set_flashdata('error', 'Aucune ligne sélectionnée.');
    	redirect('stock_matieres/Stock_matieres');
    }

    foreach ($select_stocks as $id_stock_selected) {

        // retrouver l'index correspondant
    	$index = array_search($id_stock_selected, $id_stocks);

    	if ($index === false) {
    		continue;
    	}
    	$donne_stock=$this->Model->getOne('stock_matieres_premieres',array('ID_TYPE_MATIERE'=>$id_matieres[$index],'ID_STOCK_MATIERE'=>$id_stock_selected));
    	

    	$datashisto=array(
    		'ID_STOCK_MATIERE'=>$donne_stock['ID_STOCK_MATIERE'],
    		'ID_TYPE_MATIERE'=>$donne_stock['ID_TYPE_MATIERE'],
    		'NUMERO_COLIS'=>$donne_stock['NUMERO_COLIS'],
    		'LONGEUR'=>$donne_stock['LONGEUR'],
    		'COULEUR'=>$donne_stock['COULEUR'],
    		'QUANTITE_COMMANDE'=>$donne_stock['QUANTITE_COMMANDE'],
    		'QUANTITE_RECUE'=>$quantites_recues[$index],
    		'ID_FOURNISSEUR'=>$donne_stock['ID_FOURNISSEUR'],
    		'DATE_ENTREE'=>$donne_stock['DATE_ENTREE'],
    		'ID_STATUT_MATIERE'=>2,
    		'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
    	);



    	$this->Model->insert_last_id('historique_stock_matieres_premieres',$datashisto);

    	$data = [
    		'QUANTITE_RECUE' => $quantites_recues[$index],
    		'ID_STATUT_MATIERE' => 2,
    	];

    	$this->Model->update('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$id_stock_selected),$data);
    }

    $this->session->set_flashdata('success', 'Stock mis à jour avec succès.');
    redirect('stock_matieres/liste_reception');
}


public function modifi_reception()
{
	$id=$this->input->post('ID_STOCK_MATIERE');
	$motif = trim($this->input->post('MOTIF'));

	if ($motif == '') {
		$this->session->set_flashdata('error', 'Le motif est obligatoire.');
		redirect('stock_matieres/liste_reception');
	}

	$donne_stock=$this->Model->getOne('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$id));
    

	$datashisto=array(
		'ID_STOCK_MATIERE'=>$donne_stock['ID_STOCK_MATIERE'],
		'ID_TYPE_MATIERE'=>$donne_stock['ID_TYPE_MATIERE'],
		'NUMERO_COLIS'=>$donne_stock['NUMERO_COLIS'],
		'LONGEUR'=>$donne_stock['LONGEUR'],
		'COULEUR'=>$donne_stock['COULEUR'],
		'QUANTITE_COMMANDE'=>$donne_stock['QUANTITE_COMMANDE'],
		'QUANTITE_RECUE'=>$donne_stock['QUANTITE_RECUE'],
		'ID_FOURNISSEUR'=>$donne_stock['ID_FOURNISSEUR'],
		'DATE_ENTREE'=>$donne_stock['DATE_ENTREE'],
		'ID_STATUT_MATIERE'=>3,
		'MOTIF'=>$motif,
		'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
	);



	$this->Model->insert_last_id('historique_stock_matieres_premieres',$datashisto);


	$data = [
		'ID_STATUT_MATIERE' => 1,
	];

	$this->Model->update('stock_matieres_premieres',array('ID_STOCK_MATIERE'=>$id),$data);

	$this->session->set_flashdata('success', 'Modification effectuée.');
	redirect('stock_matieres/liste_reception');
}


public function get_details($value='',$ID="")
{

	$id=$this->input->post('id');


	$query_principal ="SELECT `ID_STOCK_MATIERE`, historique_stock_matieres_premieres.`ID_HISTO_STOCK_MATIERE`, historique_stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `NUMERO_COLIS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, historique_stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, historique_stock_matieres_premieres.`ID_USER`,admin_user.NOM as user,admin_user.PRENOM, `DATE_ENTREE`,`DATE_INSERT_HISTO`,historique_stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE,MOTIF FROM `historique_stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=historique_stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=historique_stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =historique_stock_matieres_premieres.ID_STATUT_MATIERE WHERE `ID_STOCK_MATIERE`=".$id ;

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

		$row[] = date("d/m/Y", strtotime($key->DATE_INSERT_HISTO));
		$row[] = $key->user .' '.$key->PRENOM;
		$row[] = $key->NOM .' de '.$key->LOCALITE ;
		$row[] = $key->DESCRIPTION.'('.$key->CARACTERISTIQUE.')';
		//$row[] = $key->QUANTITE_COMMANDE;
		$row[] = $key->QUANTITE_RECUE;
		$row[] = $key->MOTIF ? $key->MOTIF : 'N/A';
		$row[] = $key->DESCRIPTION_STATUT_MATIERE;
		$options = '<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">';
			if($key->ID_STATUT_MATIERE ==1){
				$options .='<a class="dropdown-item" href="'.base_url("stock_matieres/Stock_Matieres_New/index_update/".$key->ID_STOCK_MATIERE.'/'.$key->ID_HISTO_STOCK_MATIERE).'">
				<i class="fa fa-edit"></i> Modifier
				</a>
				
				';

				// <a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_STOCK_MATIERE.'">
				// <i class="fa fa-trash"></i> Supprimer
				// </a>
			}
			$options .='
			
			</div>
			</div>';

			$row[]=$options ;



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
