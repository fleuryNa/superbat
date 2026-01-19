<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_production extends MY_Controller {

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
		$commandes = $this->Model->getRequete("SELECT `ID_COMANDE_PROD`, smp.NBRE_COIlS, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.UNITE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE cpmp.ID_STATUT_CO_MP=1");
		$data['commandes'] = $commandes;
		$this->load->view('Commande_List_View',$data);
	}



	public function traitement()
	{
    // Récupérer les données
		$ids           = $this->input->post('id_commande');
		$quantites     = $this->input->post('quantite_tonne');
		$typematiere     = $this->input->post('id_matiere');
		$selectionnes  = $this->input->post('select_commande');
		
		if (!$selectionnes) {
			echo "Aucune commande sélectionnée";
			return;
		}
		
    // Parcourir les commandes
		foreach ($ids as $index => $id) {

        // Si cette ligne n'a pas été cochée
			if (!in_array($id, $selectionnes)) continue;

    // Vérifier que les index existent
			if (!isset($quantites[$index])) continue;

			$donne_matiere=$this->Model->getOne('stock_matieres_premieres',array('ID_TYPE_MATIERE'=>$typematiere[$index]));


			if(!empty($donne_matiere)){



				$this->Model->update('stock_matieres_premieres', ['ID_TYPE_MATIERE' => $typematiere[$index]], array('QUANTITE_RECUE'=>($donne_matiere['QUANTITE_RECUE']-$quantites[$index])));



				$datahisto=array(
					'ID_TYPE_MATIERE'=>$donne_matiere['ID_TYPE_MATIERE'],
					'ID_STOCK_MATIERE'=>$donne_matiere['ID_STOCK_MATIERE'],
					'NBRE_COIlS'=>$donne_matiere['NBRE_COIlS'],
					'LONGEUR'=>$donne_matiere['LONGEUR'],
					'COULEUR'=>$donne_matiere['COULEUR'],
					'QUANTITE_COMMANDE'=>$donne_matiere['QUANTITE_COMMANDE'],
					'QUANTITE_RECUE'=>$quantites[$index],
					'ID_FOURNISSEUR'=>$donne_matiere['ID_FOURNISSEUR'],
					'DATE_ENTREE'=>$donne_matiere['DATE_ENTREE'],
					'ID_STATUT_MATIERE'=>4,
					'ID_USER'=>$this->session->userdata('SUPERBAT_ID_USER')
				);

				$this->Model->create('historique_stock_matieres_premieres',$datahisto);


                // Préparer les données à mettre à jour
				$data = array(
					'QUANTITE_TONNE' => $quantites[$index],
					'ID_STATUT_CO_MP'   => 2, 
					'ID_USER_DONNEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
				);

             // Mise à jour
				$this->Model->update('commande_production_matieres_premiers', ['ID_COMANDE_PROD' => $id], $data);


				$datahisto = array(
					'ID_COMANDE_PROD'=>$id,
					'QUANTITE_TONNE'=>$quantites[$index],
					'ID_TYPE_MATIERE'=>$typematiere[$index],
					'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER'),
					'ID_STATUT_CO_MP'=>2
				);

				$this->Model->insert_last_id('histo_commande_production_matieres_premiers',$datahisto);
			}
			

		}

    // Retour ou redirection
		redirect('stock_matieres/Commande_production');
	}

	public function listing_livrer()
	{

		$query_principal ="SELECT `ID_COMANDE_PROD`, smp.NBRE_COIlS, SUM(`QUANTITE_TONNE`) AS QUANTITE_TONNE, cpmp.ID_USER_DEMANDEUR,QUANTITE_CONSOME,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.UNITE, `DATE_INSERTION`,scm.DESCRIPTION_CO_MP,cpmp.ID_STATUT_CO_MP FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`  JOIN stock_matieres_premieres AS smp ON smp.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN statut_commande_matieres AS scm ON cpmp.ID_STATUT_CO_MP=scm.ID_STATUT_CO_MP WHERE cpmp.ID_STATUT_CO_MP!=1" ;

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
			$row[] = $key->DESCRIPTION.'('.$key->UNITE.')';
			$row[] = $key->NBRE_COIlS;
			$row[] = $key->QUANTITE_TONNE;
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
			<form id="FormData'.$key->ID_COMANDE_PROD.'" method="post" action="'.base_url("stock_matieres/Commande_production/modifi_envoi").'">
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
			<div class="dropdown-menu">';
			if($key->ID_STATUT_CO_MP==2){
				$options.='<a class="dropdown-item" data-toggle="modal" data-target="#rendreeff'.$key->ID_COMANDE_PROD.'">
				<i class="fa fa-edit"></i> Modifier
				</a>'; 
			}
			$options.='<a class="dropdown-item" onclick="get_histo('.$key->ID_COMANDE_PROD.')">
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

	public function modifi_envoi($value='')
	{
		$id=$this->input->post('ID_COMANDE_PROD');
		$motif = trim($this->input->post('ID_COMANDE_PROD'));

		if ($motif == '') {
			$this->session->set_flashdata('error', 'Le motif est obligatoire.');
			redirect('stock_matieres/Commande_production');
		}

		$donne_stock=$this->Model->getOne('commande_production_matieres_premiers',array('ID_COMANDE_PROD'=>$id));


		$datahisto = array(
			'ID_COMANDE_PROD'=>$id,
			'QUANTITE_TONNE'=>$donne_stock['QUANTITE_TONNE'],
			'ID_TYPE_MATIERE'=>$donne_stock['ID_TYPE_MATIERE'],
			'ID_USER_DEMANDEUR'=>$this->session->userdata('SUPERBAT_ID_USER'),
			'ID_STATUT_CO_MP'=>4
		);

		$this->Model->insert_last_id('histo_commande_production_matieres_premiers',$datahisto);

         $this->Model->update('commande_production_matieres_premiers', ['ID_COMANDE_PROD' => $id], ['ID_STATUT_CO_MP'=>1]);
	}

}
