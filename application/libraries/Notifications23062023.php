<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notifications
{
	protected $CI;

	public function __construct()
	{
	    $this->CI = & get_instance();
      $this->CI->load->library('email');
      $this->CI->load->model('Model');
	}


    public function newsend_mail($emailTo = array(),$subjet,$cc_emails=NULL,$message,$attach=NULL)
   {
        $config['protocol'] = 'sendmail';
        $config['smtp_host'] = '31.220.53.55';
        // https://jagsolution.mooo.com/
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['priority'] = '1';
        $config['wordwrap'] = TRUE;
        $this->CI->email->initialize($config);

        // Load email library and passing configured values to email library 
        $this->CI->load->library('email', $config);
        $this->CI->email->set_newline("\r\n");

        $this->CI->email->from('noreply@socarvie.bi', 'Gestion des documents');
        $this->CI->email->to($emailTo);
    
          if (!empty($cc_emails)) {
          foreach ($cc_emails as $key => $value) {
          $this->CI->email->cc($value);
          }
          }
         
        $this->CI->email->subject($subjet);
        $this->CI->email->message($message);
        
        if(!empty($attach))
          {
            $this->email->attach($attach);
         }

        if (!$this->CI->email->send()) {
            show_error($this->CI->email->print_debugger());
        } else
            echo $this->CI->email->print_debugger();
   }


  

   public function generate_UIID($taille)
   {
     $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $Hash=NULL; 
        for($x=1;$x<=$taille;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        }

        return $Hash; 
   }

    public function generate_password($taille)
   {
     $Caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVXWYZ0123456789,.@{-_/#'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $Hash=NULL; 
        for($x=1;$x<=$taille;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        }
        return $Hash; 
   }

   public function generateQrcode($data,$name)
   {
      if(!is_dir('uploads/QRCODE')) //create the folder if it does not already exists
       {
          mkdir('uploads/QRCODE',0777,TRUE);
       }

      $Ciqrcode = new Ciqrcode();
      $params['data'] = $data;
      $params['level'] = 'H';
      $params['size'] = 10;
      $params['overwrite'] = TRUE;
      $params['savename'] = FCPATH . 'uploads/QRCODE/' . $name . '.png';
      $Ciqrcode->generate($params);
   }

}

?>
