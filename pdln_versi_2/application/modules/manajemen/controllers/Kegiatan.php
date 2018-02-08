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
        $data['page'] 		= 'manajemen/v_kegiatan';
		$data['title'] 		= 'Master Kegiatan';
		$data['title_page'] = 'Manajemen Data Kegiatan';
		$data['breadcrumb'] = 'Kegiatan';   
		page_render($data);
		
	}
	
	public function kegiatan_list(){		
		$this->crud_ajax->init('m_kegiatan','m_kegiatan.ID',array('m_kegiatan.ID'=>'asc'));
		$this->crud_ajax->set_select_field('m_kegiatan.ID,m_kegiatan.NamaKegiatan,r_jenis_kegiatan.Nama as NamaJenisKegiatan,r_negara.nmnegara,
											r_kota.nmkota,m_kegiatan.StartDate,m_kegiatan.EndDate,
											m_kegiatan.Status,r_kota.nmkota');
		$join = array(	'r_jenis_kegiatan'=>array('r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan','left'),
						'r_kota'=>array('r_kota.id = m_kegiatan.Tujuan','left'),
						'r_negara'=>array('r_negara.id = m_kegiatan.Negara','left')
					);

		$this->crud_ajax->setJoinField($join);
		$list = $this->crud_ajax->get_datatables();

        $data = array();
		if(isset($_POST['start'])){
        $no = $_POST['start'];
		}else{ 
			$no=0;
		}   

        foreach ($list as $kegiatan) { 
            $no++;
            $row = array();
			$row[] = $kegiatan->ID;  
            $row[] = $no.'.'; 
			$row[] = ucwords($kegiatan->NamaKegiatan);
            $row[] = ucwords($kegiatan->NamaJenisKegiatan);
			$row[] = ucwords($kegiatan->nmnegara);
            $row[] = ucwords($kegiatan->nmkota);
			$row[] = day_dashboard(strtotime($kegiatan->StartDate)) ." s.d. " .day_dashboard(strtotime($kegiatan->EndDate));
			if($kegiatan->Status === "1") {$status = "Aktif"; $label = "primary"; } else { $status = "Tidak Aktif" ; $label = "danger";}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(28,'update'))
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
											'Status' => $Status,
											'Penyelenggara' => $Penyelenggara
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
		if(!$this->crud_ajax->delete_by_id($ID))
			$response['success'] = FALSE;	  
		else
			$response['success'] = TRUE;		
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
	
}