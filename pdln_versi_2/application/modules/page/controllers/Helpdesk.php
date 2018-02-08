<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helpdesk extends CI_Controller {
	function __construct(){
		parent ::__construct();
	}	
	
	public function index(){
			$data['theme'] 		= 'pdln';
			$data['page'] 		= 'v_helpdesk';
			$data['title'] 		= 'Helpdesk PDLN';
			$data['title_page'] = 'Helpdesk PDLN';
			$data['breadcrumb'] = 'Helpdesk';
			page_render($data);
	}

	
	public function kegiatan_request_list(){
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;

		$this->crud_ajax->init('t_tiket','id',array('update_date'=>' asc'));

		$where = array('author'=> $this->session->userdata('user_id'));
		$this->crud_ajax->setExtraWhere($where);
        // $this->db->where('author', $this->session->userdata('user_id'));
		$list = $this->crud_ajax->get_datatables();
		$data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $kegiatan) {
            $row = array();
            if ($kegiatan->help > 1) {
            	$str_jns = 'Konteks';
            }else {
            	$str_jns = 'Teknis';
            }
            if ($kegiatan->status > 0) {
            	# code...
            }
			$row[] = $kegiatan->id;
            $row[] = ++$no;
            $row[] = '<a href="' . base_url() . 'page/helpdesk/ask/' . $kegiatan->id . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
			$row[] = $kegiatan->judul;
            $row[] = $str_jns;
            if($kegiatan->status > 0) {$status = "Dijawab"; $label = "primary"; } else { $status = "Belum Jawab" ; $label = "danger";}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			// $row[] = //$kegiatan->status;
			$row[] = date("d/m/Y", strtotime($kegiatan->create_date)) ;
			$row[] = date("d/m/Y", strtotime($kegiatan->update_date));
            // $row[] = $kegiatan->level;
            
			$data[] = $row;
        }
        $output = array(
                        "draw" => (isset($_POST['draw'])?$_POST['draw']:null),
                        "recordsTotal" => $this->crud_ajax->count_all(),
                        "recordsFiltered" => $this->crud_ajax->count_filtered(),
                        "data" => $data,
        );
        echo json_encode($output);
	}
	public function count_help()
	{
		$this->db->select('id');
        $this->db->where('status', 1);
        $this->db->where('is_read_front', 0);
        $this->db->from('t_tiket');
        $detail_tiket =  $this->db->get()->result();

		$data['status'] = TRUE;
		$data['data'] = $detail_tiket;
        echo json_encode($data);

	}
	public function ask($id_tiket){

		$header_tiket  = $this->db->get_where('t_tiket', array('id' => $id_tiket))->row();

    	$this->crud_ajax->init('t_tiket','id',null);
		$upd_header = array(
								'is_read_front' => 1
    	 					);
	    $where_kegiatan = array('id'=>$id_tiket);
	    $this->crud_ajax->update($where_kegiatan,$upd_header);

		$this->db->select('steatment,create_date,author,m_level.NamaLevel as NamaLevel');
        $this->db->where('id_t_tiket', $id_tiket);
        $this->db->from('t_tiket_helpdesk');
        $this->db->join('m_level', "m_level.LevelID = t_tiket_helpdesk.level");
        $detail_tiket =  $this->db->get()->result();
        $data['id_tiket'] = (empty($id_tiket)) ? NULL : $id_tiket;
        $data['no_tiket'] = str_pad($id_tiket, 8, '0', STR_PAD_LEFT);;

        $data['header_tiket'] = (empty($header_tiket)) ? NULL : $header_tiket;
		$data['detail_tiket'] =  $detail_tiket;
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'page/detail_helpdesk';
		$data['title'] 		= 'Helpdesk PDLN';
		$data['title_page'] = 'Helpdesk PDLN';
		$data['breadcrumb'] = 'Helpdesk';
		page_render($data);
	}

	public function add_ask()
	{
		$data['status'] = TRUE;


		$comenting = $this->input->post('comenting');
		$JenisKegiatan = $this->input->post('JenisKegiatan');
		$judul = $this->input->post('judul');


		$level  = $this->db->get_where('m_user', array('UserID' => $this->session->userdata('user_id')))->row()->level;
		$level  = $this->db->get_where('m_user', array('UserID' => $this->session->userdata('user_id')))->row()->level;

    	$this->crud_ajax->init('t_tiket','id',null);
    	$data_save_judul = array(
								'author' => $this->session->userdata('user_id'),
								'judul' => $judul,
								'is_read_front' => 1,
								'is_read_backend' => 0,
    							'help' => $JenisKegiatan,
								'status' => 0,//0 ask , 1 answer
								'create_date' => date('Y-m-d H:i:s'),
								'level' => $level,
								'update_date' => date("Y-m-d H:i:s")
    	 					);
		$insert_id_judul = $this->crud_ajax->save($data_save_judul);
		if ($insert_id_judul > 0) {
			$data['id_tiket'] = $insert_id_judul;
	    	$this->crud_ajax->init('t_tiket_helpdesk','id',null);
	    	$detail = array(
	    							'id_t_tiket' => $insert_id_judul,
									'create_date' => date("Y-m-d H:i:s"),
									'steatment' => $comenting,
									'level' => $level,
									'author' => $this->session->userdata('user_id'),
									'task ' => 0 //0 ask , 1 answr
	    	 					);
			$insert_id_detail = $this->crud_ajax->save($detail);
			if ($insert_id_detail < 0) {
				$data['status'] = FALSE;
			}
		}
        echo json_encode($data);
		
	}



	public function add_koment()
	{
		$data['status'] = TRUE;

		$comenting = $this->input->post('comenting');
		$id_tiket = $this->input->post('id_tiket');
		$level  = $this->db->get_where('m_user', array('UserID' => $this->session->userdata('user_id')))->row()->level;

    	$this->crud_ajax->init('t_tiket','id',null);
		$upd_header = array(
								'update_date' => date("Y-m-d H:i:s"),
								'status' => 0,
								'is_read_backend' => 0
    	 					);
	    $where_kegiatan = array('id'=>$id_tiket);
	    $affected_row_u = $this->crud_ajax->update($where_kegiatan,$upd_header);


    	$this->crud_ajax->init('t_tiket_helpdesk','id',null);
    	$data_save_kegiatan = array(
    							'id_t_tiket' => $id_tiket,
								'create_date' => date("Y-m-d H:i:s"),
								'steatment' => $comenting,
								'level' => $level,
								'author' => $this->session->userdata('user_id'),
								'task ' => 1
    	 					);
		$insert_id_u = $this->crud_ajax->save($data_save_kegiatan);
		if ($insert_id_u < 1) {
			$data['status'] = FALSE;
		}
        echo json_encode($data);
	}



}
