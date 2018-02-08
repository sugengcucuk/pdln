<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct()
	{
		parent ::__construct();
	}

	public function index()
	{
		$instansi = 0;
        $sekarang = date("Y-m-d");
		$id_user = $this->session->user_id;
		if ($id_user > 0) {
			$this->db->select('instansi');
	        $this->db->from('m_user');
	        $this->db->where('UserID', $id_user);
	        $instansi_cek =$this->db->get()->row();
	        $instansi = $instansi_cek->instansi;
		}
        $test_survey = 0;
        $cek_instansi_survey = $this->db->get_where('user_survey' ,array('id_instansi' => $instansi))->result();
        $data_survey = array();
        if (!empty($cek_instansi_survey)) {
			$instansi = $instansi_cek->instansi;

        	$is_done_survey = 0;
	        $data = array();
        	foreach ($cek_instansi_survey as $cek) {
	        	$sr = $this->db->get_where('survey', array('id' => $cek->id_survey,'is_active ' => 1))->row();
	        	if (!empty($sr)) {
					$row = array();
					$row[] =  $sr->id;
					$row[] =  $sr->title;
					$row[] =  $sr->description;

					$str_date = strtotime($sr->start_date);
		    		$end_date = strtotime($sr->end_date);
		    		$now_date = strtotime($sekarang);

					if (($now_date >= $str_date) && ($now_date <= $end_date)) { 
		        		$survey_log_responden = $this->db->get_where('survey_log_responden', array('id_user' => $id_user,'id_survey'=> $sr->id))->row();
				        
				        if (!empty($survey_log_responden)) {
				        	$is_done_survey = $survey_log_responden->is_done;
				        }else{
				        	$test_survey = 1;
							$this->db->select('id,id_survey,question');
					        $this->db->from('survey_item');
					        $this->db->where('id_survey' , $sr->id);
					        $survey_item = array();
					        $survey_item =  $this->db->get()->result();

				        	$row[] =  $survey_item;
				        	$data[] = $row;
				        }
					}
				}
			}
			if ($test_survey > 0) {
				$data['survey_not_yet'] 		= $data;
			}
        }else{

        }

        // $survey = $this->db->get_where('survey', array('id' => $instansi,'is_active ' => 1))->result();
        

		// SETUP SURVEY 
		$data['test_survey'] 		= $test_survey;

		$data['theme'] 		= 'pdln';
		$data['page'] 		= 'dashboard';
		$data['title'] 		= 'Dashboard';
		$data['title_page'] = 'Dashboard';
		$data['breadcrumb'] = 'Beranda';
		page_render($data);
	}

	public function tabulasi_survey()
	{
		$response['status'] = TRUE;
		$id_user = $this->session->user_id;
        $instansi = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->instansi;
		$jumlah_survey = $this->input->post('jumlah_survey');


        $data = array();
		for ($i=0; $i < $jumlah_survey ; $i++) { 
			$plus1 = $i+1;
			$id_survey = $this->input->post('id_survey_'.$i);
			$jum_pertanyaan = $this->input->post('jum_prertanyaan_'.$plus1);

			$res = $this->db->get_where('survey', array('id' => $id_survey))->row()->partisipasi;
			$partisipasi = array(
	            'partisipasi' => $res+1
	        );

			$survey_item = $this->db->get_where('survey_item', array('id_survey' => $id_survey))->result();

			$flag_selesai_survey = 0;
			$batch_jawaban = array();
			foreach ($survey_item as $key) {
				$row = array();
				$jawaban = $this->input->post('ans_'.$id_survey.'_'.$key->id);
				if (!empty($jawaban)) {
					
				
					$detail_ask = $this->db->get_where('survey_item', array('id' => $key->id))->row();
					$tidak_setuju = $detail_ask->tidak_setuju ? $detail_ask->tidak_setuju : 0;
					$kurang_setuju = $detail_ask->kurang_setuju ? $detail_ask->kurang_setuju : 0;
					$setuju = $detail_ask->setuju ? $detail_ask->setuju : 0;
					$sangat_setuju = $detail_ask->sangat_setuju ? $detail_ask->sangat_setuju :0;
					if ($jawaban == 1) {
						$tidak_setuju = $tidak_setuju +1;
					}else if ($jawaban == 2) {
						$kurang_setuju = $kurang_setuju +1;
					}else if ($jawaban == 3) {
						$setuju = $setuju +1;
					}else if ($jawaban == 4) {
						$sangat_setuju = $detail_ask->sangat_setuju +1;
					}

					$reponden = $detail_ask->reponden +1;
					$tabulasi_data = array(
		                'tidak_setuju' => $tidak_setuju,
		                'kurang_setuju' => $kurang_setuju,
		                'setuju' => $setuju,
		                'sangat_setuju' => $sangat_setuju,
		                'reponden' => $reponden
		            );

	            	$this->crud_ajax->init('survey_item', 'id', null);
		            $where_survey = array('id' => $key->id);
		            $this->crud_ajax->update($where_survey, $tabulasi_data);
		            $user_survey_result = array(
		                    'id_survey'=>$id_survey,
		                    'id_user'=>$this->session->user_id,
		                    'id_survey_item'=>$key->id,
		                    'from_instansi'=>$instansi,
		                    'created'=>date("Y-m-d"),
		                    'result'=>$jawaban
		            );
		            $batch_jawaban[] = $user_survey_result;
		        }else{
		        	$flag_selesai_survey = 0;
					$response['message'] = "Pilihan Survey Tidak boleh Kosong ...";
					$response['status'] = FALSE;

					echo json_encode($response);
					exit();
		        }
		        $flag_selesai_survey = 1;
			}
			if ($flag_selesai_survey > 0) {
		        $this->db->insert_batch('user_survey_result',$batch_jawaban);
	            $survey_log_responden = array(
	                    'id_survey'=>$id_survey,
	                    'id_user'=>$this->session->user_id,
	                    'from_instansi'=>$instansi,
	                    'date_survey'=>date("Y-m-d H:i:s"),
	                    'is_done'=>1
	            );
	            $insert_id_approval = $this->db->insert('survey_log_responden',$survey_log_responden);
	            $this->crud_ajax->init('survey', 'id', null);
		        $where_res = array('id' => $id_survey);
		        $this->crud_ajax->update($where_res, $partisipasi);
	        }
		}
			$response['data'] = $data;


		echo json_encode($response);
		
	}

	public function list_dashboard()
	{
		$id_user = $this->session->user_id;
		$level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
		$order = array('update_date'=>'DESC');
		if($level==LEVEL_TEMBUSAN_PDLN){
			$this->crud_ajax->init('m_pdln','id_pdln',$order);
			$this->crud_ajax->set_select_field('
				m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_register,m_pdln.tgl_sp,m_pdln.status,
				m_pdln.create_date,m_pdln.update_date,m_pdln.no_sp,m_pdln.no_surat_usulan_fp,
				m_pdln.path_sp,m_pdln.no_surat_usulan_pemohon,m_pdln.tgl_surat_usulan_pemohon,m_pdln.jenis_permohonan,
				m_kegiatan.NamaKegiatan as nama_jenis_kegiatan,m_pdln.keterangan as catatan,
				m_pdln.id_level_pejabat AS level_pejabat
			');
			$join = array(
				't_template_unit_tembusan'=>array('t_template_unit_tembusan.TemplateID = m_pdln.format_tembusan','inner'),
				'm_kegiatan'=>array('m_kegiatan.ID = m_pdln.id_kegiatan','left')
			);
			$this->crud_ajax->setJoinField($join);
			$where = array('t_template_unit_tembusan.UserID'=>$id_user,'m_pdln.status'=>11);
			$this->crud_ajax->setExtraWhere($where);
		}else{
			$this->crud_ajax->init('view_monitoring_pdln','id_pdln',$order);
			if($level == 27){
				$where = array('status'=>11);
				$this->crud_ajax->setExtraWhere($where);
			} else if (!is_pemohon($this->session->userdata('user_id'))) {//Jika user login adalah FP
				$where = array('unit_fp'=>$this->session->userdata('user_id'));
				$this->crud_ajax->setExtraWhere($where);
			} else if(is_pemohon($this->session->userdata('user_id'))) {//Jika user login adalah Pemohon
				$where = array('unit_pemohon'=>$this->session->userdata('user_id'));
				$this->crud_ajax->setExtraWhere($where);
			}
		}
		$list = $this->crud_ajax->get_datatables();
        $data = array();
       	$no = (isset($_POST['start'])) ? $_POST['start'] : 0;
        foreach ($list as $pdln) {
            $row = array();
			$row[] = $pdln->id_pdln; //0
			$row[] = ++$no.'.'; //1
            $no_register = $pdln->no_register;
			$tgl_register = $pdln->tgl_register;
			$tgl_sp = $pdln->tgl_sp;
			$update_date = $pdln->update_date;
			$no_surat_usulan_fp ='';
			$no_surat_usulan_pemohon= '';
			if (!empty($pdln->no_surat_usulan_fp) && $pdln->no_surat_usulan_fp !='undefined' ) {
				$no_surat_usulan_fp = $pdln->no_surat_usulan_fp;
			}
			if (!empty($pdln->no_surat_usulan_pemohon) && $pdln->no_surat_usulan_pemohon !='undefined' ) {
				$no_surat_usulan_pemohon = $pdln->no_surat_usulan_pemohon;
			}
			$row[] = $no_surat_usulan_pemohon;
			$row[] = (empty($pdln->tgl_surat_usulan_pemohon)) ?  '' : day_dashboard($pdln->tgl_surat_usulan_pemohon);
			$row[] = (empty($no_register)) ?  '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);//2
			$row[] = (empty($tgl_register)) ?  '' : day_dashboard($tgl_register);//3
			if($pdln->status == 12){
				$row[] = '<button type="button" id="view_catatan" name="view_catatan" title="Catatan Pengembalian" class="btn btn-xs btn-outline btn-circle red"><i class="fa fa-eye"></i> Catatan </button>';
			}else{
				$row[] = '';
			}
			if($level==LEVEL_TEMBUSAN_PDLN){
				$row[] ="";
			}else{
				if($this->ion_auth->is_allowed(25,'update')){
					if($pdln->status == 11)
						$row[] = '<button type="button" id="edit_pdln" name="edit_pdln" title="Edit" class="btn btn-xs purple" disabled><i class="fa fa-edit"></i> </button>';
					else{
						$row[] = '<a href="'.base_url().'kotak_surat/approval/edit_task/'.$pdln->id_pdln.'" target="_blank" >'.'<button type="button" id="view_pdln_saja" name="view_pdln_saja" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"> </i> </button> </a>';
					}
				}
				else
					$row[] = '';
			}
			if($pdln->status == 11){
				if ($level == 27) {
					$row[] = '<a href="'.base_url().'layanan/realisasi/pelaporan/'.$pdln->id_pdln.'" target="_blank" >'.'<button type="button" id="laporan_pdln" name="laporan_pdln" title="laporan pdln" class="btn btn-xs blue"><i class="fa fa-file"> </i> View Laporan </button> </a>';
				}else{
					$row[] = '<button type="button" id="preview_sp" name="preview_sp" title="Preview SP" class="btn btn-xs red-sunglo"><i class="fa fa-search-plus"></i> </button>
						  <a  href="' . base_url() . 'kotak_surat/approval/download/' . $pdln->id_pdln . '" target="_blank" title="Download SP" class="btn btn-xs blue"><i class="fa fa-download"></i> </a>';
						}
			}else{
				$row[] = '<button type="button" id="preview_sp" name="preview_sp" title="Preview SP" class="btn btn-xs red-sunglo disabled"><i class="fa fa-search-plus"></i> </button>
						  <button type="button" id="download_sp" name="download_sp" title="Download SP" class="btn btn-xs blue" disabled><i class="fa fa-download"></i> </button>';
			}
			$row[] = $pdln->no_sp;//4
			$row[] = (empty($tgl_sp)) ?  '' : day_dashboard($tgl_sp);//5
			$row[] = '<span class="label label-'.setLabelPdln($pdln->status).'">'.setStatusPdln($pdln->status).'</span>';//6
			$row[] = (empty($update_date)) ?  '' : day_dashboard($update_date);//7
			$row[] = $no_surat_usulan_fp;//8
			//$row[] = $no_surat_usulan_pemohon;//9
			$row[] = setJenisPermohonan($pdln->jenis_permohonan);//10
			$row[] = $pdln->nama_jenis_kegiatan;//11
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

	public function get_log_catatan($id_pdln)
	{
		$response = array();
		$result = $this->db->from('t_approval_pdln')->where('id_pdln',$id_pdln)->where('aksi','tolak')->order_by('id','asc')->get();
		if($result->num_rows() > 0) {
			$response['total_catatan'] = $result->num_rows();
			$response['status'] = TRUE;
			$response['data'] = array();
			foreach ($result->result() as $row) {
				$data = array();
				$data['note'] = ucwords($row->note);
				$data['day_submit_catatan'] = day(date("Y-m-d",strtotime($row->submit_date)));
				$data['time_catatan'] = date("H:i:s",strtotime($row->submit_date));
				array_push($response['data'],$data);
			}
		}
		else $response['status'] = FALSE;
		echo json_encode($response);
	}

	private function _get_detail_pembiayaan($id_karegori_biaya,$id_biaya)
	{
		if ($id_karegori_biaya=="0") {
			$this->db->select('t_ref_pembiayaan_tunggal.id_log_dana_tunggal,r_institution.Nama');
			$this->db->where('id_log_dana_tunggal',$id_biaya);
			$this->db->join('r_institution',"r_institution.ID = t_ref_pembiayaan_tunggal.id_instansi","left");
			return $this->db->get('t_ref_pembiayaan_tunggal')->row()->Nama;
		} else if ($id_karegori_biaya=="1") {
			$this->db->select('t_pemb.id_dana_campuran,ref_camp.by,r_jenis_pembiayaan.Description AS jenis_biaya,
							   (CASE WHEN ref_camp.by=1 THEN t_pemb.instansi_gov WHEN ref_camp.by=2 THEN t_pemb.instansi_donor
							   ELSE 0 END) AS id_instansi_pembiayaan',false);
			$this->db->where('t_pemb.id_dana_campuran',$id_biaya);
			$this->db->from('t_pembiayaan_campuran t_pemb');
			$this->db->join('t_ref_pembiayaan_campuran as ref_camp',"t_pemb.id_dana_campuran = ref_camp.id_dana_campuran");
			$this->db->join('r_jenis_pembiayaan',"r_jenis_pembiayaan.ID = ref_camp.id_jenis_biaya");
			$pembiayaan = " ";
			foreach ($this->db->get()->result() as $pembiaya) {
				$id_instansi = $pembiaya->id_instansi_pembiayaan;
				if ($id_instansi ==0) {
					$pembiayaan .= ' - ' . $pembiaya->jenis_biaya ." : Perseorangan";
				} else {
					$pembiayaan .= ' - ' . $pembiaya->jenis_biaya ." : " .$this->db->select('*')->where( "ID = $id_instansi" )->get('r_institution')->row()->Nama . '<br/><br/>';
				}
			}
			return $pembiayaan;
		}
	}

	public function print_permohonan($id_surat)
	{
		setlocale(LC_ALL, 'id_ID');
		$this->db->select('m_pdln.id_pdln,m_pdln.id_kegiatan,m_pdln.id_level_pejabat,m_pdln.no_sp,m_pdln.tgl_sp,m_pdln.tgl_surat_usulan_fp,m_pdln.no_surat_usulan_fp,m_pdln.pejabat_sign_sp,
							m_pdln.path_sp,m_pdln.format_tembusan,m_pdln.penandatangan_persetujuan,m_pdln.barcode, m_pdln.update_date, m_pdln.create_date');
		$this->db->where('m_pdln.id_pdln',$id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        $base_path = $this->config->item('pdln_upload_path');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = month(date('n', $create_date));
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = "sp_pdln_{$id_surat}_{$update_date}.pdf";
        $fullpath = "{$targetPath}{$filename}";
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
		$this->db->select('r_unit_tembusan.Nama');
		$this->db->join ('r_unit_tembusan','r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
		$this->db->where('TemplateID',$result_data->format_tembusan);
		$unit_tembusan =  $this->db->get('t_template_unit_tembusan')->result();
		$this->db->select('t_log_peserta.id_log_peserta,t_log_peserta.start_date,t_log_peserta.end_date,m_pemohon.nama,m_pemohon.nip_nrp,m_pemohon.jabatan,
							r_institution.Nama as instansi,m_pemohon.instansi_lainnya,t_log_peserta.id_kategori_biaya,t_log_peserta.id_biaya');
		$this->db->join ('m_pemohon','m_pemohon.id_pemohon = t_log_peserta.id_pemohon');
		$this->db->join ('r_institution','r_institution.ID = m_pemohon.id_instansi');
		$this->db->where('id_pdln',$id_surat);
		$temp_pemohon =  $this->db->get('t_log_peserta');
		$list_pemohon = array();
		foreach ($temp_pemohon->result() as $pemohon) {
			$list_pemohon[$pemohon->id_log_peserta] = $pemohon;
			$list_pemohon[$pemohon->id_log_peserta]->pembiayaan = $this->_get_detail_pembiayaan($pemohon->id_kategori_biaya,$pemohon->id_biaya); // Get the categories sub categories
		}
		$this->db->select('m_kegiatan.NamaKegiatan,m_kegiatan.StartDate,m_kegiatan.EndDate,r_negara.nmnegara as Negara');
		$this->db->join('r_negara','r_negara.ID = m_kegiatan.negara','left');
		$this->db->where('m_kegiatan.ID',$result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
		$this->db->select('*');
		$this->db->where('m_user.UserID',$result_data->penandatangan_persetujuan);
        $penandatangan_persetujuan = $this->db->get('m_user')->row();
		$label_penandatangan = $this->get_label_penandatangan($result_data->penandatangan_persetujuan,$result_data->id_level_pejabat);
		$data = array(
            'title'=>"Surat Persetujuan",
            'unit_tembusan'=>$unit_tembusan,
			'data_sp'=>$result_data,
			'kegiatan'=>$kegiatan,
			'penandatangan'=>$penandatangan_persetujuan,
			'label_penandatangan'=>$label_penandatangan,
			'm_pdf'=>$this->load->library('M_pdf'));
        $html = $this->load->view('dashboard/v_print_permohonan',$data,TRUE);
		$data = array(
            'title'=>"Surat Persetujuan",
            'data_lampiran'=>$result_data,
			'kegiatan'=>$kegiatan,
			'list_pemohon'=>$list_pemohon,
			'label_penandatangan'=>$label_penandatangan,
			'm_pdf'=>$this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('dashboard/v_print_lampiran_permohonan',$data,TRUE);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                                    '', '', '', '',
                                    15, // margin_left
                                    15, // margin right
                                    15, // margin top
                                    10, // margin bottom
                                    18, // margin header
                                    5); // margin footer
		if (isset($result_data->barcode)) {
			$this->m_pdf->pdf->SetHTMLFooter ('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
		}
		$this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                                    '', '', '', '',
                                    15, // margin_left
                                    15, // margin right
                                    15, // margin top
                                    10, // margin bottom
                                    18, // margin header
                                    5); // margin footer
		if (isset($result_data->barcode)) {
			$this->m_pdf->pdf->SetHTMLFooter ('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
		}
		$this->m_pdf->pdf->WriteHTML($html_lampiran);
		$this->m_pdf->pdf->Output($fullpath,'F');
		send_file_to_browser($fullpath); // this function will exec die() and exit
    }

	public function get_label_penandatangan($id_user,$level_pejabat)
	{
		$this->db->select('*');
		$this->db->where('m_user.UserID',$id_user);
        $user = $this->db->get('m_user')->row();
		$label = "";
		if (isset($user->level)) {
			if (($user->level==LEVEL_KARO)&&($level_pejabat==LEVEL_ESELON_II)) {
				$label="a.n.	Sekretaris Kementerian Sekretariat Negara <br/>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepala Biro Kerja Sama Teknik Luar Negeri";
			} else if (($user->level==LEVEL_KARO)&&($level_pejabat==LEVEL_ESELON_I))	{
				$label="Plh.	Sekretaris Kementerian Sekretariat Negara <br/>";
			} else if (($user->level==LEVEL_KABAG)) {
				$label="a.n.	Sekretaris Kementerian Sekretariat Negara <br/>
												Plh.&nbsp;&nbsp;&nbsp;Kepala Biro Kerja Sama Teknik Luar Negeri";
			} else if (($user->level==LEVEL_SESMEN)&&($level_pejabat==LEVEL_ESELON_I)) {
				$label="Sekretaris Kementerian Sekretariat Negara <br/>";
			} else if (($user->level==LEVEL_SESMEN)&&($level_pejabat==LEVEL_MENTERI)) {
				$label="Plh. Menteri Sekretariat Negara <br/>";
			} else if (($user->level==LEVEL_MENSESNEG))	{
				$label="Menteri Sekretariat Negara <br/>";
			}
		}
		return $label;
	}
}
