<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate extends CI_Controller {

    function __construct() {
        parent ::__construct();
        $this->config->load('pdln', TRUE);
    }

    function qrcode(){
        $id=$this->input->get('id');
        $url_esign=$this->config->item('url_esign', 'pdln');
        $this->load->library('ciqrcode');
        header("Content-Type: image/png");
        $params['data'] = base_url().$url_esign.$id;
        $this->ciqrcode->generate($params);
    }
}