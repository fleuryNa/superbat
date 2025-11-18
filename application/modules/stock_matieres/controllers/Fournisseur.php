<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends MY_Controller {

	public function index()
	{
		$this->load->view('Fournisseur_List_View');
	}
}
