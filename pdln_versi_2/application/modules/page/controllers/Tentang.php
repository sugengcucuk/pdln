<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tentang extends CI_Controller {
	function __construct(){
		parent ::__construct();
	}	
	
	public function index(){
			$data['theme'] 		= 'pdln';
			$data['page'] 		= 'v_tentang';
			$data['title'] 		= 'Tentang PDLN';
			$data['title_page'] = 'Tentang PDLN';
			$data['breadcrumb'] = 'Tentang PDLN';
			page_render($data); 
	}
}
