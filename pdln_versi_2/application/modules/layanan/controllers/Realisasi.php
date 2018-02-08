<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi extends CI_Controller {
	function __construct(){
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
        $data['page'] 		= 'v_realisasi';
		$data['title'] 		= 'Realisasi'; 
		$data['title_page'] = 'Realisasi';
		$data['breadcrumb'] = 'Realisasi'; 
		page_render($data);
	}
	public function realisasi_list(){
        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
		$this->crud_ajax->init('view_monitoring_pdln','id_pdln',NULL);
        $join = array(
                        't_laporan_pdln'=>array('view_monitoring_pdln.id_pdln = t_laporan_pdln.id_pdln','left')
                    );
        $where_not = array("40","50");
        
        $field = "jenis_permohonan";
        $this->crud_ajax->setExtraWhereNotIn($field,$where_not);
        $this->crud_ajax->setJoinField($join);
        $this->crud_ajax->set_select_field('view_monitoring_pdln.id_pdln,view_monitoring_pdln.unit_fp,t_laporan_pdln.date_created,view_monitoring_pdln.no_register,view_monitoring_pdln.no_surat_fp,jenis_permohonan,status,is_final_print');
        if ($level == LEVEL_PEMOHON) {
             $where = array(
                        'unit_pemohon'=>$this->session->user_id,
                        'status'=>11
                );
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array(
                        'unit_fp'=>$this->session->user_id,
                        'status'=>11
                    );

        }
       
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
            $tgl_upload = $pdln->date_created;
            $row[] = (empty($pdln->is_final_print)) ? '<a class="btn btn-circle btn-xs purple-sharp" title="Laporkan" href="'.base_url().'layanan/realisasi/pelaporan/'.$pdln->id_pdln.'"><i class="fa fa-arrow-circle-up"></i> Laporkan</a>':'<a class="btn btn-circle btn-xs blue-sharp" title="View" href="'.base_url().'layanan/realisasi/pelaporan/'.$pdln->id_pdln.'"><i class="fa fa-arrow-circle-right"></i> View </a>';
            $row[] = (empty($no_register)) ?  '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
			$row[] = (empty($tgl_upload)) ? '' : day_dashboard(strtotime($tgl_upload)).' '.date("H:i:s",strtotime($tgl_upload));
			$row[] = $pdln->no_surat_fp;
            $row[] = (empty($pdln->is_final_print)) ? '<span class="btn btn-xs btn-circle red-sunglo">Belum Dilaporkan</span>' : '<span class="btn btn-xs btn-circle green-sharp">Sudah Dilaporkan</span>';
			$row[] = '<span class="label label-'.setLabel($pdln->jenis_permohonan).'">'.setJenisPermohonan($pdln->jenis_permohonan).'</span>';			
			$row[] = '<span class="label label-'.setLabelPdln($pdln->status).'">'.setStatusPdln($pdln->status).'</span>';			

            // <button class="btn btn-circle btn-xs purple-sharp" title="Sudah Dilaporkan" href="'.base_url().'layanan/realisasi/add" disabled><i class="fa fa-arrow-circle-up"></i> </button>';
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
	public function add(){		
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'v_add_realisasi';
		$data['title'] 		= 'Laporan Penugasan';
		$data['title_page'] = 'Laporan Penugasan';
		$data['breadcrumb'] = 'Laporan Penugasan';
		page_render($data);
	}

	public function cari_surat(){
		$id_surat = $this->input->post('ID');
    	$response = array();
    	if(strlen($id_surat)<8 || strlen($id_surat)>8){ 
			$response['status'] = FALSE; 
			$response['msg'] = "Masukkan 8 angka nomor registrasi";
			echo json_encode($response);exit; 
		}
		
    	$this->db->select('id_pdln,no_register');
    	$this->db->from('m_pdln');
    	$this->db->where('no_register',ltrim($id_surat,"0"));
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(		
                'ID'=>$row->id_pdln,
				'status'=>TRUE
    		);
    	}else{
    		$response['status'] = FALSE;
			$response['msg'] = "Nomor Registrasi Surat Tidak Ada";
    	}
    	echo json_encode($response);
	}
    public function get_biaya_estimasi($id_pdln,$id_kategori_biaya){

        if ($id_kategori_biaya > 0) {
            $this->db->select('m_pemohon.nama as nama , t_pembiayaan_campuran.biaya_apbn as estimasi_biaya');
            $this->db->where('t_log_peserta.id_pdln',$id_pdln);  
            $this->db->from('t_log_peserta');
            $this->db->join('m_pemohon','t_log_peserta.id_pemohon = m_pemohon.id_pemohon','left');
            $this->db->join('t_ref_pembiayaan_campuran','t_log_peserta.id_biaya = t_ref_pembiayaan_campuran.id_det_dana_campuran','left');
            $this->db->join('t_pembiayaan_campuran','t_ref_pembiayaan_campuran.id_dana_campuran = t_pembiayaan_campuran.id_dana_campuran','left');
        }else{
            $this->db->select('m_pemohon.nama as nama , t_ref_pembiayaan_tunggal.biaya as estimasi_biaya ');
            $this->db->where('t_log_peserta.id_pdln',$id_pdln);  
            $this->db->from('t_log_peserta');
            $this->db->join('m_pemohon','t_log_peserta.id_pemohon = m_pemohon.id_pemohon','left');
            $this->db->join('t_ref_pembiayaan_tunggal','t_ref_pembiayaan_tunggal.id_log_dana_tunggal = t_log_peserta.id_biaya','left');
        }        
        $peserta = $this->db->get()->result();
        $response['status'] = TRUE;
        $response['estimasi'] =$peserta;
        $response['msg'] = "succes";

        echo json_encode($response);


    }

    public function pelaporan($id_pdln){ 
        $this->db->where('id_pdln',$id_pdln);
        $row = $this->db->get('m_pdln')->row();
        $is_exist = $this->db->get_where('t_laporan_pdln',array('id_pdln'=>$id_pdln));
        if($is_exist->num_rows() > 0){

            $this->db->select('m_pdln.no_register as no_registri,m_pdln.is_final_print as is_final_print, r_kota.nmkota as nmkota ,t_realisasi.* ');
            $this->db->from('t_realisasi');
            $this->db->where('m_pdln.id_pdln',$id_pdln);

            // $this->db->where('m_pdln',array("id_pdln"=>$id_pdln));
            $this->db->join('r_kota','t_realisasi.id_kota = r_kota.id','left');
            $this->db->join('m_pdln','m_pdln.id_pdln = t_realisasi.id_pdln','left');
            $result = $this->db->get();
            if($result->num_rows() > 0){
                $result =$result->result();
            }
            $data['theme']      = 'pdln';
            $data['m_pdln']       = $result;
            $data['no_registri']       = str_pad($row->no_register, 8, '0', STR_PAD_LEFT);

            $data['page']       = 'v_pelaporan_edit';


            $this->db->where('id_pdln',$id_pdln);
            $row = $this->db->get('t_laporan_pdln')->row();
            $data['dokumen']       = $row->dokumen;
            $data['title']      = 'Laporan Penugasan';
            $data['title_page'] = 'Laporan Penugasan';
            $data['breadcrumb'] = 'Laporan Penugasan';
            page_render($data);

        }else{

            $this->db->select('m_pdln.id_kegiatan , m_pdln.id_pdln as id_pdln ,m_pdln.no_register, m_kegiatan.NamaKegiatan as NamaKegiatan ,m_kegiatan.StartDate as StartDate , m_kegiatan.EndDate as EndDate , r_kota.nmkota as nmkota, r_kota.id as id_kota ,');
            $this->db->where('m_pdln.id_pdln',$id_pdln);  
            $this->db->from('m_pdln');
            $this->db->join('m_kegiatan','m_pdln.id_kegiatan = m_kegiatan.ID','left');
            $this->db->join('r_jenis_kegiatan','m_kegiatan.JenisKegiatan = r_jenis_kegiatan.ID','left');
            $this->db->join('r_kota','m_kegiatan.Tujuan = r_kota.id','left');

            $result = $this->db->get()->result();
            $this->db->select('m_pemohon.nama as nama ,id_kategori_biaya ,t_log_peserta.id_pemohon');
            $this->db->where('t_log_peserta.id_pdln',$id_pdln);  
            $this->db->from('t_log_peserta');
            $this->db->join('m_pemohon','t_log_peserta.id_pemohon = m_pemohon.id_pemohon','left');
            $peserta = $this->db->get()->result();


            $data['theme']      = 'pdln';
            $data['m_pdln']       = $result;
            $data['peserta']       = $peserta;
            $data['no_registri']       = str_pad($row->no_register, 8, '0', STR_PAD_LEFT);
            $data['page']       = 'v_pelaporan';
            $data['title']      = 'Laporan Penugasan';
            $data['title_page'] = 'Laporan Penugasan';
            $data['breadcrumb'] = 'Laporan Penugasan';
            page_render($data);

        }
        
    }


    public function reported(){

        $user_id = $this->session->user_id;

        $this->db->where('UserID',$user_id);
        $unitkerja = $this->db->get('m_user')->row()->unitkerja;

        $id_pdln = $this->input->post('id_pdln');
        $kegiatan_name = $this->input->post('kegiatan_name');
        $tujuan_kegiatan = $this->input->post('tujuan_kegiatan');
        $start_date = $this->input->post('StartDate');
        $end_date = $this->input->post('EndDate');
        $materi_kegiatan = $this->input->post('materi_kegiatan');
        $rekom = $this->input->post('rekom');
        $tindak_lanjut = $this->input->post('tindak_lanjut');
        $id_kegiatan = $this->input->post('id_kegiatan');

        $id_kota = $this->input->post('id_kota');
        $counter_peserta = $this->input->post('counter_peserta');



        // t_laporan_pdln
        $is_exist = $this->db->get_where('t_realisasi',array('id_pdln'=>$id_pdln));
        if($is_exist->num_rows() > 0){
            for ($i=0; $i < $counter_peserta; $i++) {
                    $this->crud_ajax->init('t_realisasi','id',NULL);
                    $id_realisasi = $this->input->post('id_realisasi_'.$i);

                    $id_kategori_biaya = $this->input->post('id_kategori_biaya_'.$i);
                    $biaya_estimasi = $this->input->post('biaya_estimasi_'.$i);
                    $biaya_realisasi = $this->input->post('biaya_realisasi_'.$i);
                    $id_peserta = $this->input->post('id_peserta_'.$i);
                    $id_nama_peserta = $this->input->post('id_nama_peserta_'.$i);

                    $data_biaya_reported = array(
                                            'id_pdln'=>(empty($id_pdln)) ? NULL : $id_pdln,
                                            'id_kegiatan'=>(empty($id_kegiatan)) ? NULL : $id_kegiatan,
                                            'id_unitkerja' => $unitkerja,
                                            'id_peserta'=>(empty($id_peserta)) ? NULL : $id_peserta,
                                            'id_user'=> $user_id,
                                            'nama_kegiatan'=>(empty($kegiatan_name)) ? NULL : $kegiatan_name,
                                            'nama_peserta'=>(empty($id_nama_peserta)) ? NULL : $id_nama_peserta,
                                            'id_kota'=> $id_kota,
                                            'id_kategori_biaya'=>(int)$id_kategori_biaya,
                                            'start_date'=>$start_date,
                                            'end_date'=>$end_date,
                                            'tujuan_kegiatan'=>(empty($tujuan_kegiatan)) ? NULL : $tujuan_kegiatan,
                                            'materi_kegiatan'=>(empty($materi_kegiatan)) ? NULL : $materi_kegiatan,
                                            'tindak_lanjut'=>(empty($tindak_lanjut)) ? NULL : $tindak_lanjut,
                                            'dampak_recom'=>(empty($rekom)) ? NULL : $rekom,
                                            'estimasi_awal'=>(empty($biaya_estimasi)) ? NULL : convert_to_number($biaya_estimasi),
                                            'realisasi_biaya' =>(empty($biaya_realisasi)) ? NULL : convert_to_number($biaya_realisasi),
                                            'create_date'=> date("Y-m-d H:i:s")

                        );
                    $where = array(
                            'id'=>$id_realisasi
                    );
                    $response['status'] = true;
                    $response['msg'] = "Succes";
                    $array[] = $data_biaya_reported;

                    $insert_realisasi = $this->crud_ajax->update($where,$data_biaya_reported);
                }
                $where_t_laporan_id_pdln = array(
                            'id_pdln'=>$id_pdln
                    );
                $this->crud_ajax->init('t_laporan_pdln','id',NULL);
                $data_report_pdln =  array(
                                        'is_done_report' =>1,
                                        'id_pejabat_tindak_lanjut' =>LEVEL_KARO,
                                        'user_created'=>$user_id
                                        );
                $insert_realisasi = $this->crud_ajax->update($where_t_laporan_id_pdln,$data_report_pdln);
            // $this->crud_ajax->update($where,$data_report_pdln);
        }else{
            $array = array();
            if ($counter_peserta > 0) {
                $is_exis_dok  = $this->db->get_where('t_laporan_pdln',array('id_pdln'=>$id_pdln));
                if ($is_exis_dok > 0) {
                    $insert_realisasi = 0;
                }else{
                    $this->crud_ajax->init('t_laporan_pdln','id',NULL);
                    $data_report_pdln =  array(
                                            'id_pdln'=>$id_pdln,
                                            'is_done_report' =>1,
                                            'id_pejabat_tindak_lanjut' =>LEVEL_KARO,
                                            'user_created'=>$user_id
                                            );
                    $insert_realisasi = $this->crud_ajax->save($data_report_pdln);
                }                
                if ($insert_realisasi == 0) {                
                    for ($i=0; $i < $counter_peserta; $i++) {

                        $this->crud_ajax->init('t_realisasi','id',NULL);

                        $id_kategori_biaya = $this->input->post('id_kategori_biaya_'.$i);
                        $biaya_estimasi = $this->input->post('biaya_estimasi_'.$i);
                        $biaya_realisasi = $this->input->post('biaya_realisasi_'.$i);
                        $id_peserta = $this->input->post('id_peserta_'.$i);
                        $id_nama_peserta = $this->input->post('id_nama_peserta_'.$i);

                        $data_biaya_reported = array(
                                                'id_pdln'=>(empty($id_pdln)) ? NULL : $id_pdln,
                                                'id_kegiatan'=>(empty($id_kegiatan)) ? NULL : $id_kegiatan,
                                                'id_unitkerja' => $unitkerja,
                                                'id_peserta'=>(empty($id_peserta)) ? NULL : $id_peserta,
                                                'id_user'=> $user_id,
                                                'nama_kegiatan'=>(empty($kegiatan_name)) ? NULL : $kegiatan_name,
                                                'nama_peserta'=>(empty($id_nama_peserta)) ? NULL : $id_nama_peserta,
                                                'id_kota'=> $id_kota,
                                                'id_kategori_biaya'=>(int)$id_kategori_biaya,
                                                'start_date'=>$start_date,
                                                'end_date'=>$end_date,
                                                'tujuan_kegiatan'=>(empty($tujuan_kegiatan)) ? NULL : $tujuan_kegiatan,
                                                'materi_kegiatan'=>(empty($materi_kegiatan)) ? NULL : $materi_kegiatan,
                                                'tindak_lanjut'=>(empty($tindak_lanjut)) ? NULL : $tindak_lanjut,
                                                'dampak_recom'=>(empty($rekom)) ? NULL : $rekom,
                                                'estimasi_awal'=>(empty($biaya_estimasi)) ? NULL : convert_to_number($biaya_estimasi),
                                                'realisasi_biaya' =>(empty($biaya_realisasi)) ? NULL : convert_to_number($biaya_realisasi),
                                                'create_date'=> date("Y-m-d H:i:s")

                            );
                        $where = array(
                                'id_pdln'=>$id_pdln
                        );
                        $array[] = $data_biaya_reported;

                        // $insert_realisasi = $this->crud_ajax->update($where,$data_biaya_reported);
                        $insert_realisasi = $this->crud_ajax->save($data_biaya_reported);
                        if(!empty($insert_realisasi)){
                            $response['status'] = true;
                            $response['msg'] = "Succes";

                        }else {
                            $response['status'] = false;
                            $response['msg'] = "Gagal Menyimpan Laporan , Silakan mengulang !!!";
                            echo json_encode($response);exit; 
                        }


                    }
                }
            }
        }
        $response['id_pdln'] = $id_pdln;
        $response['counter_peserta'] = $counter_peserta;
        echo json_encode($response);
    }
    public function cari_doc_lapor(){
        $id_surat = $this->input->post('ID');
        $response = array();
        if(strlen($id_surat)<8 || strlen($id_surat)>8){ 
            $response['status'] = FALSE; 
            $response['msg'] = "Masukkan 8 angka nomor registrasi";
            echo json_encode($response);exit; 
        }
        
        $this->db->select('id_pdln,no_register');
        $this->db->from('m_pdln');
        $this->db->where('no_register',ltrim($id_surat,"0"));
        
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $row = $query->row();
            $response = array(      
                'ID'=>$row->id_pdln,
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
											m_surat_masuk.TanggalSurat,m_surat_masuk.BagianPemroses,m_surat_masuk.Hal,r_institution.Nama as NamaAsalInstitusi,m_unit_kerja_institusi.Name as NamaAsalUnitKerja,m_user.Nama as Pemroses,m_surat_masuk.create_date');
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

        $filename = "realisasi_permohonan_sm_{$id_surat}_{$hash_word}.pdf";
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
		
		$data = array(  
                        'title'=>"TANDA TERIMA BERKAS",
                        'data_resi'=>$result_data,
						'jenis_kegiatan'=>$jenis_kegiatan,
                        'm_pdf'=>$this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('layanan/v_print_lampiran',$data,TRUE);

		
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
        
		$this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                                    '', '', '', '',
                                    15, // margin_left
                                    15, // margin right
                                    15, // margin top
                                    10, // margin bottom
                                    18, // margin header
                                    5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html_lampiran); 
         
		//$filename = 'resi_sm_'.$id_surat.'_'.date('d_m_Y');
        //$this->m_pdf->pdf->Output($filename.'.pdf','I');
        $this->m_pdf->pdf->Output($fullpath, 'F');
                
        send_file_to_browser($fullpath); // this function will exec die() and exit
    }
	public function upload_laporan(){
		$this->do_upload_laporan($this->input->post('id_pdln'),$this->input->post('biaya')); 
	}
	public function do_upload_laporan($id_surat,$biaya){
        $id_user = $this->session->user_id;
		$temporary = explode(".", $_FILES["file_laporan_kegiatan"]["name"]);
		$file_extension = end($temporary);
		$new_name = $id_surat.'_'.$this->session->user_id.'_laporan_pdln.'.$file_extension;
		
		$this->db->where('id_pdln',$id_surat);
		// Berdasarkan tanggal first submit / create date insert row
		$create_date = $this->db->get('m_pdln')->row()->create_date;

		if(upload_pdln("laporan",$id_surat,$new_name,'file_laporan_kegiatan',date("Y-m-d",$create_date)))
		{
			$this->crud_ajax->init('t_laporan_pdln','id_pdln',null);
			$data_save_laporan = array(
				'id_pdln' => $id_surat,
				'dokumen' => $new_name,
                'date_created' => date('Y-m-d H:i:s'),
                'user_created' => $this->session->userdata('user_id'),
                'biaya'=>$biaya,
                'is_done_report' =>1
			);            

			// ini fungsinya LAPOR
			if(!$this->isExist_Laporan($id_surat)){
				$where = array('id_pdln'=>$id_surat);
				$this->crud_ajax->update($where,$data_save_laporan);
                $this->crud_ajax->init('m_pdln','id_pdln',null);
                $data_save_pdln = array(
                    'update_date' => strtotime(date("Y-m-d"))
                    // 'is_final_print'=>1
                );
                $this->crud_ajax->update($where,$data_save_pdln);
			}else{
				$this->crud_ajax->save($data_save_laporan);
			}
			
			$response['status'] = TRUE;
			$response['msg'] = 'Dokumen laporan realisasi berhasil diupload';
			
			echo json_encode($response);
			exit;
		}else{ 
			$response['status'] = FALSE;
			$response['msg'] = 'Terdapat kesalahan ketika upload dokumen, silahkan ulangi kembali';
			echo json_encode($response);
			exit;
		}
    }
    public function go_reported()
    {

        $response['status'] = TRUE;
        $response['msg'] = 'Dokumen laporan realisasi berhasil diupload';
        $id_surat = $this->input->post('id_pdln');
        // $this->crud_ajax->init('t_laporan_pdln','id_pdln',null);
        // $data_save_laporan = array(
        //         'date_created' => date('Y-m-d H:i:s')
        //     );
        // $this->crud_ajax->update($where,$data_save_laporan);

        $where = array('id_pdln'=>$id_surat);
        $pdlnya = $this->crud_ajax->init('m_pdln','id_pdln',null);
        $data_save_pdln = array(
            'update_date' => strtotime(date("Y-m-d")),
            'is_final_print'=>1
        );
        $laporannya = $this->crud_ajax->update($where,$data_save_pdln);

        if (!empty($pdlnya) && !empty($laporannya)) {
            $response['status'] = TRUE;
            $response['msg'] = 'Dokumen laporan realisasi berhasil diupload';
        }else{
            $response['status'] = FALSE;
            $response['msg'] = 'Dokumen laporan realisasi berhasil diupload';
        }
        echo json_encode($response);
        exit;

    }
	
	private function isExist_Laporan($id_surat){
    	$this->db->where('id_pdln',$id_surat);
    	$query = $this->db->get('t_laporan_pdln');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	} 
    	return (bool) $result;
    }	
	public function get_data_laporan(){
    	$response = array();    	
    	$ID = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('t_laporan_pdln');
    	$this->db->where('id_surat_keluar',$ID);
    	
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(
                            'id_surat_keluar'=>$row->id_surat_keluar,
							'dokumen'=>$row->dokumen, 
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
    }	
	public function view_laporan(){
    	$data = array('title'=>"Dokumen"); 
        $this->load->view('v_view_dokumen',$data,TRUE);
    }	
	public function list_permohonan(){
        $order = array('update_date','DESC');
        $this->crud_ajax->init('view_monitoring_pdln','id_pdln',$order);
        $where_not = array("40","50");
        
        $field = "jenis_permohonan";
        $this->crud_ajax->setExtraWhereNotIn($field,$where_not);
        $where = array(
                        'unit_fp'=>$this->session->user_id,
                        'status'=>11,
                        'is_final_print'=>NULL
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
    public function get_sp_path($id_pdln){
        $query = $this->db->get_where('m_pdln',array('id_pdln'=>$id_pdln))->row();
        $created_date = $query->create_date;

        $query_fileName = $this->db->get_where('t_laporan_pdln',array('id_pdln'=>$id_pdln))->row();
        $filename = $query_fileName->dokumen;
        (empty($filename)) ? $response['status'] = FALSE : $response['status'] = TRUE;
        $response['path_sp'] = get_file_pdln("sp",date("Y-m-d",$created_date),$id_pdln,$filename);

        echo json_encode($response);
    }
}