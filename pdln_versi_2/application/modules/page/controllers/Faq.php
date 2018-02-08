<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
	function __construct(){
		parent ::__construct();
	}	
	
	public function index(){
			$data['theme'] 		= 'pdln';
			$data['page'] 		= 'v_faq';
			$data['title'] 		= 'Frequently Asked Questions';
			$data['title_page'] = 'Frequently Asked Questions';
			$data['breadcrumb'] = 'FAQ';
			page_render($data);
	}
}
