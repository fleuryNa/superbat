<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {


	public function index()
	{
		 if (!empty($this->session->userdata('SUPERBAT_ID_USER'))) {
        $message='<div class="alert alert-success text-center" id="message">Connexion bien etablie!<br> Les menus sont à gauche</div>';
        $this->session->set_flashdata($datas);     
        $this->session->set_flashdata(array('message'=>$message));    
        redirect(base_url('Acceuil'));
        } else {
        	if (!empty($this->session->flashdata('message'))) {
        		$message= $this->session->flashdata('message');
        	}
        	else{
        		$message= NULL;
        	}
        	// if()
                             

            $datas['message'] = $message;
            $datas['title'] = 'Login';
            $this->load->view('login_view', $datas);

         }
	}



 public function do_login() {
         
        $login = $this->input->post('USERNAME');
        $password = $this->input->post('PASSWORD');
       
        $user= $this->Model->getOne('admin_user',array('USERNAME'=>$login,'STATUS'=>1));
        

        if (!empty($user)) {
          
            if ($user['PASSWORD'] == md5($password))

             {

              $droits = $this->Model->getRequete("Select ID_DROIT FROM config_profil_droit WHERE PROFIL_ID = ".$user['PROFIL_ID']."");
             $listdroi[] =NULL;
             foreach ($droits as $key) {
                 $listdroi[] .= $key['ID_DROIT'];
             }
           

              $session = array(
                              'SUPERBAT_ID_USER' => $user['ID_USER'],
                              'SUPERBAT_NOM' => $user['NOM'],
                              'SUPERBAT_PRENOM' => $user['PRENOM'],
                              'SUPERBAT_USERNAME' => $user['USERNAME'],
                              'SUPERBAT_PROFIL_ID' => $user['PROFIL_ID'],
                              'ID_EMPLOYE' => $user['ID_EMPLOYE'],
                              'SUPERBAT_DROIT'=>$listdroi,
                               );
                
                 $message = "<div class='alert alert-success' id='messages'> Bonne Connexion</div>";
                 $this->session->set_userdata($session);
                 
                 redirect(base_url());
            
            }

             else
                $message = "<div class='alert alert-danger' id='messages'> Le nom d'utilisateur ou/et mot de passe incorect(s) !</div>";
            $this->session->set_flashdata(array('message'=>$message));
            redirect(base_url());
              
        }
       
                $message = "<div class='alert alert-danger' id='messages'> Le nom d'utilisateur ou/et mot de passe incorect(s) !</div>";
                $this->session->set_flashdata(array('message'=>$message));
                redirect(base_url());     

    }

    public function do_logout(){

                $session = array(
                              'SUPERBAT_ID_USER' => NULL,
                              'SUPERBAT_NOM' => NULL,
                              'SUPERBAT_PRENOM' => NULL,
                              'SUPERBAT_USERNAME' => NULL,
                            );                   
            $this->session->set_userdata($session);
            redirect(base_url('Login'));
        }



	public function forgotPassword(){
		$this->load->view('mot_de_passe_oublier');

	}
}
