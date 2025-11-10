<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acceuil extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->Is_Connected();
    }

    public function Is_Connected()
       {
       if (empty($this->session->userdata('SOCAR_ID_USER')))
        {
         redirect(base_url('Login/'));
        }
       }



    public function index()
    {
      $data = array();

      $this->load->view('Acceuil_View',$data);
    }




}
?>