<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


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

		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.UNITE, `NBRE_COIlS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE 1" ;
		$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.DESCRIPTION, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE 1 AND stock_matieres_premieres.LOT_MP IS NULL" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION', 'NBRE_COIlS','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR NBRE_COIlS LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = $key->DESCRIPTION.'('.$key->UNITE.')';
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

			voulez vous supprimer le fournisseur '.$key->DESCRIPTION.'('.$key->UNITE.')
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
			</a>';
			if($key->ID_TYPE_MATIERE ==33){
			$options .='<a class="dropdown-item" onclick="get_packing('.$key->ID_STOCK_MATIERE.')">
			<i class="fa fa-eye"></i> Packing list
			</a>';
			}
			$options .='</div>
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
		$ID_TYPE_MATIERE = $this->input->post('ID_TYPE_MATIERE');
		$NBRE_COIlS = $this->input->post('DESCRIPTION');
		$QUANTITE = $this->input->post('QUANTITE');
		$ID_FOURNISSEUR = $this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE = $this->input->post('DATE_ENTREE');

		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE', 'Quantité', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Date entrée', 'required');

		if ($this->form_validation->run() == FALSE) {

        // Utiliser 'error' au lieu de 'message'
			$this->session->set_flashdata('error', 'Le stock de matières n’a pas été enregistré. Veuillez vérifier les champs.');

			$data['title'] = 'Ajouter au Stock des matières';
			$data['type_matieres'] = $this->Model->getRequete('SELECT * FROM `type_matieres` ORDER BY DESCRIPTION');
			$data['fournisseur'] = $this->Model->getRequete('SELECT * FROM `fournisseur` ORDER BY NOM');

			$this->load->view('Stock_Matieres_New_Add_View', $data);

		} else {

			$donne_matiere = $this->Model->getOne('stock_matieres_premieres', array('ID_TYPE_MATIERE' => $ID_TYPE_MATIERE));

			if (!empty($donne_matiere)) {

				$this->Model->update(
					'stock_matieres_premieres',
					['ID_TYPE_MATIERE' => $ID_TYPE_MATIERE],
					['QUANTITE_RECUE' => ($donne_matiere['QUANTITE_RECUE'] + $QUANTITE)]
				);

				$datahisto = array(
					'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
					'ID_STOCK_MATIERE' => $donne_matiere['ID_STOCK_MATIERE'],
					'DESCRIPTION' => $NBRE_COIlS,
					'QUANTITE_RECUE' => $QUANTITE,
					'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
					'DATE_ENTREE' => $DATE_ENTREE,
					'ID_STATUT_MATIERE' => 1,
					'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
				);

				$this->Model->create('historique_stock_matieres_premieres', $datahisto);

			} else {

				$datas = array(
					'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
					'DESCRIPTION' => $NBRE_COIlS,
					'QUANTITE_RECUE' => $QUANTITE,
					'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
					'DATE_ENTREE' => $DATE_ENTREE,
					'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
				);

				$ID_STOCK_MATIERE = $this->Model->insert_last_id('stock_matieres_premieres', $datas);

				$datahisto = array(
					'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
					'ID_STOCK_MATIERE' => $ID_STOCK_MATIERE,
					'DESCRIPTION' => $NBRE_COIlS,
					'QUANTITE_RECUE' => $QUANTITE,
					'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
					'DATE_ENTREE' => $DATE_ENTREE,
					'ID_STATUT_MATIERE' => 1,
					'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
				);

				$this->Model->create('historique_stock_matieres_premieres', $datahisto);
			}

        // Utiliser 'success' au lieu de 'message'
			$this->session->set_flashdata('success', 'Le stock de matières a été enregistré avec succès.');
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
		$ID_STOCK_MATIERE = $this->input->post('ID_STOCK_MATIERE');
		$ID_TYPE_MATIERE = $this->input->post('ID_TYPE_MATIERE');
		$QUANTITE = $this->input->post('QUANTITE');
		$ID_FOURNISSEUR = $this->input->post('ID_FOURNISSEUR');
		$DATE_ENTREE = $this->input->post('DATE_ENTREE');
		$ID_HISTO_STOCK_MATIERE = $this->input->post('ID_HISTO_STOCK_MATIERE');

		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Nom du Profil', 'required');
		$this->form_validation->set_rules('QUANTITE', 'Quantité', 'required');
		$this->form_validation->set_rules('ID_FOURNISSEUR', 'Fournisseur', 'required');
		$this->form_validation->set_rules('DATE_ENTREE', 'Date', 'required');

		if ($this->form_validation->run() == FALSE) {

        // Utiliser 'error' au lieu de 'message'
			$this->session->set_flashdata('error', 'Le stock de matières n’a pas été modifié. Veuillez vérifier les champs.');

			$data['title'] = 'Modifier un Fournisseur';
			$data['type_matieres'] = $this->Model->getRequete('SELECT * FROM `type_matieres` ORDER BY DESCRIPTION');
			$data['fournisseur'] = $this->Model->getRequete('SELECT * FROM `fournisseur` ORDER BY NOM');
			$data['data'] = $this->Model->getRequeteOne('SELECT * FROM `stock_matieres_premieres` WHERE ID_STOCK_MATIERE = ' . $ID_STOCK_MATIERE);

			$this->load->view('Stock_Matieres_New_Update_View', $data);

		} else {

			$datas = array(
				'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
				'DESCRIPTION' => $this->input->post('DESCRIPTION'),
				'QUANTITE_RECUE' => $QUANTITE,
				'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
				'DATE_ENTREE' => $DATE_ENTREE,
				'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
			);

			if ($ID_HISTO_STOCK_MATIERE == 0) {

				$update = $this->Model->update('stock_matieres_premieres', ['ID_STOCK_MATIERE' => $ID_STOCK_MATIERE], $datas);

				if ($update) {
					$datashisto = array(
						'ID_STOCK_MATIERE' => $ID_STOCK_MATIERE,
						'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
						'DESCRIPTION' => $this->input->post('DESCRIPTION'),
						'QUANTITE_RECUE' => $QUANTITE,
						'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
						'DATE_ENTREE' => $DATE_ENTREE,
						'ID_STATUT_MATIERE' => 1,
						'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
					);

					$this->Model->insert_last_id('historique_stock_matieres_premieres', $datashisto);
				}

			} else {

				$data = $this->Model->getRequeteOne('SELECT * FROM `historique_stock_matieres_premieres` WHERE ID_HISTO_STOCK_MATIERE = ' . $ID_HISTO_STOCK_MATIERE);
				$don = $this->Model->getOne('stock_matieres_premieres', ['ID_STOCK_MATIERE' => $ID_STOCK_MATIERE]);

				$QUANTITE2 = $data['QUANTITE_RECUE'];
				$quant = $don['QUANTITE_RECUE'] + ($QUANTITE - $QUANTITE2);

				$datas['QUANTITE_RECUE'] = $quant;

				$update = $this->Model->update('stock_matieres_premieres', ['ID_STOCK_MATIERE' => $ID_STOCK_MATIERE], $datas);

				if ($update) {
					$datashisto = array(
						'ID_STOCK_MATIERE' => $ID_STOCK_MATIERE,
						'ID_TYPE_MATIERE' => $ID_TYPE_MATIERE,
						'DESCRIPTION' => $this->input->post('DESCRIPTION'),
						'QUANTITE_RECUE' => $QUANTITE,
						'ID_FOURNISSEUR' => $ID_FOURNISSEUR,
						'DATE_ENTREE' => $DATE_ENTREE,
						'ID_STATUT_MATIERE' => 1,
						'ID_USER' => $this->session->userdata('SUPERBAT_ID_USER')
					);

					$this->Model->update('historique_stock_matieres_premieres', ['ID_HISTO_STOCK_MATIERE' => $ID_HISTO_STOCK_MATIERE], $datashisto);
				}
			}

        // Utiliser 'success' au lieu de 'message'
			$this->session->set_flashdata('success', 'Le stock de matières a été modifié avec succès.');
			redirect(base_url('stock_matieres/Stock_Matieres_New'));
		}
	}


	public function effacer($id)
	{
    // Supprimer la matière du stock
		$delete = $this->Model->delete('stock_matieres_premieres', ['ID_STOCK_MATIERE' => $id]);

		if ($delete) {
        // Message de succès
			$this->session->set_flashdata('success', 'Le stock de matières a été supprimé avec succès.');
		} else {
        // Message d'erreur si la suppression échoue
			$this->session->set_flashdata('error', 'La suppression du stock de matières a échoué. Veuillez réessayer.');
		}

		redirect(base_url('stock_matieres/Stock_Matieres_New'));
	}

	public function upload_excel()
	{
		if (empty($_FILES['fichier_excel']['name'])) {
			$this->session->set_flashdata('error', 'Veuillez choisir un fichier Excel.');
			redirect(base_url('stock_matieres/Stock_Matieres_New/ajouter'));
		}

		$tmpFilePath = $_FILES['fichier_excel']['tmp_name'];
		if (!$tmpFilePath || !file_exists($tmpFilePath)) {
			$this->session->set_flashdata('error', 'Impossible de lire le fichier temporaire.');
			redirect(base_url('stock_matieres/Stock_Matieres_New/ajouter'));
		}

		$date_entree = $this->input->post('DATE_ENTREE_EXCEL');
		if (!$date_entree) $date_entree = date('Y-m-d');

		try {
			$spreadsheet = IOFactory::load($tmpFilePath);
			$sheetData   = $spreadsheet->getActiveSheet()->toArray();
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			$this->session->set_flashdata('error', 'Erreur lecture fichier Excel : ' . $e->getMessage());
			redirect(base_url('stock_matieres/Stock_Matieres_New/ajouter'));
		}

    $data = array_slice($sheetData, 2);   // on saute 2 lignes
    $data = array_filter($data, function($row){
    	return !empty(array_filter($row));
    });
    $data = array_values($data);

    /** ---- TOTALS ---- **/
    $totalNet = 0;
    $totalGross = 0;

    foreach($data as $row){
    	$totalNet   += floatval($row[6]);
    	$totalGross += floatval($row[7]);
    }

    $total = ceil($totalNet);
    $NUMERO_LOT = $this->generateLotNumber($total);

    /** ---- INSERT MASTER STOCK ---- **/
    $datas = [
    	'ID_TYPE_MATIERE'   => 33,
    	'QUANTITE_COMMANDE' => $totalNet,
    	'QUANTITE_RECUE'    => $totalNet,
    	'LOT_MP'            => $NUMERO_LOT,
    	'DATE_ENTREE'       => $date_entree,
    	'ID_STATUT_MATIERE' => 1,
    	'ID_USER'           => $this->session->userdata('SUPERBAT_ID_USER')
    ];

    $ID_STOCK_MATIERE = $this->Model->insert_last_id('stock_matieres_premieres', $datas);

    /** ---- HISTORIQUE ---- **/
    $this->Model->create('historique_stock_matieres_premieres', $datas);

    /** ---- DETAILS PACKING LIST ---- **/
    $success = 0;
    $fail = 0;

    foreach ($data as $row) {

    	$TYPE_MATIERE = trim($row[1]);  
    	$SIZE         = trim($row[2]);
    	$COILS_NUMBER = trim($row[3]);
    	$COLOUR       = trim($row[4]);
    	$METER        = trim($row[5]);
    	$NET_WEIGHT   = trim($row[6]);
    	$GORSS_WEIGHT = trim($row[7]);

        // Vérifier si type matière existe
    	$exists = $this->Model->getOne('type_matieres', ['TYPE_ABREV' => $TYPE_MATIERE]);

    	if ($exists) {

    		$data_enregistrer = [
    			'ID_STOCK_MATIERE' => $ID_STOCK_MATIERE,
    			'LOT_MP'           => $NUMERO_LOT,
    			'ID_TYPE_MATIERE'  => $exists['ID_TYPE_MATIERE'],
    			'SIZE'             => $SIZE,
    			'COILS_NUMBER'     => $COILS_NUMBER,
    			'COLOUR'           => $COLOUR,
    			'METER'            => $METER,
    			'NET_WEIGHT'       => $NET_WEIGHT,
    			'GORSS_WEIGHT'     => $GORSS_WEIGHT,
    			'DATE_ENTREE'      => $date_entree
    		];

    		$id_packing = $this->Model->insert_last_id('packinglist_matieres', $data_enregistrer);

    		if ($id_packing) $success++;
    		else $fail++;

    	} else {
    		$fail++;
    	}
    }

    // Mise à jour du nombre de coils
    $this->Model->update(
    	'stock_matieres_premieres',
    	['ID_STOCK_MATIERE' => $ID_STOCK_MATIERE],
    	['NBRE_COILS' => $success]
    );

    if($fail > 0){
    	$this->session->set_flashdata('error', "$fail lignes non importées (Type matière introuvable)");
    }

    $this->session->set_flashdata('success', "Import terminé : $success lignes insérées, $fail échouées.");
    redirect(base_url('stock_matieres/Stock_Matieres_New/ajouter'));
}


// Générer un numéro de lot unique
function generateLotNumber($totalnet)
{
    // Exemple : L + ID_TYPE_MATIERE + YYYYMMDD + compteur
    $date = date('md'); // ex: 20260110
    $prefix = 'L' .$date.'_'.$totalnet;

    // Chercher le dernier lot pour ce type aujourd'hui
    $last_lot = $this->Model->getRequeteOne(
    	"SELECT LOT_MP FROM stock_matieres_premieres 
    	WHERE LOT_MP LIKE '{$prefix}%' 
    	ORDER BY LOT_MP DESC LIMIT 1"
    );

    if ($last_lot) {
        // Récupérer le compteur et l’incrémenter
    	$last_counter = intval(substr($last_lot['LOT_MP'], -3));
    	$counter = str_pad($last_counter + 1, 3, '0', STR_PAD_LEFT);
    } else {
    	$counter = '001';
    }
// . $counter; 
    return $prefix.'_'. $counter; // ex: L5 20260110 001
}



function liste_reception(){
	$data['title']='Réception des matières premières';
	$commandes = $this->Model->getRequete("SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.UNITE, `NBRE_COIlS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM AS user,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE stock_matieres_premieres.ID_STATUT_MATIERE!=2");
	$data['commandes'] = $commandes;
	$this->load->view('Stock_Matieres_List_Reception_View',$data);
}




public function listing_reception()
{

	$query_principal ="SELECT `ID_STOCK_MATIERE`, stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.UNITE, `NBRE_COIlS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, stock_matieres_premieres.`ID_USER`,admin_user.NOM,admin_user.PRENOM, `DATE_ENTREE`, `DATE_INSERT`,stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE FROM `stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =stock_matieres_premieres.ID_STATUT_MATIERE WHERE stock_matieres_premieres.ID_STATUT_MATIERE=2" ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION', 'NBRE_COIlS','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR NBRE_COIlS LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$row[] = $key->DESCRIPTION.'('.$key->UNITE.')';
		$row[] = $key->NBRE_COIlS ? $key->NBRE_COIlS.'('.$key->COULEUR.')' : "N/A" ;
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
    		'NBRE_COIlS'=>$donne_stock['NBRE_COIlS'],
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
		'NBRE_COIlS'=>$donne_stock['NBRE_COIlS'],
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


public function get_details()
{

	$id=$this->input->post('id');


	$query_principal ="SELECT `ID_STOCK_MATIERE`, historique_stock_matieres_premieres.`ID_HISTO_STOCK_MATIERE`, historique_stock_matieres_premieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.UNITE, `NBRE_COIlS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`, `QUANTITE_RECUE`, historique_stock_matieres_premieres.`ID_FOURNISSEUR`,fournisseur.NOM,fournisseur.LOCALITE, historique_stock_matieres_premieres.`ID_USER`,admin_user.NOM as user,admin_user.PRENOM, `DATE_ENTREE`,`DATE_INSERT_HISTO`,historique_stock_matieres_premieres.ID_STATUT_MATIERE,statut_matieres.DESCRIPTION_STATUT_MATIERE,MOTIF FROM `historique_stock_matieres_premieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=historique_stock_matieres_premieres.ID_TYPE_MATIERE JOIN fournisseur ON fournisseur.ID_FOURNISSEUR JOIN admin_user ON admin_user.ID_USER=historique_stock_matieres_premieres.ID_USER JOIN statut_matieres ON statut_matieres.ID_STATUT_MATIERE =historique_stock_matieres_premieres.ID_STATUT_MATIERE WHERE `ID_STOCK_MATIERE`=".$id ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION', 'NBRE_COIlS','LONGEUR','COULEUR','QUANTITE_COMMANDE','QUANTITE_RECUE','fournisseur.NOM','admin_user.NOM','DATE_ENTREE');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR NBRE_COIlS LIKE '%$var_search%' OR LONGEUR LIKE '%$var_search%' OR fournisseur.NOM LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
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
		$row[] = $key->DESCRIPTION.'('.$key->UNITE.')';
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

public function get_packing()
{

	$id=$this->input->post('id');


	$query_principal ="SELECT `ID_PACKING_LIST`, `ID_STOCK_MATIERE`, `LOT_MP`, type_matieres.`ID_TYPE_MATIERE`,type_matieres.DESCRIPTION,type_matieres.UNITE,type_matieres.TYPE_ABREV, `SIZE`, `COILS_NUMBER`, `COLOUR`, `METER`, `NET_WEIGHT`, `GORSS_WEIGHT`, `ACTUAL_WEIGHT`, `STATUT_ENTREE`, `DATE_ENTREE`, `DATE_INSERTION` FROM `packinglist_matieres` JOIN type_matieres ON type_matieres.ID_TYPE_MATIERE=packinglist_matieres.ID_TYPE_MATIERE WHERE  `ID_STOCK_MATIERE`=".$id ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_STOCK_MATIERE','type_matieres.DESCRIPTION', 'SIZE','COILS_NUMBER','COLOUR','METER','NET_WEIGHT','GORSS_WEIGHT','DATE_ENTREE');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_FOURNISSEUR ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND type_matieres.DESCRIPTION LIKE '%$var_search%' OR COILS_NUMBER LIKE '%$var_search%' OR COLOUR LIKE '%$var_search%' OR METER LIKE '%$var_search%' OR NET_WEIGHT LIKE '%$var_search%' OR DATE_FORMAT(DATE_ENTREE, '%d/%m/%Y') LIKE '%$var_search%'  "
	: '';

	$critaire = '';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$row[] = date("d/m/Y", strtotime($key->DATE_ENTREE));
		$row[] = $key->DESCRIPTION;
		$row[] = $key->SIZE ;
		$row[] = $key->COILS_NUMBER ;
		$row[] = $key->COLOUR;
		$row[] = $key->METER;
		$row[] = $key->NET_WEIGHT;
		$row[] = $key->GORSS_WEIGHT;

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
