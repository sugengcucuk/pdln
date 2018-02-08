<?php

#-----------------------------------------------------------------------------------------------------
function page_render($assign_data=array(), $page_merge=''){

	$CI =& get_instance();

	#Default Var------------------------------------------------
	if(isset($assign_data['module']))
	$module = $assign_data['module'];
	else
	$module = '';
	
	if(isset($assign_data['title']))
	$assign_data['title'] = $assign_data['title'];
	else
	$assign_data['title'] = $CI->config->item('default_title');
	
	if(isset($assign_data['desc']))
	$default_data['desc'] = $assign_data['desc'];
	else
	$default_data['desc'] = $assign_data['title'];
	
	if(!isset($assign_data['theme']))
	$default_data['theme'] = 'siktln';
	
	$data = array_merge($default_data, $assign_data);
	
	if(!isset($data['module']))
	$data['module'] = $module;
	
	if (!$CI->ion_auth->logged_in())
	{
			// redirect them to the login page
			redirect('auth', 'refresh'); 
	}else{
		$CI->load->view('main', $data);
	}
}

?>
