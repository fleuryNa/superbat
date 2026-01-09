<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produits_Finis extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->Is_Connected();
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
		$data['title']="Liste de Produits";
		$tansfere = $this->Model->getRequete("SELECT ID_TRANSFERER_STOCK,ID_TYPE_PRODUIT ,IF (ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit, type_toles.DESCRIPTION_TOLES,type_clous.DESCRIPTION AS clous, tps.ID_TYPE_CLOUS, QUANTITE, DATE_INSERT_TRANSFER, NUMERO_COLIS, user.NOM,user.PRENOM, ID_USER_RECEPTEUR,statut_produits.DESCRIPTION_PRODUITS,COULEUR,LONGUEUR  FROM transferer_prod_stock tps LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES=tps.ID_TYPE_TOLES LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=tps.ID_TYPE_CLOUS LEFT JOIN admin_user user ON user.ID_USER=tps.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=tps.ID_STATUT_PRODUITS WHERE tps.ID_STATUT_PRODUITS=1 ORDER BY tps.ID_TRANSFERER_STOCK DESC");
		$data['transferes'] = $tansfere;
		$this->load->view('Produits_Finis_List_View',$data);
	}



	public function listing()
	{

		$query_principal ="SELECT ID_TRANSFERER_STOCK,ID_TYPE_PRODUIT ,IF (ID_TYPE_PRODUIT=1,'Toles','Clous') AS produit, type_toles.DESCRIPTION_TOLES,type_clous.DESCRIPTION AS clous, tps.ID_TYPE_CLOUS, QUANTITE, DATE_INSERT_TRANSFER, NUMERO_COLIS, user.NOM,user.PRENOM, ID_USER_RECEPTEUR,statut_produits.DESCRIPTION_PRODUITS,COULEUR,LONGUEUR  FROM transferer_prod_stock tps LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES=tps.ID_TYPE_TOLES LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=tps.ID_TYPE_CLOUS LEFT JOIN admin_user user ON user.ID_USER=tps.ID_USER_EXPEDITEUR JOIN statut_produits ON statut_produits.ID_STATUT_PRODUITS=tps.ID_STATUT_PRODUITS WHERE tps.ID_STATUT_PRODUITS=2" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_TRANSFERER_STOCK', 'produit','type_clous.DESCRIPTION','user.NOM', 'user.PRENOM', 'NUMERO_COLIS', 'QUANTITE', 'DATE_INSERT_TRANSFER');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_TRANSFERER_STOCK ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND produit LIKE '%$var_search%' OR type_clous.DESCRIPTION LIKE '%$var_search%' OR user.NOM LIKE '%$var_search%' OR user.PRENOM LIKE '%$var_search%'  LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERT_TRANSFER, '%d/%m/%Y') LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();

			$row[] = date("d/m/Y", strtotime($key->DATE_INSERT_TRANSFER));
			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->produit;
			$row[] = $key->ID_TYPE_PRODUIT==1 ? $key->DESCRIPTION_TOLES.'('.$key->NUMERO_COLIS.')' : $key->clous  ;
			$row[] = $key->QUANTITE ;
			
			

			
			
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


	private function verifierProduitPost($index)
	{
		$typeProduit  = $this->input->post('type_produit')[$index];
		$quantite     = $this->input->post('quantite')[$index];
		$numeroColis  = $this->input->post('numero_Colis')[$index];
		$couleur      = $this->input->post('couleur')[$index];
		$longueur     = $this->input->post('longueur')[$index];
		$idCommande   = $this->input->post('id_commande')[$index];

    // Sécurité : récupération DB
		$transfer = $this->Model->getOne(
			'transferer_prod_stock',
			['ID_TRANSFERER_STOCK' => $idCommande]
		);

		if (empty($transfer)) {
			return ['status' => false, 'message' => 'Commande introuvable.'];
		}

		/* ===================== TOLES ===================== */
		if ($typeProduit == 1) {

			if (empty($numeroColis) || empty($couleur) || empty($longueur)) {
				return ['status' => false, 'message' => 'Infos tôle incomplètes.'];
			}

			$stock = $this->Model->getOne(
				'stock_production',
				[
					'ID_TYPE_PRODUIT' => 1,
					'ID_TYPE_TOLES'   => $transfer['ID_TYPE_TOLES'],
					'NUMERO_COLIS'    => $numeroColis,
					'COULEUR'         => $couleur,
					'LONGUEUR'        => $longueur
				]
			);

			if (empty($stock) || $stock['NOMBRE_BONNET'] < $quantite) {
				return ['status' => false, 'message' => 'Stock tôle insuffisant.'];
			}

			return ['status' => true, 'type' => 'toles', 'stock' => $stock];
		}

		/* ===================== CLOUS ===================== */
		if ($typeProduit == 2) {



			$stock = $this->Model->getOne(
				'stock_production',
				[
					'ID_TYPE_PRODUIT' => 2,
					'ID_TYPE_CLOUS'   => $transfer['ID_TYPE_CLOUS'],
				]
			);

			if (empty($stock) || $stock['QUANTITE'] < $quantite) {
				return ['status' => false, 'message' => 'Stock clous insuffisant.'];
			}

			return ['status' => true, 'type' => 'clous', 'stock' => $stock];
		}

		return ['status' => false, 'message' => 'Type de produit non valide.'];
	}


	public function traitement()
	{
		$select_commande = $this->input->post('select_commande');
		$id_commandes    = $this->input->post('id_commande');
		$quantites       = $this->input->post('quantite');

		if (empty($select_commande)) {
			$this->session->set_flashdata('error', 'Aucune commande sélectionnée.');
			redirect('produit_finis/Produits_Finis');
		}

		$this->db->trans_begin();

		foreach ($id_commandes as $index => $id_commande) {

        // Ne traiter que les lignes cochées
			if (!in_array($id_commande, $select_commande)) {
				continue;
			}

        // Vérification complète du produit (tôle / clous)
			$check = $this->verifierProduitPost($index);

			if ($check['status'] === false) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', $check['message']);
				redirect('produit_finis/Produits_Finis');
			}

			$stock     = $check['stock'];
			$quantite  = (int) $quantites[$index];
			$type      = $check['type'];

			/* ===================== MISE À JOUR STOCK ===================== */
			if ($type == 'toles') {

				$this->Model->update(
					'stock_production',
					['ID_PRODUCTION' => $stock['ID_PRODUCTION']],
					[
						'NOMBRE_BONNET' => $stock['NOMBRE_BONNET'] - $quantite,
						'ID_STATUT_PRODUITS'=>2
					]
				);

				$this->Model->create('historique_stock_production', [
					'ID_PRODUCTION'   => $stock['ID_PRODUCTION'],
					'ID_TYPE_PRODUIT' => 1,
					'ID_TYPE_TOLES'   => $stock['ID_TYPE_TOLES'],
					'NUMERO_COLIS'    => $stock['NUMERO_COLIS'],
					'COULEUR'         => $stock['COULEUR'],
					'LONGUEUR'        => $stock['LONGUEUR'],
					'NOMBRE_BANDE'    => $stock['NOMBRE_BANDE'],
					'NOMBRE_BONNET'   => $quantite,
					'ID_STATUT_PRODUITS'=>2,
					'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
					'DATE_INSERTION'  => date('Y-m-d H:i:s')
				]);

        } else { // CLOUS

        	$this->Model->update(
        		'stock_production',
        		['ID_PRODUCTION' => $stock['ID_PRODUCTION']],
        		['QUANTITE' => $stock['QUANTITE'] - $quantite]
        	);

        	$this->Model->create('historique_stock_production', [
        		'ID_PRODUCTION'   => $stock['ID_PRODUCTION'],
        		'ID_TYPE_PRODUIT' => 2,
        		'ID_TYPE_CLOUS'   => $stock['ID_TYPE_CLOUS'],
        		'QUANTITE'        => $quantite,
        		'ID_STATUT_PRODUITS'=>2,
        		'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
        		'DATE_INSERTION'  => date('Y-m-d H:i:s')
        	]);
        }

        /* ===================== MISE À JOUR TRANSFERT ===================== */
        $this->Model->update(
        	'transferer_prod_stock',
        	['ID_TRANSFERER_STOCK' => $id_commande],
        	[
        		'QUANTITE'          => $quantite,
        		'ID_STATUT_PRODUITS'   => 2,
        		'ID_USER_RECEPTEUR' => $this->session->userdata('SUPERBAT_ID_USER')
        	]
        );
    }

    // Validation finale
    if ($this->db->trans_status() === FALSE) {
    	$this->db->trans_rollback();
    	$this->session->set_flashdata('error', 'Erreur lors du traitement.');
    } else {
    	$this->db->trans_commit();
    	$this->session->set_flashdata('success', 'Réception des produits validée avec succès.');
    }

    redirect('produit_finis/Produits_Finis');
}



function sortant(){
	$data['title']="Liste des commandes";
	$tansfere = $this->Model->getRequete("SELECT `ID_VENTE`,NUMERO_FATURE,ID_TYPE_PRODUIT, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, type_toles.ID_TYPE_TOLES, vente_commande.ID_TYPE_CLOUS,type_toles.DESCRIPTION_TOLES, `QUANTITE_VENTE`,type_clous.DESCRIPTION, `PRIX_UNITAIRE`, vente_commande.ID_CLIENT,client_acheteur.NOM as client,client_acheteur.TELEPHONE, `ID_STATUT_VENTE`, vente_commande.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION`,NUMERO_COLIS,COULEUR,LONGUEUR FROM `vente_commande` LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES= vente_commande.ID_TYPE_TOLES JOIN client_acheteur ON client_acheteur.ID_CLIENT=vente_commande.ID_CLIENT JOIN admin_user ON admin_user.ID_USER=vente_commande.ID_USER LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=vente_commande.ID_TYPE_CLOUS WHERE ID_STATUT_VENTE =1 GROUP BY NUMERO_FATURE ");
	$data['commandes'] = $tansfere;
	$this->load->view('Sortant_List_View',$data);
}


public function listing_traite()
{

	$query_principal ="SELECT `ID_VENTE`,NUMERO_FATURE,ID_TYPE_PRODUIT, IF (`ID_TYPE_PRODUIT`=1,'Toles','Clous') AS produit, type_toles.ID_TYPE_TOLES, vente_commande.ID_TYPE_CLOUS,type_toles.DESCRIPTION_TOLES, `QUANTITE_VENTE`,type_clous.DESCRIPTION, `PRIX_UNITAIRE`, vente_commande.ID_CLIENT,client_acheteur.NOM as client,client_acheteur.TELEPHONE, `ID_STATUT_VENTE`, vente_commande.ID_USER,admin_user.NOM,admin_user.PRENOM, `DATE_INSERTION` FROM `vente_commande` LEFT JOIN type_toles ON type_toles.ID_TYPE_TOLES= vente_commande.ID_TYPE_TOLES JOIN client_acheteur ON client_acheteur.ID_CLIENT=vente_commande.ID_CLIENT JOIN admin_user ON admin_user.ID_USER=vente_commande.ID_USER LEFT JOIN type_clous ON type_clous.ID_TYPE_CLOUS=vente_commande.ID_TYPE_CLOUS WHERE ID_STATUT_VENTE =2 " ;

	$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

	$limit = 'LIMIT 0,10';

	if (isset($_POST['length']) && $_POST['length'] != -1) {
		$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
	}

	$order_by = '';

	$order_column = array('ID_VENTE', 'produit','client_acheteur.NOM', 'type_toles.DESCRIPTION_TOLES','admin_user.NOM', 'admin_user.PRENOM', 'QUANTITE_VENTE', 'DATE_INSERTION');

	$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

	$search = !empty($_POST['search']['value']) ?
	"AND NUMERO_FATURE LIKE '%$var_search%'  OR produit LIKE '%$var_search%'  OR type_toles.DESCRIPTION_TOLES LIKE '%$var_search%' OR admin_user.NOM LIKE '%$var_search%' OR admin_user.PRENOM LIKE '%$var_search%' OR client_acheteur.NOM LIKE '%$var_search%' OR DATE_FORMAT(DATE_INSERTION, '%d/%m/%Y') LIKE '%$var_search%' "
	: '';

	$critaire = '';

	$groupeby = ' GROUP BY NUMERO_FATURE';

	$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search. ' ' . $critaire .'  '.$groupeby . ' ' . $order_by . '   ' . $limit ;
	$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

	$resultat = $this->Model->datatable($query_secondaire);

	$data = array();
	foreach ($resultat as $key) {
		$row = array();

		$statut=$this->Model->getOne('statut_vente',['ID_STATUT_VENTE' => $key->ID_STATUT_VENTE]);

		$row[] = $key->NOM." ".$key->PRENOM;
		$row[] = $key->client.'('.$key->TELEPHONE.')' ;
		$row[] = $key->produit;
		$row[] =  $key->DESCRIPTION_TOLES;
		$row[] =  $key->QUANTITE_VENTE;
		$row[] =  $key->PRIX_UNITAIRE;
		$row[] =  $statut['DESCRIPTION_VENTE'];

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


public function vendre()
{
	if ($this->input->method() !== 'post') {
		show_error('Accès interdit', 403);
	}

	$ids_selected     = (array) $this->input->post('select_commande');
	$numeros_facture  = (array) $this->input->post('numerofacture');
	$id_vente         = (array) $this->input->post('id_vente');
	$quantite         = (array) $this->input->post('quantite');

	if (empty($ids_selected)) {
		$this->session->set_flashdata('error',
			"<div class='alert alert-danger'>Aucune ligne sélectionnée.</div>"
		);
		redirect('produit_finis/Produits_Finis');
	}

	$this->db->trans_begin();

	try {

		foreach ($id_vente as $i => $vente_id) {

			if (!in_array($vente_id, $ids_selected)) {
				continue;
			}

			$qte_demande = (int) $quantite[$i];
			$facture    = $numeros_facture[$i];

			if ($qte_demande <= 0) {
				throw new Exception("Quantité invalide (Facture $facture)");
			}

            /* ===============================
             * RÉCUPÉRATION DES LIGNES FACTURE
             * =============================== */
            $lignes = $this->Model->getRequete(
            	"SELECT * FROM vente_commande WHERE NUMERO_FATURE = ? AND ID_STATUT_VENTE = 1",
            	[$facture]
            );

            if (!$lignes) {
            	throw new Exception("Facture $facture introuvable.");
            }

            foreach ($lignes as $ligne) {

                /* ===============================
                 * GESTION STOCK
                 * =============================== */
                if ($ligne['ID_TYPE_PRODUIT'] == 1) {
                    // 🔹 TÔLES
                	$where_stock = [
                		'ID_TYPE_TOLES' => $ligne['ID_TYPE_TOLES'],
                		'NUMERO_COLIS'  => $ligne['NUMERO_COLIS'],
                		'COULEUR'       => $ligne['COULEUR'],
                		'LONGUEUR'      => $ligne['LONGUEUR']
                	];
                } else {
                    // 🔹 CLOUS
                	$where_stock = [
                		'ID_TYPE_CLOUS' => $ligne['ID_TYPE_CLOUS']
                	];
                }

                $stock = $this->Model->getOne('transferer_prod_stock', $where_stock);

                if (!$stock || $stock['QUANTITE'] < $qte_demande) {
                	throw new Exception("Stock insuffisant (Facture $facture)");
                }

                /* ===============================
                 * DÉDUCTION STOCK
                 * =============================== */
                $this->Model->update(
                	'transferer_prod_stock',
                	$where_stock,
                	['QUANTITE' => $stock['QUANTITE'] - $qte_demande]
                );

                /* ===============================
                 * MISE À JOUR VENTE
                 * =============================== */
                $this->Model->update(
                	'vente_commande',
                	['ID_VENTE' => $ligne['ID_VENTE']],
                	['ID_STATUT_VENTE' => 2]
                );

                /* ===============================
                 * HISTORIQUE
                 * =============================== */
                $this->Model->create('historique_vente_commande', [
                	'ID_VENTE'        => $ligne['ID_VENTE'],
                	'NUMERO_FATURE'   => $ligne['NUMERO_FATURE'],
                	'ID_TYPE_PRODUIT' => $ligne['ID_TYPE_PRODUIT'],
                	'ID_TYPE_TOLES'   => $ligne['ID_TYPE_TOLES'],
                	'ID_TYPE_CLOUS'   => $ligne['ID_TYPE_CLOUS'],
                	'QUANTITE_VENTE'  => $ligne['QUANTITE_VENTE'],
                	'PRIX_UNITAIRE'   => $ligne['PRIX_UNITAIRE'],
                	'ID_CLIENT'       => $ligne['ID_CLIENT'],
                	'ID_STATUT_VENTE' => 2,
                	'NUMERO_COLIS'    => $ligne['NUMERO_COLIS'],
                	'COULEUR'         => $ligne['COULEUR'],
                	'LONGUEUR'        => $ligne['LONGUEUR'],
                	'ID_USER'         => $this->session->userdata('SUPERBAT_ID_USER'),
                	'DATE_INSERTION'  => date('Y-m-d H:i:s')
                ]);
            }
        }

        if ($this->db->trans_status() === false) {
        	throw new Exception('Erreur base de données.');
        }

        $this->db->trans_commit();

        $this->session->set_flashdata('success',
        	"<div class='alert alert-success'>Vente validée avec succès.</div>"
        );

    } catch (Exception $e) {

    	$this->db->trans_rollback();

    	$this->session->set_flashdata('error',
    		"<div class='alert alert-danger'>{$e->getMessage()}</div>"
    	);
    }

    redirect('produit_finis/Produits_Finis');
}





}

