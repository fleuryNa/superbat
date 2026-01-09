<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Entree extends MY_Controller {

	
	public function index()
	{
		$data['title']="Arrivee des matieres premieres.";
		$this->load->view('Stock_Entree_Add_View',$data);
	}

 
	public function listing() 
	{

		$query_principal ="SELECT ID_USER, NOM, PRENOM, USERNAME, config_profil.DESCRIPTION as DESCRIPTION, masque_agence_msi.DESCRIPTION AS AGENCE, admin_user.STATUS AS STATUS FROM `admin_user` JOIN config_profil ON config_profil.PROFIL_ID = admin_user.PROFIL_ID JOIN masque_agence_msi ON masque_agence_msi.ID_AGENCE = admin_user.ID_AGENCE" ;

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_USER', 'NOM','PRENOM', 'USERNAME','config_profil.DESCRIPTION', 'masque_agence_msi.DESCRIPTION', 'admin_user.STATUS');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY ID_USER ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND NOM LIKE '%$var_search%' OR PRENOM LIKE '%$var_search%' OR USERNAME LIKE '%$var_search%' OR config_profil.DESCRIPTION LIKE '%$var_search%' OR masque_agence_msi.DESCRIPTION LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		$data = array();
		foreach ($resultat as $key) {
			$row = array();


			if ($key->STATUS == 1) {
				$stat = 'Actif';
				$fx = 'desactiver';
				$col = 'btn-danger';
				$titr = 'Désactiver';
				$stitr = 'voulez-vous désactiver cet utilisateur ';
				$bigtitr = 'Désactivation de cet utilisateur';
				$icone= '<i class="fa fa-lock"></i>';
			}
			else{
				$stat = 'Innactif';
				$fx = 'reactiver';
				$col = 'btn-primary';
				$titr = 'Réactiver';
				$stitr = 'voulez-vous réactiver cet  utilisateur';
				$bigtitr = 'Réactivation de cet  utilisateur';
				$icone= '<i class="fa fa-unlock"></i>';
			}

			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->USERNAME;
			$row[] = $key->AGENCE;
			$row[] = $key->DESCRIPTION;
			$row[] = $stat;

			$row[] = '
			<div class="modal fade" id="desactcat'.$key->ID_USER.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">'.$bigtitr.'</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<h6><b>Mr/Mme , </b> '.$stitr.' ('.$key->NOM.' '.$key->PRENOM.')?</h6>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			<a href="'.base_url("administration/User/".$fx."/".$key->ID_USER).'" class="btn '.$col.'">'.$titr.'</a>
			</div>
			</div>
			</div>
			</div>

			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("administration/User/index_update/".$key->ID_USER).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" href="#" data-toggle="modal" data-target="#desactcat'.$key->ID_USER.'">'.$icone.' '.$titr.' </a> 
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
		$data['title']='STOCK MATIERES PREMIERES';

		$data['type_mat']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');

		$data['fournisseurs']=$this->Model->getRequete('SELECT * FROM `fournisseurs` order by NOM_FOURNISSEUR');

		$data['users']=$this->Model->getRequete('SELECT * FROM `admin_user` order by NOM');

		$this->load->view('Stock_Entree_Add_View',$data);

	}







	public function save()
	{

$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');

		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');

		$LONGEUR=$this->input->post('LONGEUR');

		$COULEUR=$this->input->post('COULEUR');

		$QUANTITE_COMMANDE=$this->input->post('QUANTITE_COMMANDE');

		$QUANTITE_RECUE=$this->input->post('QUANTITE_RECUE');
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$ID_USER=$this->input->post('ID_USER');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');




		$this->form_validation->set_rules('ID_TYPE_MATIERE', 'Type', 'required');

		$this->form_validation->set_rules('NUMERO_COLIS', 'Numero', 'required');

		$this->form_validation->set_rules('LONGEUR', 'Longeur', 'required|callback_check_username_unique');

		$this->form_validation->set_rules('COULEUR', 'Couleur', 'required');

		$this->form_validation->set_rules('QUANTITE_COMMANDE', 'Quantite Commander', 'required');

		$this->form_validation->set_rules('QUANTITE_RECUE', 'Quantite recue', 'required');

		$this->form_validation->set_rules('ID_FOURNISSEUR', 'Fourisseur', 'required');

		$this->form_validation->set_rules('ID_USER', 'Utilisateur', 'required');

		$this->form_validation->set_rules('DATE_ENTREE', 'Date entree', 'required');






		if ($this->form_validation->run() == FALSE){

			$message = "<div class='alert alert-danger'>

			Op&eacute;ration  non r&eacute;ussi !

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

		$data['title']='STOCK MATIERES PREMIERES';

		$data['type_mat']=$this->Model->getRequete('SELECT * FROM `type_matieres` order by DESCRIPTION');

		$data['fournisseurs']=$this->Model->getRequete('SELECT * FROM `fournisseurs` order by NOM_FOURNISSEUR');

		$data['users']=$this->Model->getRequete('SELECT * FROM `admin_user` order by NOM');

		$this->load->view('Stock_Entree_Add_View',$data);

		}else{

				$ID_TYPE_MATIERE=$this->input->post('ID_TYPE_MATIERE');

		$NUMERO_COLIS=$this->input->post('NUMERO_COLIS');

		$LONGEUR=$this->input->post('LONGEUR');

		$COULEUR=$this->input->post('COULEUR');

		$QUANTITE_COMMANDE1=$this->input->post('QUANTITE_COMMANDE');

		$QUANTITE_RECUE1=$this->input->post('QUANTITE_RECUE');
		$ID_FOURNISSEUR=$this->input->post('ID_FOURNISSEUR');
		$ID_USER=$this->input->post('ID_USER');
		$DATE_ENTREE=$this->input->post('DATE_ENTREE');


		$quant=$this->Model->getRequeteOne('SELECT ID_STOCK_MATIERE,QUANTITE_RECUE,QUANTITE_COMMANDE FROM `stock_matieres_premieres` where ID_TYPE_MATIERE='.$ID_TYPE_MATIERE.'');


		if (!empty($quant)) {
		$QUANTITE_COMMANDE=$this->input->post('QUANTITE_COMMANDE')+$quant['QUANTITE_COMMANDE'];

		$QUANTITE_RECUE=$this->input->post('QUANTITE_RECUE')+$quant['QUANTITE_RECUE'];
		}
		else{
    $QUANTITE_COMMANDE=$this->input->post('QUANTITE_COMMANDE');

		$QUANTITE_RECUE=$this->input->post('QUANTITE_RECUE');

		}


			$datasuser=array(

				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,
				'LONGEUR'=>$LONGEUR,
				'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,
        'QUANTITE_RECUE'=>$QUANTITE_RECUE,
				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'ID_USER'=>$ID_USER,
				'DATE_ENTREE'=>$DATE_ENTREE,
        );

			if (empty($quant)) {
			$val=$this->Model->insert_last_id('stock_matieres_premieres',$datasuser); 
			$ID_STOCK_MATIERE=$val;
			}
			else{
			$datasuser=array(

				
				'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,

        'QUANTITE_RECUE'=>$QUANTITE_RECUE,

        );
			$this->Model->update('stock_matieres_premieres',array('ID_TYPE_MATIERE'=>$quant['ID_TYPE_MATIERE']),$datasuser);	
			$ID_STOCK_MATIERE=$quant['ID_STOCK_MATIERE'];
			}

	

			$datasuser2=array(

				'ID_STOCK_MATIERE'=>$ID_STOCK_MATIERE,
       
				'ID_TYPE_MATIERE'=>$ID_TYPE_MATIERE,

				'NUMERO_COLIS'=>$NUMERO_COLIS,

				'LONGEUR'=>$LONGEUR,

				'QUANTITE_COMMANDE'=>$QUANTITE_COMMANDE,

        'QUANTITE_RECUE'=>$QUANTITE_RECUE,

				'ID_FOURNISSEUR'=>$ID_FOURNISSEUR,
				'ID_USER'=>$ID_USER,
				'DATE_ENTREE'=>$DATE_ENTREE,
				'ACTION'=>'ENTREE DANS LE STOCK',


        );

			$this->Model->create('stock_matieres_premieres',$datasuser);  

			$message = "<div class='alert alert-success' id='message'>

			Opération avec succés

			<button type='button' class='close' data-dismiss='alert'>&times;</button>

			</div>";

			$this->session->set_flashdata(array('message'=>$message));

			redirect(base_url('first_materials/Stock_Entree/list'));  

		}

	}

public function liste()
{
$data['title']='Contenu du stock des matieres premieres';
$this->load->view('Stock_Entree_List_View',$data);


}

public function list()
{
	$query_principal ="SELECT `ID_STOCK_MATIERE`, admin_user.NOM,admin_user.PRENOM,fournisseurs.NOM_FOURNISSEUR,type_matieres.DESCRIPTION, `NUMERO_COLIS`, `LONGEUR`, `COULEUR`, `QUANTITE_COMMANDE`,QUANTITE_RECUE,`DATE_ENTREE` FROM `stock_matieres_premieres` left JOIN fournisseurs ON stock_matieres_premieres.`ID_STOCK_MATIERE` = fournisseurs.`ID_FOURNISSEUR` left JOIN type_matieres ON stock_matieres_premieres.`ID_TYPE_MATIERE` = type_matieres.ID_TYPE_MATIERE left join admin_user on stock_matieres_premieres.`ID_USER`=admin_user.ID_USER";

		$var_search = !empty($_POST['search']['value']) ? $this->db->escape_like_str($_POST['search']['value']) : null;

		$limit = 'LIMIT 0,10';

		if (isset($_POST['length']) && $_POST['length'] != -1) {
			$limit = 'LIMIT ' . (isset($_POST["start"]) ? $_POST["start"] : 0) . ',' . $_POST["length"];
		}

		$order_by = '';

		$order_column = array('ID_STOCK_MATIERE', 'NOM','PRENOM');

		$order_by = isset($_POST['order']) ? ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . '  ' . $_POST['order']['0']['dir'] : ' ORDER BY admin_user.ID_USER ASC';

		$search = !empty($_POST['search']['value']) ?
		"AND NOM LIKE '%$var_search%' OR PRENOM LIKE '%$var_search%' OR NOM_FOURNISSEUR LIKE '%$var_search%' OR QUANTITE_COMMANDE LIKE '%$var_search%' OR QUANTITE_RECUE LIKE '%$var_search%' "
		: '';

		$critaire = '';

		$query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . '   ' . $limit;
		$query_filter = $query_principal . ' ' . $critaire . ' ' . $search;

		$resultat = $this->Model->datatable($query_secondaire);

		

		$data = array();
		foreach ($resultat as $key) {
			$row = array();
 

			$row[] = $key->NOM." ".$key->PRENOM;
			$row[] = $key->NOM_FOURNISSEUR;
			$row[] = $key->DESCRIPTION;
			$row[] = $key->NUMERO_COLIS;
			$row[] = $key->QUANTITE_COMMANDE;
			$row[] = $key->QUANTITE_RECUE;
			$row[] = $key->DATE_ENTREE;

			$row[] = '
			<div class="modal fade" id="desactcat'.$key->ID_STOCK_MATIERE.'" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">test</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<h6>test</h6>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			<a href="'.base_url("administration/User/".$key->ID_STOCK_MATIERE).'" class="btn btn-danger">test</a>
			</div>
			</div>
			</div>
			</div>

			<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-cogs"></i> Actions <i class="fa fa-angle-down"></i>
			</button>
			<div class="dropdown-menu">
			<a class="dropdown-item" href="'.base_url("administration/User/index_update/".$key->ID_STOCK_MATIERE).'">
			<i class="fa fa-edit"></i> Modifier
			</a>
			<a class="dropdown-item" href="#" data-toggle="modal" data-target="#desactcat'.$key->ID_STOCK_MATIERE.'">test </a> 
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

}


public function check_username_unique()
{
	$username=$this->input->post('USERNAME');
    $exists = $this->db
        ->where('USERNAME', $username)
        ->get('admin_user')
        ->num_rows();

    if ($exists > 0) {
        $this->form_validation->set_message('check_username_unique', 'Ce nom d’utilisateur existe déjà.');
        return false;
    }
    return true;
}


    public function index_update($id)
    {
     $data['title']='Utilisateur';

      $data['data']=$this->Model->getRequeteOne('SELECT * FROM `admin_user` WHERE ID_USER = '.$id.'');

      $data['profil']=$this->Model->getRequete('SELECT * FROM `config_profil` order by DESCRIPTION');

      $data['agence']=$this->Model->getRequete('SELECT * FROM `masque_agence_msi` order by DESCRIPTION');

      $this->load->view('User_Update_View',$data);

    }

    public function update()
    {
      $NOM=$this->input->post('NOM');

      $PRENOM=$this->input->post('PRENOM');

      $USERNAME=$this->input->post('USERNAME');

      $ID_USER=$this->input->post('ID_USER');

      $PROFIL_ID=$this->input->post('PROFIL_ID');

      $ID_AGENCE=$this->input->post('ID_AGENCE');

    

      $this->form_validation->set_rules('NOM', 'Nom', 'required');

      $this->form_validation->set_rules('PRENOM', 'Prenom', 'required');

      $this->form_validation->set_rules('USERNAME', 'Username', 'required');

      $this->form_validation->set_rules('PROFIL_ID', 'Profile', 'required');

      $this->form_validation->set_rules('ID_AGENCE', 'Agence', 'required');

    

       if ($this->form_validation->run() == FALSE){

        $message = "<div class='alert alert-danger'>

                                Utilisateur non modifi&eacute; de cong&eacute; non enregistr&eacute;

                                <button type='button' class='close' data-dismiss='alert'>&times;</button>

                          </div>";

        $this->session->set_flashdata(array('message'=>$message));

        $data['title']='Utilisateur';

        $data['data']=$this->Model->getRequeteOne('SELECT * FROM `admin_user` WHERE ID_USER = '.$ID_USER.'');

        $data['profil']=$this->Model->getRequete('SELECT * FROM `config_profil` order by DESCRIPTION');

        $this->load->view('User_Update_View',$data);

       }

       else{

    

        $datasuser=array(

                           'NOM'=>$NOM,

                           'PRENOM'=>$PRENOM,

                           'USERNAME'=>$USERNAME,

                           'PROFIL_ID'=>$PROFIL_ID,

                           'ID_AGENCE'=>$ID_AGENCE

                          );

                          

        $this->Model->update('admin_user',array('ID_USER'=>$ID_USER),$datasuser);  

    

        $message = "<div class='alert alert-success' id='message'>

                                Utilisateur modifi&eacute; avec succés

                                <button type='button' class='close' data-dismiss='alert'>&times;</button>

                          </div>";

        $this->session->set_flashdata(array('message'=>$message));

          redirect(base_url('administration/User'));  

       }

    }





    public function desactiver($id)
    {

      $this->Model->update('admin_user',array('ID_USER'=>$id),array('STATUS'=>0));

      $message = "<div class='alert alert-success' id='message'>

                            Utilisateur désactivé avec succés

                            <button type='button' class='close' data-dismiss='alert'>&times;</button>

                      </div>";

      $this->session->set_flashdata(array('message'=>$message));

      redirect(base_url('administration/User'));  

    }



  public function reactiver($id)
    {

      $this->Model->update('admin_user',array('ID_USER'=>$id),array('STATUS'=>1));

      $message = "<div class='alert alert-success' id='message'>

                            Utilisateur Réactivé avec succés

                            <button type='button' class='close' data-dismiss='alert'>&times;</button>

                      </div>";

      $this->session->set_flashdata(array('message'=>$message));

      redirect(base_url('administration/User'));  

    }






}
