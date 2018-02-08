<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Controller {
	function __construct(){
		parent ::__construct();
	}

	public function index(){
		$this->kegiatan();

	}

	/*Start Master Kegiatan Management*/
	public function kegiatan(){
		$where = array('Status'=>'1');
		$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
        $this->crud_ajax->setExtraWhere($where);
		$data['jenis_kegiatan']		= $this->crud_ajax->get_data();
		$this->crud_ajax->init('r_kota','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['kota']		= $this->crud_ajax->get_data();
        $this->crud_ajax->init('r_negara','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['negara']		= $this->crud_ajax->get_data();
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'kegiatan/v_kegiatan';
		$data['title'] 		= 'Master Kegiatan';
		$data['title_page'] = 'Manajemen Data Kegiatan';
		$data['breadcrumb'] = 'Kegiatan';
		page_render($data);

	}

	public function kegiatan_list(){
		$this->crud_ajax->init('m_kegiatan','ID',array('m_kegiatan.ID'=>'asc'));
		$this->crud_ajax->set_select_field('m_kegiatan.ID,m_kegiatan.NamaKegiatan,r_jenis_kegiatan.Nama as NamaJenisKegiatan,r_negara.nmnegara,
											r_kota.nmkota,m_kegiatan.StartDate,m_kegiatan.EndDate,
											m_kegiatan.Status,r_kota.nmkota');
		$join = array(	'r_jenis_kegiatan'=>array('r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan','left'),
						'r_kota'=>array('r_kota.id = m_kegiatan.Tujuan','left'),
						'r_negara'=>array('r_negara.id = m_kegiatan.Negara','left')
					);

		$where = array('m_kegiatan.is_request'=>null);
		$this->crud_ajax->setExtraWhere($where);
		$this->crud_ajax->setJoinField($join);
		$list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $kegiatan) {
            $row = array();
			$row[] = $kegiatan->ID;
            $row[] = ++$no;
			$row[] = $kegiatan->NamaKegiatan;
            $row[] = $kegiatan->NamaJenisKegiatan;
			$row[] = $kegiatan->nmnegara;
            $row[] = $kegiatan->nmkota;
			$row[] = date("d/m/Y", strtotime($kegiatan->StartDate)) ." - " .date("d/m/Y", strtotime($kegiatan->EndDate));
			if($kegiatan->Status === "1") {$status = "Aktif"; $label = "primary"; } else { $status = "Tidak Aktif" ; $label = "danger";}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(27,'update'))
			{
				$row[] ='<button type="button" id="edit_kegiatan" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i> </button>';
                    // <button type="button" id="delete_kegiatan" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> </button>
			}else{
				$row[] ='';
			}
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

	public function get_data_kegiatan(){
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('m_kegiatan');
    	$this->db->where('ID',$id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(
                            'NamaKegiatan'=>$row->NamaKegiatan,
							'JenisKegiatan'=>$row->JenisKegiatan,
							'Negara'=>$row->Negara,
							'Tujuan'=>$row->Tujuan,
							'StartDate'=>date("d-m-Y", strtotime($row->StartDate)),
							'Penyelenggara'=>$row->Penyelenggara,
							'EndDate'=>date("d-m-Y", strtotime($row->EndDate)),
							'is_active'=>$row->Status,
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
	}
	public function kegiatan_request(){
		$where = array('Status'=>'1');
		$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
        $this->crud_ajax->setExtraWhere($where);
		$data['jenis_kegiatan']		= $this->crud_ajax->get_data();
		$this->crud_ajax->init('r_kota','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['kota']		= $this->crud_ajax->get_data();
        $this->crud_ajax->init('r_negara','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['negara']		= $this->crud_ajax->get_data();
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'kegiatan/v_request_keg';
		$data['title'] 		= 'Master Kegiatan';
		$data['title_page'] = 'Request Data Kegiatan';
		$data['breadcrumb'] = 'Kegiatan';
		page_render($data);

	}

	public function approv_request($id_keg)
	{


		$keg_req = $this->db->get_where('m_kegiatan', array('ID' => $id_keg))->row();
        $data['keg_req'] = $keg_req;// $this->crud_ajax->get_data();

		$keg_master =  $this->db->get_where('m_kegiatan', array('ID' => $keg_req->id_rever_to))->row();


		$this->db->select('r_jenis_kegiatan.nama as JenisKegiatan,r_negara.nmnegara as nmnegara, r_kota.nmkota as nmkota,m_kegiatan.NamaKegiatan,m_kegiatan.Penyelenggara,m_kegiatan.StartDate,m_kegiatan.EndDate , m_kegiatan.is_request');
        $this->db->where('m_kegiatan.ID', $keg_req->id_rever_to);
        $this->db->from('m_kegiatan');
        $this->db->join("r_jenis_kegiatan", "r_jenis_kegiatan.id = m_kegiatan.JenisKegiatan");
        $this->db->join("r_kota", "r_kota.id = m_kegiatan.Tujuan");
        $this->db->join("r_negara", "r_negara.id = m_kegiatan.Negara");
        $data['keg_master'] =  $this->db->get()->row();

		$where = array('Status'=>'1');
		$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
        $this->crud_ajax->setExtraWhere($where);
		$data['jenis_kegiatan']		= $this->crud_ajax->get_data();
		$this->crud_ajax->init('r_kota','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['kota']		= $this->crud_ajax->get_data();
        $this->crud_ajax->init('r_negara','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['negara']		= $this->crud_ajax->get_data();

		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'kegiatan/request_keg';
		$data['title'] 		= 'Master Kegiatan';
		$data['title_page'] = 'Request Data Kegiatan';
		$data['breadcrumb'] = 'Kegiatan';
		$data['id_keg'] = $id_keg;

		page_render($data);

	}

	public function count_req(){
		$this->crud_ajax->init('m_kegiatan','ID',array('m_kegiatan.ID'=>'asc'));

		$this->db->where_in('m_kegiatan.is_request',array(1));

		$list = $this->crud_ajax->get_datatables();
		$data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $kegiatan) {
            $row = array();
			$row[] = $kegiatan->ID;
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
	

	public function kegiatan_request_list(){
		$this->crud_ajax->init('m_kegiatan','ID',array('m_kegiatan.ID'=>'asc'));


        // $this->db->where('m_kegiatan.ModifiedBy', $this->session->userdata('user_id'));
        $where = array('m_kegiatan.ModifiedBy' => $this->session->userdata('user_id'), 'm_kegiatan.is_request <' => 2);
        // $this->db->where_in('m_kegiatan.is_request',array(0,1,2));

		$this->crud_ajax->set_select_field('m_kegiatan.ID,m_kegiatan.NamaKegiatan,r_jenis_kegiatan.Nama as NamaJenisKegiatan,r_negara.nmnegara,
											r_kota.nmkota,m_kegiatan.StartDate,m_kegiatan.EndDate,
											m_kegiatan.Status,r_kota.nmkota,m_kegiatan.is_request');
		$join = array(	'r_jenis_kegiatan'=>array('r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan','left'),
						'r_kota'=>array('r_kota.id = m_kegiatan.Tujuan','left'),
						'r_negara'=>array('r_negara.id = m_kegiatan.Negara','left')
					);
        $this->crud_ajax->setExtraWhere($where);
		$this->crud_ajax->setJoinField($join);
		$list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $kegiatan) {
            $row = array();
			$row[] = $kegiatan->ID;
            $row[] = ++$no;
			$row[] = $kegiatan->NamaKegiatan;
            $row[] = $kegiatan->NamaJenisKegiatan;
			$row[] = $kegiatan->nmnegara;
            $row[] = $kegiatan->nmkota;
			$row[] = date("d/m/Y", strtotime($kegiatan->StartDate)) ." - " .date("d/m/Y", strtotime($kegiatan->EndDate));
			
			if ($kegiatan->is_request === '1') {
				$status = "Konfirmasi Admin";
				 $label = "primary";
			}elseif ($kegiatan->is_request === '0') {
				$status = "Perubahan Disetujui" ;
				$label = "info";
			}elseif ($kegiatan->is_request === '2'){
				$status = "Ditolak" ;
				$label = "danger";
			}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(27,'update'))
			{
				$row[] = '<a href="' . base_url() . 'master/kegiatan/approv_request/' . $kegiatan->ID . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
				//$row[] ='<button type="button" id="edit_kegiatan" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i> </button>';
			}else{
				$row[] ='';
			}
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
	public function kegiatan_request_update(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		$NamaKegiatan = $this->input->post('NamaKegiatan');
		$JenisKegiatan = $this->input->post('JenisKegiatan');
		$Negara = $this->input->post('Negara');
		$Tujuan = $this->input->post('Tujuan');
		$StartDate = strtotime(str_replace('/', '-',$this->input->post('StartDate')));
		$EndDate   = strtotime(str_replace('/', '-',$this->input->post('EndDate')));
		$Status = $this->input->post('opt_status');
		$Penyelenggara = $this->input->post('Penyelenggara');
        if($NamaKegiatan === '')
        {
            $data['inputerror'][] = 'NamaKegiatan';
            $data['error_string'][] = 'Nama Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($JenisKegiatan === '0')
        {
            $data['inputerror'][] = 'JenisKegiatan';
            $data['error_string'][] = 'Jenis Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	if($this->input->post('method') === "tambah"){
	        	//insert to table m_kegiatan
				if(!$this->isExist_NamaKegiatan($NamaKegiatan)){
					$data['inputerror'][] = 'NamaKegiatan';
					$data['error_string'][] = 'Maaf Nama Kegiatan sudah digunakan';
					$data['status'] = FALSE;
					echo json_encode($data);
                    exit();
				}else{
					$this->crud_ajax->init('m_kegiatan','ID',null);
					$data_save_kegiatan = array(
											'NamaKegiatan' => $NamaKegiatan,
											'JenisKegiatan' => $JenisKegiatan,
											'Negara' => $Negara,
											'Tujuan' => $Tujuan,
											'StartDate' => date("Y-m-d", $StartDate),
											'EndDate' => date("Y-m-d", $EndDate),
											'Status' => $Status,
											'Penyelenggara' => $Penyelenggara,
											'CreatedBy'=>$this->session->userdata('user_id')
										);
					$insert_id_u = $this->crud_ajax->save($data_save_kegiatan);
				}
	        }else if($this->input->post('method') === "ubah"){
	        	$ID = $this->input->post('ID');
	        	//update to table m_kegiatan
	        	$this->crud_ajax->init('m_kegiatan','ID',null);
	        	$data_save_kegiatan = array(
	        							'NamaKegiatan' => $NamaKegiatan,
											'JenisKegiatan' => $JenisKegiatan,
											'Negara' => $Negara,
											'Tujuan' => $Tujuan,
											'StartDate' => date("Y-m-d", $StartDate),
											'EndDate' => date("Y-m-d", $EndDate),
											'Status' => $Status ? $Status:1,
											'Penyelenggara' => $Penyelenggara,
											'ModifiedBy' => $this->session->userdata('user_id'),
											'is_request' => 1
											// 'id_rever_to' => $ID
	        	 					);
	        	$where_kegiatan = array('ID'=>$ID);
	        	$affected_row_u = $this->crud_ajax->update($where_kegiatan,$data_save_kegiatan);
                if($affected_row_u < 1){
                    $data['status'] = FALSE;
                    $data['msg'] = "Gagal Update Data Kegiatan"; 
                    echo json_encode($data);
                    exit();
                }
	        }
        }
        echo json_encode(array("status" => TRUE));
    }

	public function kegiatan_save(){
        $this->kegiatan_validate();
        echo json_encode(array("status" => TRUE));
    }

	 /**
	 * @method private _validate handle validation data users
	 * @return json output status on form or modal
	 */
	private function kegiatan_validate(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		$NamaKegiatan = $this->input->post('NamaKegiatan');
		$JenisKegiatan = $this->input->post('JenisKegiatan');
		$Negara = $this->input->post('Negara');
		$Tujuan = $this->input->post('Tujuan');
		$StartDate = strtotime(str_replace('/', '-',$this->input->post('StartDate')));
		$EndDate   = strtotime(str_replace('/', '-',$this->input->post('EndDate')));
		$Status = $this->input->post('opt_status');
		$Penyelenggara = $this->input->post('Penyelenggara');
        if($NamaKegiatan === '')
        {
            $data['inputerror'][] = 'NamaKegiatan';
            $data['error_string'][] = 'Nama Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($JenisKegiatan === '0')
        {
            $data['inputerror'][] = 'JenisKegiatan';
            $data['error_string'][] = 'Jenis Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	if($this->input->post('method') === "tambah"){
	        	//insert to table m_kegiatan
				if(!$this->isExist_NamaKegiatan($NamaKegiatan)){
					$data['inputerror'][] = 'NamaKegiatan';
					$data['error_string'][] = 'Maaf Nama Kegiatan sudah digunakan';
					$data['status'] = FALSE;
					echo json_encode($data);
                    exit();
				}else{
					$this->crud_ajax->init('m_kegiatan','ID',null);
					$data_save_kegiatan = array(
											'NamaKegiatan' => $NamaKegiatan,
											'JenisKegiatan' => $JenisKegiatan,
											'Negara' => $Negara,
											'Tujuan' => $Tujuan,
											'StartDate' => date("Y-m-d", $StartDate),
											'EndDate' => date("Y-m-d", $EndDate),
											'Status' => $Status,
											'Penyelenggara' => $Penyelenggara,
											'CreatedBy'=>$this->session->userdata('user_id')
										);
					$insert_id_u = $this->crud_ajax->save($data_save_kegiatan);
				}
	        }else if($this->input->post('method') === "ubah"){
	        	$ID = $this->input->post('ID');
	        	//update to table m_kegiatan
	        	$this->crud_ajax->init('m_kegiatan','ID',null);
	        	$data_save_kegiatan = array(
	        							'NamaKegiatan' => $NamaKegiatan,
											'JenisKegiatan' => $JenisKegiatan,
											'Negara' => $Negara,
											'Tujuan' => $Tujuan,
											'StartDate' => date("Y-m-d", $StartDate),
											'EndDate' => date("Y-m-d", $EndDate),
											'Status' => 0,
											'Penyelenggara' => $Penyelenggara,
											'ModifiedBy' => $this->session->userdata('user_id'),
											'is_request' => 1,
											'id_rever_to' => $ID
	        	 					);
	        	// $where_kegiatan = array('ID'=>$ID);
				$insert_id_u = $this->crud_ajax->save($data_save_kegiatan);

	        	// $affected_row_u = $this->crud_ajax->update($where_kegiatan,$data_save_kegiatan);
                // if($affected_row_u < 1){
                //     $data['status'] = FALSE;
                //     $data['msg'] = "Gagal Update Data Kegiatan"; 
                //     echo json_encode($data);
                //     exit();
                // }
	        }
        }
    }

	private function isExist_NamaKegiatan($NamaKegiatan){
    	$this->db->where('NamaKegiatan',$NamaKegiatan);
    	$query = $this->db->get('m_kegiatan');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	public function kegiatan_delete(){
		$ID = $this->input->post('ID');
		$response = array();
		$this->crud_ajax->init('m_kegiatan','ID',null);
		$response['success'] = $this->crud_ajax->delete_by_id($ID);
		echo json_encode($response);
	}

	public function get_kota() {
		$id_negara = $this->input->post('id_negara');
		$where = array('id_negara'=>$id_negara);
		$this->crud_ajax->init('r_kota','id',null);
		$this->crud_ajax->setExtraWhere($where);
		$query = $this->crud_ajax->get_data();
		if(count($query) > 0) {
			foreach($query as $row) {
				echo '<option value="'.$row->id.'">'.trim($row->nmkota).'</option>';
			}
		}else {
			echo '<option value="">--Kota Tidak Tersedia--</option>';
		}
	}

	/*End Master Kegiatan Management*/

	/*Start Master Jenis Kegiatan Management*/
	public function jenis_kegiatan(){
		$where = array('Status'=>'1');
		$this->crud_ajax->init('r_kategori_kegiatan','ID',null);
		$data['kategori']		= $this->crud_ajax->get_data();
		$this->crud_ajax->init('r_jenisdoc_kegiatan','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['dokumen_kegiatan']		= $this->crud_ajax->get_data();
		$this->crud_ajax->init('r_jenisdoc_pemohon','id',null);
		$this->crud_ajax->setExtraWhere($where);
        $data['dokumen_pemohon']		= $this->crud_ajax->get_data();
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'kegiatan/v_jenis_kegiatan';
		$data['title'] 		= 'Master Jenis Kegiatan';
		$data['title_page'] = 'Manajemen Data Jenis Kegiatan';
		$data['breadcrumb'] = 'Jenis Kegiatan';
		page_render($data);

	}

	public function jenis_kegiatan_list(){
		$this->crud_ajax->init('r_jenis_kegiatan','ID',array('r_jenis_kegiatan.ID'=>'asc'));
		$this->crud_ajax->set_select_field('r_jenis_kegiatan.ID,r_jenis_kegiatan.Status,r_jenis_kegiatan.Kodifikasi,r_jenis_kegiatan.Nama,r_kategori_kegiatan.Nama as NamaKategori,
											r_subkategori_kegiatan.Nama as NamaSubKategori');
		$join = array(	'r_kategori_kegiatan'=>array('r_kategori_kegiatan.ID = r_jenis_kegiatan.Kategori','left'),
						'r_subkategori_kegiatan'=>array('r_subkategori_kegiatan.ID = r_jenis_kegiatan.SubKategori','left')
					);
		$this->crud_ajax->setJoinField($join);
		$list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $jenis_kegiatan) {
            $row = array();
			$row[] = $jenis_kegiatan->ID;
            $row[] = ++$no;
			$row[] = $jenis_kegiatan->Nama;
            $row[] = $jenis_kegiatan->NamaKategori;
			$row[] = $jenis_kegiatan->NamaSubKategori;
			$row[] = $jenis_kegiatan->Kodifikasi;
			if($jenis_kegiatan->Status === "1") {$status = "Aktif"; $label = "primary"; } else { $status = "Tidak Aktif" ; $label = "danger";}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(27,'update'))
			{
				$row[] ='<button type="button" id="edit_jenis_kegiatan" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i> </button>';
                    // <button type="button" id="delete_jenis_kegiatan" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> </button>
			}else{
				$row[] ='';
			}
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

	public function get_data_jenis_kegiatan(){
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('r_jenis_kegiatan');
    	$this->db->where('ID',$id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(
                            'Nama'=>$row->Nama,
							'Kategori'=>$row->Kategori,
							'SubKategori'=>$row->SubKategori,
							'Kodifikasi'=>$row->Kodifikasi,
							'is_active'=>$row->Status,
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
	}

	public function jenis_kegiatan_save(){
        $this->jenis_kegiatan_validate();
        echo json_encode(array("status" => TRUE));
    }

	 /**
	 * @method private _validate handle validation data users
	 * @return json output status on form or modal
	 */
	private function jenis_kegiatan_validate(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		$Nama = $this->input->post('Nama');
		$Kategori = $this->input->post('Kategori');
		$SubKategori = $this->input->post('SubKategori');
		$Kodifikasi = $this->input->post('Kodifikasi');
		$Status = $this->input->post('opt_status');
        if($Nama === '')
        {
            $data['inputerror'][] = 'Nama';
            $data['error_string'][] = 'Nama Jenis Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	if($this->input->post('method') === "tambah"){
	        	//insert to table r_jenis_kegiatan
				if(!$this->isExist_NamaJenis_Kegiatan($Nama)){
					$data['inputerror'][] = 'Nama';
					$data['error_string'][] = 'Maaf Nama Jenis Kegiatan sudah digunakan';
					$data['status'] = FALSE;
					echo json_encode($data);
                    exit();
				}else{
					$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
					$data_save_jenis_kegiatan = array(
											'Nama' => $Nama,
											'Kategori' => $Kategori,
											'SubKategori' => $SubKategori,
											'Kodifikasi' => $Kodifikasi,
											'Status' => $Status
										);
					$insert_id_u = $this->crud_ajax->save($data_save_jenis_kegiatan);
					$doc_pemohon=$this->input->post('doc_pemohon');
					if(!empty($doc_pemohon)){
						// make array container for input batch
						$insert_doc_pemohon = array(); //insert new selected
						foreach($doc_pemohon as $dp) {
							//$tempArray;
							$required = $this->input->post('pemohon'.$dp);
							if ($required==1) {$required=1;}else{$required=0;}
							if($this->isExist_DocPemohon($dp,$insert_id_u)){
								$tempArray = array(
									'IDJenisKegiatan' => $insert_id_u,
									'IDJenisDokumen' => $dp,
									'Required' => $required
								);
								array_push($insert_doc_pemohon, $tempArray);
							}
						}
						if(!empty($insert_doc_pemohon)){
							$this->db->insert_batch('t_doc_req_pemohon', $insert_doc_pemohon);
						}
					}
					$doc_kegiatan=$this->input->post('doc_kegiatan');
					if(!empty($doc_kegiatan)){
						// make array container for input batch
						$insert_doc_kegiatan = array(); //insert new selected
						foreach($doc_kegiatan as $dp) {
							//$tempArray;
							$required = $this->input->post('kegiatan'.$dp);
							if ($required==1) {$required=1;}else{$required=0;}
							if($this->isExist_DocPemohon($dp,$insert_id_u)){
								$tempArray = array(
									'IDJenisKegiatan' => $insert_id_u,
									'IDJenisDokumen' => $dp,
									'Required' => $required
								);
								array_push($insert_doc_kegiatan, $tempArray);
							}
						}
						if(!empty($insert_doc_kegiatan)){
							$this->db->insert_batch('t_doc_req_kegiatan', $insert_doc_kegiatan);
						}
					}
				}
	        }else if($this->input->post('method') === "ubah"){
	        	$ID = $this->input->post('ID');
	        	//update to table r_jenis_kegiatan
	        	$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
	        	$data_save_jenis_kegiatan = array(
	        							'Nama' => $Nama,
										'Kategori' => $Kategori,
										'SubKategori' => $SubKategori,
										'Kodifikasi' => $Kodifikasi,
										'Status' => $Status
	        	 					);
	        	$where_jenis_kegiatan = array('ID'=>$ID);
	        	$affected_row_u = $this->crud_ajax->update($where_jenis_kegiatan,$data_save_jenis_kegiatan);
                /*if($affected_row_u < 1){
                    $data['status'] = FALSE;
                    $data['msg'] = "Gagal Update Data Jenis_Kegiatan";
                    echo json_encode($data);
                    exit();
                }*/
					//Checklist Dokumen Pemohon
					$this->db->select('*');
					$this->db->from('t_doc_req_pemohon');
					$this->db->where('IDJenisKegiatan',$ID);
					$query = $this->db->get();
					$current_doc_pemohon = array();
					foreach($query->result() as $row)
					{
						$current_doc_pemohon[] = $row->IDJenisDokumen; // add each user id to the array
					}
					$doc_pemohon=$this->input->post('doc_pemohon');
					if(!empty($doc_pemohon)){
						$temp=array_diff($current_doc_pemohon,$doc_pemohon); //cek yang dihapus dari select box
						$intersect=array_intersect($current_doc_pemohon,$doc_pemohon); //cek yang tetap di select box
						//print_r($temp);exit;
						foreach($temp as $dp) {
							$this->db->where('IDJenisKegiatan', $ID);
							$this->db->where('IDJenisDokumen', $dp);
							$this->db->delete('t_doc_req_pemohon');
						}
						// make array container for input batch
						$insert_doc_pemohon = array(); //insert new selected
						foreach($doc_pemohon as $dp) {
							//$tempArray;
							$required = $this->input->post('pemohon'.$dp);
							if ($required==1) {$required=1;}else{$required=0;}
							if($this->isExist_DocPemohon($dp,$ID)){
								$tempArray = array(
									'IDJenisKegiatan' => $ID,
									'IDJenisDokumen' => $dp,
									'Required' => $required
								);
								array_push($insert_doc_pemohon, $tempArray);
							}
						}
						if(!empty($insert_doc_pemohon)){
							$this->db->insert_batch('t_doc_req_pemohon', $insert_doc_pemohon);
						}
						// make array container for update batch
						$update_doc_pemohon = array(); //update existing
						foreach($intersect as $dp) {
							//$tempArray;
							$required = $this->input->post('pemohon'.$dp);
							if ($required==1) {$required=1;}else{$required=0;}
							$tempArray = array(
								'IDJenisKegiatan' => $ID,
								'IDJenisDokumen' => $dp,
								'Required' => $required
							);
							array_push($update_doc_pemohon, $tempArray);
						}
						if(!empty($update_doc_pemohon)){
							$this->db->where('IDJenisKegiatan',$ID);
							$this->db->update_batch('t_doc_req_pemohon', $update_doc_pemohon,'IDJenisDokumen');
						}
					}
					//Checklist Dokumen Kegiatan
					$this->db->select('*');
					$this->db->from('t_doc_req_kegiatan');
					$this->db->where('IDJenisKegiatan',$ID);
					$query = $this->db->get();
					$current_doc_kegiatan = array();
					foreach($query->result() as $row)
					{
						$current_doc_kegiatan[] = $row->IDJenisDokumen; // add each user id to the array
					}
					$doc_kegiatan=$this->input->post('doc_kegiatan');
					if(!empty($doc_kegiatan)){
						$temp=array_diff($current_doc_kegiatan,$doc_kegiatan); //cek region yang dihapus dari select box
						$intersect=array_intersect($current_doc_kegiatan,$doc_kegiatan); //cek yang tetap di select box
						foreach($temp as $dk) {
							$this->db->where('IDJenisKegiatan', $ID);
							$this->db->where('IDJenisDokumen', $dk);
							$this->db->delete('t_doc_req_kegiatan');
						}
						// make array container for input batch
						$insert_doc_kegiatan = array();
						foreach($doc_kegiatan as $dk) {
							//$tempArray;
							$required = $this->input->post('kegiatan'.$dk);
							if ($required==1) {$required=1;}else{$required=0;}

							if($this->isExist_DocKegiatan($dk,$ID)){
								$tempArray = array(
									'IDJenisKegiatan' => $ID,
									'IDJenisDokumen' => $dk,
									'Required' => $required
								);
								array_push($insert_doc_kegiatan, $tempArray);
							}
						}
						if(!empty($insert_doc_kegiatan)){
							$this->db->insert_batch('t_doc_req_kegiatan', $insert_doc_kegiatan);
						}
						// make array container for update batch
						$update_doc_kegiatan = array(); //update existing
						foreach($intersect as $dk) {
							//$tempArray;
							$required = $this->input->post('kegiatan'.$dk);
							if ($required==1) {$required=1;}else{$required=0;}
							$tempArray = array(
								'IDJenisKegiatan' => $ID,
								'IDJenisDokumen' => $dk,
								'Required' => $required
							);
							array_push($update_doc_kegiatan, $tempArray);
						}
						if(!empty($update_doc_kegiatan)){
							$this->db->where('IDJenisKegiatan',$ID);
							$this->db->update_batch('t_doc_req_kegiatan', $update_doc_kegiatan,'IDJenisDokumen');
						}

					}


	        }
        }
    }

	public function isExist_DocPemohon($DocID,$KegiatanID){
    	$this->db->where('IDJenisDokumen',$DocID);
		$this->db->where('IDJenisKegiatan',$KegiatanID);
    	$query = $this->db->get('t_doc_req_pemohon');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	public function isExist_DocKegiatan($DocID,$KegiatanID){
    	$this->db->where('IDJenisDokumen',$DocID);
		$this->db->where('IDJenisKegiatan',$KegiatanID);
    	$query = $this->db->get('t_doc_req_kegiatan');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	private function isExist_NamaJenis_Kegiatan($NamaJenis_Kegiatan){
    	$this->db->where('Nama',$NamaJenis_Kegiatan);
    	$query = $this->db->get('r_jenis_kegiatan');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	public function jenis_kegiatan_delete(){
		$ID = $this->input->post('ID');
		$response = array();
		$this->crud_ajax->init('r_jenis_kegiatan','ID',null);
		$response['success'] = $this->crud_ajax->delete_by_id($ID);
		echo json_encode($response);
	}

	public function get_sub_kategori() {
		$id_kategori = $this->input->post('id_kategori');
		$where = array('KategoriID'=>$id_kategori);
		$this->crud_ajax->init('r_subkategori_kegiatan','ID',null);
		$this->crud_ajax->setExtraWhere($where);
		$query = $this->crud_ajax->get_data();
		if(count($query) > 0) {
			foreach($query as $row) {
				echo '<option value="'.$row->ID.'">'.trim($row->Nama).'</option>';
			}
		}else {
			echo '<option value="">--Kota Tidak Tersedia--</option>';
		}
	}

	/*End Master Jenis Kegiatan Management*/

	/*Start Master Dokumen Kegiatan Management*/
	public function dokumen_kegiatan(){
		$data['theme'] 		= 'ktln_admin';
        $data['page'] 		= 'kegiatan/v_dokumen_kegiatan';
		$data['title'] 		= 'Master Dokumen Kegiatan';
		$data['title_page'] = 'Manajemen Data Dokumen Kegiatan';
		$data['breadcrumb'] = 'Dokumen Kegiatan';
		page_render($data);

	}

	public function dokumen_kegiatan_list(){
		$this->crud_ajax->init('r_jenisdoc_kegiatan','ID',array('r_jenisdoc_kegiatan.ID'=>'asc'));
		$list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $dokumen_kegiatan) {
            $row = array();
			$row[] = $dokumen_kegiatan->ID;
            $row[] = ++$no;
			$row[] = $dokumen_kegiatan->Nama;
            $row[] = $dokumen_kegiatan->Description;
			if($dokumen_kegiatan->Status === "1") {$status = "Aktif"; $label = "primary"; } else { $status = "Tidak Aktif" ; $label = "danger";}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(19,'update'))
			{
				$row[] ='<button type="button" id="edit_dokumen_kegiatan" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i> </button>';
                    // <button type="button" id="delete_dokumen_kegiatan" title="Hapus" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> </button>
			}else{
				$row[] ='';
			}
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

	public function get_data_dokumen_kegiatan(){
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('r_jenisdoc_kegiatan');
    	$this->db->where('ID',$id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(
                            'Nama'=>$row->Nama,
							'Description'=>$row->Description,
							'is_active'=>$row->Status,
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
	}

	public function dokumen_kegiatan_save(){
        $this->dokumen_kegiatan_validate();
        echo json_encode(array("status" => TRUE));
    }

	 /**
	 * @method private _validate handle validation data users
	 * @return json output status on form or modal
	 */
	private function dokumen_kegiatan_validate(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		$Nama = $this->input->post('Nama');
		$Description = $this->input->post('Description');
        if($Nama === '')
        {
            $data['inputerror'][] = 'Nama';
            $data['error_string'][] = 'Nama Dokumen Kegiatan tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	if($this->input->post('method') === "tambah"){
	        	//insert to table r_jenisdoc_kegiatan
				if(!$this->isExist_NamaDokumen_Kegiatan($Nama)){
					$data['inputerror'][] = 'Nama';
					$data['error_string'][] = 'Maaf Nama Dokumen Kegiatan sudah digunakan';
					$data['status'] = FALSE;
					echo json_encode($data);
                    exit();
				}else{
					$this->crud_ajax->init('r_jenisdoc_kegiatan','ID',null);
					$data_save_dokumen_kegiatan = array(
											'Nama' => $Nama,
											'Description' => $Description,
											'Status'=>$this->input->post('opt_status')
										);
					$insert_id_u = $this->crud_ajax->save($data_save_dokumen_kegiatan);
				}
	        }else if($this->input->post('method') === "ubah"){
	        	$ID = $this->input->post('ID');
	        	//update to table r_jenisdoc_kegiatan
	        	$this->crud_ajax->init('r_jenisdoc_kegiatan','ID',null);
	        	$data_save_dokumen_kegiatan = array(
	        							'Nama' => $Nama,
										'Description' => $Description,
										'Status'=>$this->input->post('opt_status')
	        	 					);
	        	$where_dokumen_kegiatan = array('ID'=>$ID);
	        	$affected_row_u = $this->crud_ajax->update($where_dokumen_kegiatan,$data_save_dokumen_kegiatan);
                if($affected_row_u < 1){
                    $data['status'] = FALSE;
                    $data['msg'] = "Gagal Update Data Dokumen_Kegiatan";
                    echo json_encode($data);
                    exit();
                }
	        }
        }
    }

	private function isExist_NamaDokumen_Kegiatan($NamaDokumen_Kegiatan){
    	$this->db->where('Nama',$NamaDokumen_Kegiatan);
    	$query = $this->db->get('r_jenisdoc_kegiatan');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	public function dokumen_kegiatan_delete(){
		$ID = $this->input->post('ID');
		$response = array();
		$this->crud_ajax->init('r_jenisdoc_kegiatan','ID',null);
		$response['success'] = $this->crud_ajax->delete_by_id($ID);
		echo json_encode($response);
	}

	public function get_doc_req_pemohon(){
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('t_doc_req_pemohon');
    	$this->db->where('IDJenisKegiatan',$id);
    	$query = $this->db->get();
		foreach($query->result() as $row)
		{	$array = array();
			$array['IDJenisKegiatan'] = $row->IDJenisKegiatan; // add each user id to the array
			$array['IDJenisDokumen'] = $row->IDJenisDokumen;
			$array['Required'] = $row->Required;
			array_push($response, $array);
		}
    	echo json_encode($response);
	}

	public function get_doc_req_kegiatan(){
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('t_doc_req_kegiatan');
    	$this->db->where('IDJenisKegiatan',$id);
    	$query = $this->db->get();
		foreach($query->result() as $row)
		{	$array = array();
			$array['IDJenisKegiatan'] = $row->IDJenisKegiatan; // add each user id to the array
			$array['IDJenisDokumen'] = $row->IDJenisDokumen;
			$array['Required'] = $row->Required;
			array_push($response, $array);
		}
    	echo json_encode($response);
	}
	/*End Master Dokumen Kegiatan Management*/
}