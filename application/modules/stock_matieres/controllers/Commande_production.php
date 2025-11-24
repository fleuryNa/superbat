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
		$commandes = $this->Model->getRequete("SELECT `ID_COMANDE_PROD`, `NOMBRE_COLIS`, `QUANTITE_TONNE`, cpmp.ID_USER_DEMANDEUR,user_demandeur.NOM,user_demandeur.PRENOM, `ID_USER_DONNEUR`, cpmp.ID_TYPE_MATIERE,type_matieres.DESCRIPTION,type_matieres.CARACTERISTIQUE, `DATE_INSERTION` FROM `commande_production_matieres_premiers` AS cpmp JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=cpmp.`ID_TYPE_MATIERE` JOIN admin_user AS user_demandeur ON user_demandeur.ID_USER=cpmp.`ID_USER_DEMANDEUR`   WHERE STATUT_CO_MT=1");
		$data['commandes'] = $commandes;
		$this->load->view('Commande_List_View',$data);
	}



	public function traitement()
	{
    // Récupérer les données
		$ids           = $this->input->post('id_commande');
		$colis         = $this->input->post('nombre_colis');
		$quantites     = $this->input->post('quantite_tonne');
		$selectionnes  = $this->input->post('select_commande');
		// echo "<pre>";
		// print_r($colis);exit();
		// echo "</pre>";
		if (!$selectionnes) {
			echo "Aucune commande sélectionnée";
			return;
		}
		
    // Parcourir les commandes
		foreach ($ids as $index => $id) {

        // Si cette ligne n'a pas été cochée
			if (!in_array($id, $selectionnes)) continue;

    // Vérifier que les index existent
			if (!isset($colis[$index]) || !isset($quantites[$index])) continue;
			
        // Préparer les données à mettre à jour
			$data = array(
				'NOMBRE_COLIS'   => $colis[$index],
				'QUANTITE_TONNE' => $quantites[$index],
				'STATUT_CO_MT'   => 2, 
				'ID_USER_DONNEUR'=>$this->session->userdata('SUPERBAT_ID_USER')
			);

        // Mise à jour
			$this->Model->update('commande_production_matieres_premiers', ['ID_COMANDE_PROD' => $id], $data);
		}

    // Retour ou redirection
		redirect('stock_matieres/Commande_production');
	}



}
