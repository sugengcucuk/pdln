<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manual extends CI_Controller {
	function __construct(){
		parent ::__construct();
	}	
	
	public function index(){
		$data['theme'] 		= 'pdln';
		$data['page'] 		= 'v_manual';
		$data['title'] 		= 'Manual PDLN';
		$data['title_page'] = 'Manual PDLN';
		$data['breadcrumb'] = 'Manual PDLN';
		page_render($data);
	}
}
