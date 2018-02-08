<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** 
 * Class Pembatalan.php 
 * Handle Pembatalan Permohonan Baru PDLN Proccess
 * @package layanan 
 * @author Guntar  & Budi
 * @version 1.0.0 
 * @date_create 20/12/2016 
**/
class Pembatalan extends CI_Controller {
	public function __construct(){
		parent ::__construct();
        if (!$this->is_logged_in())
        {
            $logout = $this->ion_auth->logout();
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('', 'refresh');
        }
	}
    
    public function is_logged_in()
    {
        $user = $this->session->userdata('user_id');
        return isset($user);
    }

	public function index(){
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'v_pembatalan';
		$data['title'] 		= 'Permohonan Pembatalan';
		$data['title_page'] = 'Permohonan Pembatalan';
		$data['breadcrumb'] = 'Permohonan Pembatalan'; 
		page_render($data);
	}
	public function pembatalan_list(){
		$this->crud_ajax->init('view_monitoring_pdln','id_pdln',NULL);

        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
        $where = "";
        if ($level == LEVEL_PEMOHON) {
            $where = array('unit_pemohon'=> $this->session->user_id,'status' => '1'
                    );

        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('unit_fp'=> $this->session->user_id,'status' => '2'
                            );
        }
        $this->db->where_in('jenis_permohonan', array('40'));
		$this->crud_ajax->setExtraWhere($where);
		$list = $this->crud_ajax->get_datatables();
        $data = array();
		if(isset($_POST['start'])){
        	$no = $_POST['start'];
		}else{
			$no=0;
		}
        foreach ($list as $pdln) { 
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = $no . '.';
            if ($this->ion_auth->is_allowed(26, 'update')) {
                $row[] = '<a href="' . base_url() . 'layanan/pembatalan/view_pembatalan/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
            } else {
                $row[] = '';
            }
            $no_register = $pdln->no_register;
            $row[] = (empty($no_register)) ? '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day_dashboard($pdln->tgl_register);
            $row[] = $pdln->no_surat_fp;
            $row[] = $pdln->catatan;

            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = '<span class="label label-' . setLabelPdln($pdln->status) . '">' . setStatusPdln($pdln->status) . '</span>';

            

            $data[] = $row;
        } 
        $output = array(
                        "draw" => (isset($_POST['draw']) ? $_POST['draw']:null),
                        "recordsTotal" => $this->crud_ajax->count_all(),
                        "recordsFiltered" => $this->crud_ajax->count_filtered(),
                        "data" => $data,
        );        
        echo json_encode($output);
	}

    public function persetujuan_list(){
        $data['theme']      = 'pdln';
        $data['page']       = 'v_persetujuan';
        $data['title']      = 'Permohonan Pembatalan dari Persetujuan';
        $data['title_page'] = 'Permohonan Pembatalan dari Persetujuan';
        $data['breadcrumb'] = 'Permohonan Pembatalan dari Persetujuan'; 
        page_render($data);
    }
    public function tersetujui(){
        $this->crud_ajax->init('view_monitoring_pdln','id_pdln',NULL);

        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
        $where = "";
        if ($level == LEVEL_PEMOHON) {
            $where = array('unit_pemohon'=> $this->session->user_id
                    );

        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('unit_fp'=> $this->session->user_id
                            );
        }
        // $this->db->where_in('jenis_permohonan', array('10'));
        // $this->db->where_in('status', array(11));
        $this->db->where_in('status', array(11,13,14));
        $this->crud_ajax->setExtraWhere($where);
        $list = $this->crud_ajax->get_datatables();
        $counter = $this->crud_ajax->count_all();

        $data = array();
        if(isset($_POST['start'])){
            $no = $_POST['start'];
        }else{
            $no=0;
        }
        foreach ($list as $pdln) { 
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = $no . '.';
            $no_register = $pdln->no_register;
            $row[] = (empty($no_register)) ? '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day_dashboard($pdln->tgl_register);
            $row[] = $pdln->no_surat_fp;
            $row[] = $pdln->nama_jenis_kegiatan;

            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = '<span class="label label-' . setLabelPdln($pdln->status) . '">' . setStatusPdln($pdln->status) . '</span>';
// http://localhost/pdln/kotak_surat/modify/edit_wizard/73
            if ($this->ion_auth->is_allowed(26, 'update')) {

                $row[] = '<button type="button" id="form_renew" title="Batalkan" class="btn btn-xs red"><i class="fa fa-edit"></i>Batalkan</button>';
                // $row[] = '<a href="' . base_url() . 'layanan/pembatalan/view_pembatalan/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Batalkan</button></a>';
            } else {
                $row[] = '';
            }

            $data[] = $row;
        } 
        $output = array(
                        "draw" => (isset($_POST['draw']) ? $_POST['draw']:null),
                        "recordsTotal" => $counter,
                        "recordsFiltered" => $this->crud_ajax->count_filtered(),
                        "data" => $data,
        );        
        echo json_encode($output);
    }

    public function view_pembatalan($id_pdln) {
        $where = array('Status' => '1');
        $this->crud_ajax->init('r_template_tembusan', 'ID', null);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_temp_tembusan'] = $this->crud_ajax->get_data();
        $data['id_pdln'] = $id_pdln;
        $this->crud_ajax->init('t_approval_pdln', 'id', null);
        $where_pdln = array('id_pdln' => $id_pdln, 'is_done' => 1);
        $this->crud_ajax->setExtraWhere($where_pdln);
        $data['list_approval'] = $this->crud_ajax->get_data(); //get history approval
        $this->db->select('p.id_pdln,p.id_kegiatan,p.no_surat_usulan_pemohon,p.tgl_surat_usulan_pemohon,p.no_surat_usulan_fp,
                            p.tgl_surat_usulan_fp,p.pejabat_sign_sp,p.id_level_pejabat,p.format_tembusan,p.jenis_permohonan,p.status,p.id_level_pejabat,p.author');
        $this->db->where('p.id_pdln', $id_pdln);
        $this->db->from('m_pdln p');
        // $this->db->join("r_level_pejabat lp", "lp.id = p.id_level_pejabat");
        $data['data_pdln'] = $this->db->get()->row();
        //-----------------------------------------------------------------------------------------------------
        // Memastikan bahwa level pejabat tertentu atau pengguna tertentu yang dapat melakukan perubahan data
        //-----------------------------------------------------------------------------------------------------
        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
        $this->config->load('pdln', TRUE);
        $data_integrity = $this->config->item('data_integrity', 'pdln');
        $id_level_pejabat = $data['data_pdln']->id_level_pejabat;
        if(!empty($data['data_pdln'])
            && $data['data_pdln']->author != $id_user
            && (array_key_exists($id_level_pejabat, $data_integrity) == false || $data_integrity[$id_level_pejabat] != $level)
            //&& in_array($data['data_pdln']->status, $pdln_status) == false
            ){
            //show_error("Anda tidak memiliki akses terhadap halaman atau data di halaman ini. ", 403, "Forbidden");
        }
        //-----------------------------------------------------------------------------------------------------
        $this->db->select('m_kegiatan.ID,NamaKegiatan,StartDate,EndDate,r_negara.nmnegara,r_kota.nmkota,r_jenis_kegiatan.Nama as JenisKegiatan');
        $this->db->where('m_kegiatan.ID', $data['data_pdln']->id_kegiatan);
        $this->db->from('m_kegiatan');
        $this->db->join('r_negara', 'r_negara.id = m_kegiatan.negara');
        $this->db->join('r_kota', 'r_kota.id = m_kegiatan.tujuan');
        $this->db->join('r_jenis_kegiatan', 'r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan');
        $data['detail_kegiatan'] = $this->db->get()->row();
        $data['theme'] = 'pdln';
        $data['page'] = 'v_view_pembatalan';
        $data['title'] = 'Form Persetujuan';
        $data['title_page'] = 'Form Persetujuan';
        $data['breadcrumb'] = 'Form Persetujuan';
        page_render($data);
    }

	public function add(){
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'v_add_pembatalan';
		$data['title'] 		= 'Form Pembatalan';
		$data['title_page'] = 'Form Pembatalan';
		$data['breadcrumb'] = 'Form Pembatalan';
        $where = array('Status'=>'1');

        $this->crud_ajax->init('r_level_pejabat','id',NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['level_pejabat'] = $this->crud_ajax->get_data();

        if(!is_pemohon($this->session->userdata('user_id'))){//Jika user login adalah FP
            $this->get_list_pemohon();
            $data['list_pemohon'] = $this->get_list_pemohon();
        }
		page_render($data); 	
	}
	public function cari_surat(){
    	$response = array();    	
    	$id_surat = $this->input->post('ID');
    	$this->db->select('ID,NomorRegister');
    	$this->db->from('m_surat_keluar');
    	$this->db->where('NomorRegister',ltrim($id_surat,"0"));
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(		
                'ID'=>$row->ID,
				'status'=>TRUE
    		);
    	}else{
    		$response['status'] = FALSE;
			$response['msg'] = "Nomor Registrasi Surat Tidak Ada";
    	}
    	echo json_encode($response);
	}
	public function print_permohonan($id_surat){
		$this->db->select('m_surat_masuk.ID,m_surat_masuk.NomorRegister,m_surat_masuk.DateCreated,m_surat_masuk.JenisKegiatan,m_surat_masuk.NomorSurat,
											m_surat_masuk.TanggalSurat,m_surat_masuk.BagianPemroses,m_surat_masuk.Hal,r_institution.Nama as NamaAsalInstitusi,m_unit_kerja_institusi.Name as NamaAsalUnitKerja,m_user.Nama as Pemroses, m_surat_masuk.create_date');
		$this->db->join('m_unit_kerja_institusi', 'm_unit_kerja_institusi.ID = m_surat_masuk.IDUnitKerjaAsal','left');
		$this->db->join('r_institution', 'r_institution.ID = m_surat_masuk.IDInstansiAsal','left');
		$this->db->join('m_user', 'm_user.UserID = m_surat_masuk.UserCreated','left');
		$this->db->where('m_surat_masuk.ID',$id_surat);
        $result_data = $this->db->get('m_surat_masuk')->row();

        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        $hash_word = md5($result_data->nomor_register . $result_data->id_jenis_kegiatan . $result_data->nomor_surat . $result_data->hal . $result_data->dokumen);

        $base_path = $this->config->item('pdln_upload_path');
        $create_date = $result_data->create_date;

        $month = date('m', $create_date);
        $year = date('Y', $create_date);

        $additional_path = $year . '/' . $month . '/umum/surat_masuk/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }

        $filename = "pembatalan_permohonan_sm_{$id_surat}_{$hash_word}.pdf";
        $fullpath = "{$targetPath}{$filename}";

        if (file_exists($fullpath)){
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
		
		$this->db->select('ID,Nama');
		$jenis_kegiatan = $this->db->get('r_jenis_kegiatan')->result();
		
		$data = array(  
                        'title'=>"TANDA TERIMA BERKAS",
                        'data_resi'=>$result_data,
						'jenis_kegiatan'=>$jenis_kegiatan,
                        'm_pdf'=>$this->load->library('M_pdf'));
        $html = $this->load->view('layanan/v_print_permohonan',$data,TRUE);

        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                                    '', '', '', '',
                                    15, // margin_left
                                    15, // margin right
                                    15, // margin top
                                    10, // margin bottom
                                    18, // margin header
                                    5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html); 
        //$filename = 'resi_sm_'.$id_surat.'_'.date('d_m_Y');
        //$this->m_pdf->pdf->Output($filename.'.pdf','I');
        $this->m_pdf->pdf->Output($fullpath, 'F');
        
        send_file_to_browser($fullpath); // this function will exec die() and exit
    }
    public function get_list_pemohon(){
        $this->db->where('m_unit.Parent',$this->get_parent_id());
        $this->db->where('m_unit.Status','1');
        $this->db->where_in('m_unit.Group',array(1,2));//Pemohon
        $this->db->from('m_unit_kerja_institusi as m_unit');
        $this->db->join('m_user as mu','m_unit.ID = mu.unitkerja','left');

        $query = $this->db->get();
        if($query->num_rows() > 0)
            return $query->result();
        return FALSE;
    }
    public function list_permohonan(){
        $order = array('update_date','DESC');
        $this->crud_ajax->init('view_monitoring_pdln','id_pdln',$order);
        $where_not = array("20","30","40","50","60");
        
        $field = "jenis_permohonan";
        $this->crud_ajax->setExtraWhereNotIn($field,$where_not);
        $where = array(
                        'unit_fp'=>$this->session->user_id,
                        'status'=>11
                );
        $this->crud_ajax->setExtraWhere($where);

        $list = $this->crud_ajax->get_datatables();
        $data = array();
        if(isset($_POST['start'])){
            $no = $_POST['start'];
        }else{
            $no=0;
        }
        foreach ($list as $pdln) { 
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = $no.'.';
            $no_register = $pdln->no_register;
            $tgl_register = $pdln->tgl_register;
            $tgl_sp = $pdln->tgl_sp;
            $row[] = (empty($no_register)) ?  '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
            $row[] = (empty($tgl_register)) ?  '' : day($tgl_register);
            $row[] = $pdln->no_sp;
            $row[] = (empty($tgl_sp)) ?  '' : day($tgl_sp);
            $row[] = ucwords($pdln->nama_jenis_kegiatan);
            $row[] = '<span class="label label-info">'.ucwords($pdln->negara).'</span>';
            $row[] = '<span class="label label-'.setLabelPdln($pdln->status).'">'.setStatusPdln($pdln->status).'</span>';
            $row[] ='<button type="button" id="btn_set_surat" title="Pilih" class="btn btn-xs blue-chambray"><i class="fa fa-share-square-o"></i> </button>';
            $data[] = $row;
        }
        $output = array(
                        "draw" => (isset($_POST['draw']) ? $_POST['draw'] : NULL),
                        "recordsTotal" => $this->crud_ajax->count_filtered(),
                        "recordsFiltered" => $this->crud_ajax->count_filtered(),
                        "data" => $data,
        );        
        echo json_encode($output);
    }
    public function get_parent_id(){
        $this->db->where('UserID',$this->session->userdata('user_id'));
        $parent = $this->db->get('m_user')->row()->unitkerja;
        return $parent; //unitkerja user fp sbg parent 
    }
	public function get_data_pembatalan(){
    	$response = array();    	
    	$ID = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('t_pembatalan_permohonan');
    	$this->db->where('id_surat_keluar',$ID);
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(		
                            'id_surat_keluar'=>$row->id_surat_keluar,
							'alasan_pembatalan'=>$row->alasan_pembatalan, 
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
	}
    public function get_data_permohonan_exist(){
        $id_pdln = $this->input->post('id_pdln');
        $this->crud_ajax->init('m_pdln','id_pdln',NULL);
        $row = $this->crud_ajax->get_by_id($id_pdln);

        if(!empty($row)){
            $data = array();            
            $data['surat_usulan_pemohon'] = $row->path_file_sp_pemohon;
            $data['no_surat_usulan_pemohon'] = $row->no_surat_usulan_pemohon;
            $data['tgl_surat_usulan_pemohon'] = $row->tgl_surat_usulan_pemohon;
            $data['level_pejabat'] = $row->id_level_pejabat;
            $data['no_surat_usulan_fp'] = $row->no_surat_usulan_fp;
            $data['tgl_surat_usulan_fp'] = (empty($row->tgl_surat_usulan_fp)) ? '' : date("d-m-Y",$row->tgl_surat_usulan_fp);
            $data['surat_usulan_fp'] = $row->path_file_sp_fp;
            $data['pejabat_sign_permohonan'] = $row->pejabat_sign_sp;
            $data['user_pemohon'] = $row->unit_pemohon;            
            $data['status'] = TRUE;
        }
        echo json_encode($data);
    }
	public function pembatalan_save(){
        $this->_pembatalan_validate();
        echo json_encode(array("status" => TRUE));
    }
	private function _pembatalan_validate(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		
		$id_pdln = $this->input->post('id_pdln');
		$alasan_pembatalan = $this->input->post('alasan_pembatalan');
				
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	
				$this->crud_ajax->init('t_pembatalan_permohonan','id_surat_keluar',null);
				$data_save_pembatalan = array(
											'id_surat_keluar' => $ID,
											'alasan_pembatalan' => $alasan_pembatalan,
											'date_created' => date('Y-m-d H:i:s'),
											'user_created' => $this->session->user_id
										);
				$insert_id_u = $this->crud_ajax->save($data_save_pembatalan);
				
				$this->crud_ajax->init('m_surat_keluar','ID',null);
				$update_surat = array(
											'JenisPermohonan' => '40',
										);
				
				$where = array('ID'=>$ID);
	        	$this->crud_ajax->update($where,$update_surat);
        }
	}
    public function is_pemohon(){
        $result = array();        
        if(!(is_pemohon($this->session->user_id))){  //IF Focal Point
            $result['id_user_fp'] = $this->session->user_id;
            $result['id_user_pemohon'] = '';
            $result['status'] =  FALSE;
        }else{
            $result['id_user_fp'] = get_focal_point_by($this->session->user_id);
            $result['id_user_pemohon'] = $this->session->user_id;
            $result['status'] = TRUE;
        }
        echo json_encode($result);
    }
}