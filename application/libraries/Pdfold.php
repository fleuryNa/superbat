<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

class Pdf extends TCPDF
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->config('tcpdf');

        $conf = $CI->config->item('tcpdf');

        $this->SetCreator($conf['creator']);
        $this->SetAuthor($conf['author']);
        $this->SetTitle($conf['title']);
        $this->SetSubject($conf['subject']);
        $this->SetKeywords($conf['keywords']);

        // En-tête
        $this->SetHeaderData('', 0, $conf['header_title'], $conf['header_string']);
        $this->setHeaderFont(Array('dejavusans', '', 9));

        // Pied de page
        $this->setFooterFont(Array('dejavusans', '', 8));
        $this->setFooterMargin($conf['margin_footer']);

        $this->SetMargins(
            $conf['margin_left'],
            $conf['margin_top'],
            $conf['margin_right']
        );

        $this->SetHeaderMargin($conf['margin_header']);
        $this->SetAutoPageBreak(TRUE, $conf['margin_footer']);

        $this->SetFont($conf['font_name'], '', $conf['font_size']);
    }
}
