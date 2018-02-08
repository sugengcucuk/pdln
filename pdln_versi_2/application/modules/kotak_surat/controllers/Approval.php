<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller {
    function __construct() {
        parent ::__construct();
        if (!$this->is_logged_in())
        {
            $this->load->config('pdln');
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

    public function index() {
        $this->task();
    }

    public function task() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_task';      
        $data['title'] = 'Item Pekerjaan';
        $data['title_page'] = 'Item Pekerjaan';
        $data['breadcrumb'] = 'Item Pekerjaan';
        page_render($data);
    }

    public function task_list() {
        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();

        $level = $data_user->level;

        $unitkerja = $data_user->unitkerja;
        $instansi = $data_user->instansi;
        $is_plh = $data_user->is_plh;
        $where = "";
        $cek_supervisi_level = $this->db->get_where('m_level', array('LevelID' => $level))->row();
        $this->crud_ajax->init('m_pdln', 'id_pdln', array('m_pdln.create_date' => 'asc'));

        if ($cek_supervisi_level->is_supervisor) {
            $level = $cek_supervisi_level->supervisi_level;
            $user_supervisi = $this->db->get_where('m_user', array('instansi' => $instansi))->result();
            $row_user_id = [];
            foreach ($user_supervisi as $val) {
                $row_user_id[] = $val->UserID;   
            }

            if ($level == LEVEL_PEMOHON) {
                $where = array('m_pdln.status' => '1','is_draft' => '0','m_pdln.unit_pemohon' => $id_user);
                $this->db->where_in('unit_pemohon',$row_user_id);
            }else if ($level == LEVEL_FOCALPOINT) {
                $where = array('m_pdln.status' => '2','is_draft' => '0','m_pdln.unit_fp' => $id_user);
                $this->db->where_in('unit_fp', $row_user_id);
            }
            
        }else{
            if ($level == LEVEL_PEMOHON) {
                $where = array('m_pdln.status' => '1','m_pdln.unit_pemohon' => $id_user);
            }else if ($level == LEVEL_FOCALPOINT) {
                $where = array('m_pdln.status' => '2','m_pdln.unit_fp' => $id_user);
            } else {
                // $this->db->where_not_in('m_pdln.status', array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'));
            }

        }
        $this->crud_ajax->setExtraWhere($where);
        $this->crud_ajax->set_select_field('m_pdln.is_draft , m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_surat_usulan_pemohon,m_pdln.status,m_pdln.no_surat_usulan_fp,m_pdln.create_date,m_kegiatan.NamaKegiatan,
                                            unit_kerja.Name as unit_pemohon,unit_kerja2.Name as unit_fp,m_pdln.jenis_permohonan');
        $join = array(
                'm_user as user1' => array('user1.UserID = m_pdln.unit_pemohon', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID =  user1.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user2.unitkerja', 'left')
            );
        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $pdln) {
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = str_pad($pdln->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = date("d/m/Y", ($pdln->tgl_surat_usulan_pemohon));
            if ($cek_supervisi_level->is_supervisor) {
                $row[] = '<a href="' . base_url() . 'kotak_surat/approval/edit_task/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View </button></a>';
                $row[] = '<a href="' . base_url() . 'kotak_surat/approval/view_arsip/' . $pdln->id_pdln . '"><button class="btn btn-sm red btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Archive </button></a>';
            }else{
                $row[] = '<a href="' . base_url() . 'kotak_surat/modify/edit_wizard/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View </button></a>';
                $row[] = '<a href="' . base_url() . 'kotak_surat/approval/view_arsip/' . $pdln->id_pdln . '"><button class="btn btn-sm red btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Archive </button></a>';

            }
            
            $row[] = $pdln->no_surat_usulan_fp == 'undefined' ?  '' : $pdln->no_surat_usulan_fp;
            $row[] = $pdln->unit_pemohon;
            $row[] = $pdln->unit_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = $pdln->NamaKegiatan;
            $row[] = '<span class="label label-danger">' . setStatus_doc($pdln->status) . '</span>';
            $row[] = $pdln->tgl_surat_usulan_pemohon;
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function plh_task_level($jenis_plh) {
        $pdln_level = 0;
        if ($jenis_plh == 1) { //jika plh karo
            $pdln_level = 6;
        } else if ($jenis_plh == 2) { //jika plh sesmen
            $pdln_level = 7;
        } else if ($jenis_plh == 3) { //jika plh menteri
            $pdln_level = 8;
        }
        return $pdln_level;
    }

    public function edit_task($id_pdln) {
        $where = array('Status' => '1');
        /* $this->crud_ajax->init('r_level_pejabat','id',null);
          $this->crud_ajax->setExtraWhere($where);
          $data['level_pejabat'] = $this->crud_ajax->get_data();
          $this->crud_ajax->init('r_jenis_kegiatan','ID',null);
          $this->crud_ajax->setExtraWhere($where);
          $data['jenis_kegiatan']		= $this->crud_ajax->get_data();
          $this->crud_ajax->init('r_negara','id',null);
          $this->crud_ajax->setExtraWhere($where);
          $data['negara']		= $this->crud_ajax->get_data();
          $this->crud_ajax->init('r_jenis_pembiayaan','ID',null);
          $this->crud_ajax->setExtraWhere($where);
          $data['jenis_pembiayaan'] = $this->crud_ajax->get_data();
          $this->crud_ajax->init('r_institution','ID',null);
          $this->crud_ajax->setExtraWhere($where);
          $data['list_instansi'] = $this->crud_ajax->get_data();
         */
        $this->crud_ajax->init('r_template_tembusan', 'ID', null);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_temp_tembusan'] = $this->crud_ajax->get_data();
        $data['id_pdln'] = $id_pdln;
        $this->crud_ajax->init('t_approval_pdln', 'id', null);
        $where_pdln = array('id_pdln' => $id_pdln, 'is_done' => 1);
        $this->crud_ajax->setExtraWhere($where_pdln);
        $data['list_approval'] = $this->crud_ajax->get_data(); //get history approval
        $this->db->select('p.id_signed,p.id_pdln,p.id_kegiatan,p.no_surat_usulan_pemohon,p.tgl_surat_usulan_pemohon,p.no_surat_usulan_fp,lp.nama as level_pejabat,
                            p.tgl_surat_usulan_fp,p.pejabat_sign_sp,p.id_level_pejabat,p.format_tembusan,p.jenis_permohonan,p.status,p.id_level_pejabat,p.author');
        $this->db->where('p.id_pdln', $id_pdln);
        $this->db->from('m_pdln p');
        $this->db->join("r_level_pejabat lp", "lp.id = p.id_level_pejabat");
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
        $data['page'] = 'v_edit_task';
        $data['title'] = 'Form Persetujuan';
        $data['title_page'] = 'Form Persetujuan';
        $data['breadcrumb'] = 'Form Persetujuan';
        page_render($data);
    }

    public function view_disetujui($id_pdln) {
        $where = array('Status' => '1');
        $this->crud_ajax->init('r_template_tembusan', 'ID', null);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_temp_tembusan'] = $this->crud_ajax->get_data();
        $data['id_pdln'] = $id_pdln;
        $this->crud_ajax->init('t_approval_pdln', 'id', null);
        $where_pdln = array('id_pdln' => $id_pdln, 'is_done' => 1);
        $this->crud_ajax->setExtraWhere($where_pdln);
        $data['list_approval'] = $this->crud_ajax->get_data(); //get history approval
        $this->db->select('p.id_signed ,lp.nama as level_pejabat, p.id_pdln,p.id_kegiatan,p.no_surat_usulan_pemohon,p.tgl_surat_usulan_pemohon,p.no_surat_usulan_fp,
                            p.tgl_surat_usulan_fp,p.pejabat_sign_sp,p.id_level_pejabat,p.format_tembusan,p.jenis_permohonan,p.status,p.id_level_pejabat,p.author');
        $this->db->where('p.id_pdln', $id_pdln);
        $this->db->from('m_pdln p');
        $this->db->join("r_level_pejabat lp", "lp.id = p.id_level_pejabat");
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
            // && in_array($data['data_pdln']->status, $pdln_status) == false
            ){
            // show_error("Anda tidak memiliki akses terhadap halaman atau data di halaman ini. ", 403, "Forbidden");
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
        $data['page'] = 'v_veiw_disetujui';
        $data['title'] = 'Surat Persetujuan';
        $data['title_page'] = 'Surat Persetujuan';
        $data['breadcrumb'] = 'Surat Persetujuan';
        page_render($data);
    }

    public function get_file_path() {
        $id_pdln = $this->input->post('id_pdln');
        $this->db->where('id_pdln', $id_pdln);
        $row = $this->db->get('m_pdln')->row();
        $date_created = date("Y-m-d", $row->create_date);
        $file_pemohon = $row->path_file_sp_pemohon;
        $file_fp = $row->path_file_sp_fp;
        $response['status'] = TRUE;
        $path_pemohon = get_file_pdln("pdln", $date_created, $id_pdln, $file_pemohon);
        $path_fp = get_file_pdln("pdln", $date_created, $id_pdln, $file_fp);
        if (!empty($file_pemohon)) {
            $response['path_pemohon'] = $path_pemohon;
            $response['status_file_pemohon'] = TRUE;
            $response['msg'] = "Simpan data berhasil";
        } else
            $response['status_file_pemohon'] = FALSE;
        if (!empty($file_fp)) {
            $response['path_focal_point'] = $path_fp;
            $response['status_file_fp'] = TRUE;
        } else
            $response['status_file_fp'] = FALSE;
        echo json_encode($response);
    }

    public function get_file_kegiatan() {
        $id_jenis = $this->db->get_where('m_kegiatan', array('ID' => $this->input->post('id_jenis')))->row()->JenisKegiatan;
        $id_pdln = $this->input->post('id_pdln');
        $created_date = date("Y-m-d", ($this->db->get_where('m_pdln', array('id_pdln' => $id_pdln))->row()->create_date));
        $this->db->from('view_doc_kegiatan');
        $this->db->where('id_jenis_kegiatan', $id_jenis);
        $result = $this->db->get();
        $response = array();
        if ($result->num_rows() > 0) {
            $data['status'] = TRUE;
            foreach ($result->result() as $row) {
                $data = array();
                $data['id_jenis_doc'] = $row->id_jenis_doc;
                $data['nama_doc'] = $row->nama_doc;
                $data['nama_full_doc'] = ucwords($row->nama_full_doc);
                $data['is_require'] = ($row->is_require == '1' ? TRUE : FALSE );
                $data['id_jenis_kegiatan'] = $row->id_jenis_kegiatan;
                $id_jenis_doc = $row->id_jenis_doc;
                $where = array(
                    'id_jenis_doc' => $id_jenis_doc,
                    'id_pdln' => $id_pdln
                );
                $is_exist = $this->db->get_where('m_dok_pdln', $where);
                if ($is_exist->num_rows() > 0) {
                    $nama_file_doc = $is_exist->row()->dir_path;
                    $path_file = get_file_pdln("kegiatan", $created_date, $id_pdln, $nama_file_doc);
                    $data['path_file'] = $path_file;
                    $data['is_exist'] = TRUE;
                } else {
                    $data['is_exist'] = FALSE;
                }
                array_push($response, $data);
            }
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function lanjutkan() {
        $this->_lanjutkan_validate();
        $response['status'] = TRUE;
        echo json_encode($response);
    }

    /**
     * @method private _validate handle validation data users
     * @return json output status on form or modal
     */
    private function _lanjutkan_validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        $status = $this->input->post('status');
        $note = $this->input->post('note');
        $level = $this->input->post('level');
        $nextlevel = $this->input->post('nextlevel');
        $template_tembusan = $this->input->post('template_tembusan');
        $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
        if ($note === "") {
            $data['status'] = FALSE;
            $data['message'] = "Silahkan berikan catatan terlebih dahulu sebelum memberikan persetujuan";
            echo json_encode($data);
            exit;
        } else if ($template_tembusan === "0" || !isset($template_tembusan)) {
            if (($level_user == LEVEL_ANALIS) || ($level_user == LEVEL_KASUBAG) || ($level_user == LEVEL_KABAG)) {
                $data['status'] = FALSE;
                $data['message'] = "Silahkan pilih format tembusan terlebih dahulu";
                echo json_encode($data);
                exit;
            }
        }
        if ($data['status'] === TRUE) {
            /* Insert New Row To Next Approval */
            $this->crud_ajax->init('t_approval_pdln', 'id', null);
            $data_approval = array(
                'id_pdln' => $id_pdln,
                'assign_date' => date('Y-m-d H:i:s'),
                'level' => $nextlevel
            );
            $insert_id_u = $this->crud_ajax->save($data_approval);
            /* Update Current Data Approval */
            $data_update_approval = array(
                'user_id' => $this->session->user_id,
                'note' => $note,
                'submit_date' => date('Y-m-d H:i:s'),
                'aksi' => 'setuju',
                'is_done' => 1,
            );
            $where_approval = array('level' => $level, 'id_pdln' => $id_pdln, 'is_done' => 0);
            $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            if (($level_user == LEVEL_ANALIS) || ($level_user == LEVEL_KASUBAG) || ($level_user == LEVEL_KABAG)) {
                $data_pdln = array(
                    'status' => intval($status) + 1,
                    'format_tembusan' => $template_tembusan
                );
            } else {
                $data_pdln = array(
                    'status' => intval($status) + 1,
                );
            }
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
        }
    }

    public function lanjutketu() {
        $response['id_pdln'] = $this->_lanjutketu_validate();
        $response['status'] = TRUE;
        echo json_encode($response);
    }
    /**
     * @method private _validate handle validation data users
     * @return json output status on form or modal
     */
    private function _lanjutketu_validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        $status = $this->input->post('status');
        $level = $this->input->post('level');
        $nextlevel = $this->input->post('nextlevel');
        $note = $this->input->post('note');
        /* Insert New Row To Next Approval */
        $this->crud_ajax->init('t_approval_pdln', 'id', null);
        $data_approval = array(
            'id_pdln' => $id_pdln,
            'assign_date' => date('Y-m-d H:i:s'),
            'level' => $nextlevel
        );
        $insert_id_u = $this->crud_ajax->save($data_approval);
        /* Update Current Data Approval */
        $data_update_approval = array(
            'user_id' => $this->session->user_id,
            'submit_date' => date('Y-m-d H:i:s'),
            'note' => $note,
            'aksi' => 'setuju',
            'is_done' => 1,
        );
        $where_approval = array('level' => $level, 'id_pdln' => $id_pdln, 'is_done' => 0);
        $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
        if($affected_row_u>0){
            $data_email=array(
                'to'=>$this->session->email,
                'subject'=>'[SIMPLE] Permohonan DiSetujui',
                'body'=>'Disetujui'
            );
           // $is_send=$this->send_email($data_email);
        }
        $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
        $curr_status = $this->db->get_where('m_pdln', array('id_pdln' => $id_pdln))->row()->status;
        if ($level_user == LEVEL_SESMEN && $curr_status == 7) {
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'status' => 9,
                'penandatangan_persetujuan' => $this->session->user_id
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
        } else {
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'status' => 10,
                'penandatangan_persetujuan' => $this->session->user_id
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
        }
        return $id_pdln;
    }

    //Kepala biro setuju
    public function setuju() {
        $this->_setuju_validate();
        $response['status'] = TRUE;
        echo json_encode($response);
    }

    /**
     * @method private _validate handle validation data users
     * @return json output status on form or modal
     */
    private function _setuju_validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        $status = $this->input->post('status');
        $level = $this->input->post('level');
        $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
        $note = $this->input->post('note');
        if ($data['status'] === TRUE) {
            /* Update Current Data Approval */
            $data_update_approval = array(
                'user_id' => $this->session->user_id,
                'submit_date' => date('Y-m-d H:i:s'),
                'note' => $note,
                'aksi' => 'setuju',
                'is_done' => 1,
            );
            $this->crud_ajax->init('t_approval_pdln', 'id', null);
            $where_approval = array('level' => $level, 'id_pdln' => $id_pdln, 'is_done' => 0);
            $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
            $this->db->select('m_pdln.id_pdln,m_pdln.id_kegiatan,m_pdln.no_sp,m_pdln.tgl_sp,m_pdln.tgl_surat_usulan_fp,
                               m_pdln.no_surat_usulan_fp,m_pdln.pejabat_sign_sp,
                               m_pdln.format_tembusan, m_pdln.id_pdln_lama, m_pdln.jenis_permohonan');
            $this->db->where('m_pdln.id_pdln', $id_pdln);
            $data_pdln = $this->db->get('m_pdln')->row();
            // Get data for Update Perpanjang/Ralat/Pembatalan
            $id_pdln_lama = $data_pdln->id_pdln_lama;
            $jenis_permohonan = $data_pdln->jenis_permohonan;
            $jenis_kegiatan = $this->db->get_where('m_kegiatan', array('ID' => $data_pdln->id_kegiatan))->row()->JenisKegiatan;
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'no_sp' => $this->generate_number($jenis_kegiatan),
                'penandatangan_persetujuan' => $this->session->user_id,
                'tgl_sp' => strtotime(date('Y-m-d H:i:s')),
                'status' => 11,
                'barcode' => mt_rand(100000, 999999),
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
            $sp_file = $this->cetak_permohonan_final($id_pdln);
            //update sp file
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'path_sp' => $sp_file,
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
            if($affected_row_u>0){
                $data_email=array(
                    'to'=>$this->session->email,
                    'subject'=>'[SIMPLE] Permohonan DiSetujui',
                    'body'=>'Disetujui'
                );
               // $is_send=$this->send_email($data_email);
            }
            // Update Status SP LAMA menjadi diperpanjang/diralat/dibatalkan
            switch ($jenis_permohonan) {
                case "20":  // Perpanjangan
                    $status_baru = '14';    // Perpanjangan
                    $this->crud_ajax->init('m_pdln', 'id', null);
                    $data_pdln_lama = array(
                        'status' => $status_baru,
                    );
                    $where_pdln_lama = array('id_pdln' => $id_pdln_lama);
                    $affected_row_u = $this->crud_ajax->update($where_pdln_lama, $data_pdln_lama);
                    break;
                case "30":  // Ralat
                    $status_baru = '13';     // Ralat
                    $this->crud_ajax->init('m_pdln', 'id', null);
                    $data_pdln_lama = array(
                        'status' => $status_baru,
                    );
                    $where_pdln_lama = array('id_pdln' => $id_pdln_lama);
                    $affected_row_u = $this->crud_ajax->update($where_pdln_lama, $data_pdln_lama);
                    break;
                case "40":  // Pembatalan
                    $status_baru = '15';     // Pembatalan
                    $this->crud_ajax->init('m_pdln', 'id', null);
                    $data_pdln_lama = array(
                        'status' => $status_baru,
                    );
                    $where_pdln_lama = array('id_pdln' => $id_pdln_lama);
                    $affected_row_u = $this->crud_ajax->update($where_pdln_lama, $data_pdln_lama);
                    break;
            }
        }
    }

    public function tu_setuju() {
        $this->_tu_setuju_validate();
        $response['status'] = TRUE;
        echo json_encode($response);
    }

    /**
     * @method private _validate handle validation data users
     * @return json output status on form or modal
     */
    private function _tu_setuju_validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        $no_sp = $this->input->post('no_sp');
        $tanggal_surat = $this->input->post('tanggal_surat');
        $level = $this->input->post('level');
        $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
        if ($no_sp === "") {
            $data['status'] = FALSE;
            $data['message'] = "Nomor Surat Harus Di isi";
            echo json_encode($data);
            exit;
        }
        if ($tanggal_surat === "") {
            $data['status'] = FALSE;
            $data['message'] = "Tanggal Surat Harus Di isi";
            echo json_encode($data);
            exit;
        }
        if ($data['status'] === TRUE) {
            //generate final sp
            $sp_file = $this->cetak_permohonan_final($id_pdln);
            /* Update Current Data Approval */
            $data_update_approval = array(
                'user_id' => $this->session->user_id,
                'submit_date' => date('Y-m-d H:i:s'),
                'aksi' => 'setuju',
                'is_done' => 1,
            );
            $this->crud_ajax->init('t_approval_pdln', 'id', null);
            $where_approval = array('level' => $level, 'id_pdln' => $id_pdln, 'is_done' => 0);
            $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'no_sp' => $no_sp,
                'tgl_sp' => strtotime($tanggal_surat),
                'status' => 11,
                'path_sp' => $sp_file
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $affected_row_u = $this->crud_ajax->update($where_pdln, $data_pdln);
            if($affected_row_u>0){
                $data_email=array(
                    'to'=>$this->session->email,
                    'subject'=>'[SIMPLE] Permohonan DiSetujui',
                    'body'=>'Disetujui'
                );
               // $is_send=$this->send_email($data_email);
            }
        }
    }

    public function tolak() {
        $this->_tolak_validate();
        $response['status'] = TRUE;
        echo json_encode($response);
    }

    /**
     * @method private _validate handle validation data users
     * @return json output status on form or modal
     */
    private function _tolak_validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        // $status = $this->input->post('status');
        // $level = $this->input->post('level');
        // $level = $this->session->level;
        $note = $this->input->post('note_tolak');
        $response['note'] = $note;
        $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
        if ($note === "") {
            $data['status'] = FALSE;
            $data['message'] = "Silahkan berikan catatan terlebih dahulu sebelum memberikan penolakan !!!";
            echo json_encode($data);
            exit;
        }
        if ($data['status'] === TRUE) {
            /* Update status in pdln table */
            $this->crud_ajax->init('m_pdln', 'id', null);
            $data_pdln = array(
                'status' => 0,
            );
            $where_pdln = array('id_pdln' => $id_pdln);
            $this->crud_ajax->update($where_pdln, $data_pdln);

            $this->load->library('spde2');
            $this->_ci = & get_instance();
            $this->_ci->load->config('pdln', true);
            $mobile_notif = $this->_ci->config->item('notif', 'pdln');
            $notif['key'] = $mobile_notif['key_notif'];
            $notif['pdln_current_status'] = intval(0);
            $notif['pdln_id'] = $id_pdln;
            $respon_token = json_decode($this->spde2->postRequest($mobile_notif['notif_mobile'], $notif, $refer = "", $timeout = 3000, $header = []));
            $data_approval = array(
                                'id_pdln'=>$id_pdln,
                                'user_id'=>$this->session->user_id,
                                'note'=>$this->input->post('note_tolak'),
                                'submit_date'=>date("Y-m-d H:i:s"),
                                'assign_date'=>'',
                                'level'=>'Focalpoint',
                                'aksi'=>'Tolak',
                                'is_done'=>0
                        );
            $insert_id_approval = $this->db->insert('t_approval_pdln',$data_approval);
            if($insert_id_approval>0){
                $data_email=array(
                    'to'=>$this->session->email,
                    'subject'=>'[SIMPLE] Permohonan Dikembalikan',
                    'body'=>'Dikembalikan'
                );
               $is_send=$this->send_email($data_email);
            }
            /* Insert New Row To Next Approval */
            // $this->crud_ajax->init('t_approval_pdln', 'id', null);
            // $data_approval = array(
            //     'id_pdln' => $id_pdln,
            //     'assign_date' => date('Y-m-d H:i:s'),
            //     'level' => $nextlevel
            // );
            // $data_update_approval = array(
            //     'user_id' => $this->session->user_id,
            //     'note' => $note,
            //     'submit_date' => date('Y-m-d H:i:s'),
            //     'level' => 'Focalpoint',
            //     'aksi' => 'tolak',
            //     'is_done' => 0
            // );
            // $insert_id_u = $this->crud_ajax->save($data_update_approval);
            // $this->crud_ajax->init('t_approval_pdln', 'id', null);
            // // $insert_id_u = $this->crud_ajax->save($data_update_approval);
            // $this->db->insert('t_approval_pdln',$data_update_approval);
            // $this->db->insert('t_suratmasuk_increment',$data_init);
            // $data_approval = array(
            //     'id_pdln' => $id_pdln,
            //     'assign_date' => date('Y-m-d H:i:s'),
            // );
            /* Update Current Data Approval */
            // $where_approval = array('level' => $level_user, 'id_pdln' => $id_pdln, 'is_done' => 0);
            // $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
            // $affected_row_u = $this->db->insert('t_approval_pdln',$data_approval);
            // if(empty($insert_id_u)){
            //     $response['msg'] = "Gagal Simpan Data Workflow";
            //     $response['status'] = FALSE;
            // }else{
            //     if(is_pemohon($this->session->user_id))
            //         $response['msg'] = "Focal Point";
            //     else{
            //         $response['msg'] = "SETNEG";
            //         // $response['no_register'] = str_pad($no_register, 8, '0', STR_PAD_LEFT);
            //     }
            // }
            // var_dump($data_update_approval);
            // echo ($insert_id_u);
            // exit();
        }
    }

    public function generate_number($JenisKegiatan) {
        $penomoran = $this->db->get_where('t_suratkeluar_increment', array('Status' => 1))->row();
        $jns_kegiatan = $this->db->get_where('r_jenis_kegiatan', array('ID' => $JenisKegiatan))->row();
        $number = intval($penomoran->Nomor) + 1;
        $number_str = (string) $number;
        // for format length nomor surat is 8 digit,make the zero number show in fix_number var
        for ($x = strlen($number_str); $x < 8; $x++) {
            $number_str = '0' . $number_str;
        }
        $fix_number = $penomoran->InitialCode . "-" . $number_str . "/" . $penomoran->Formatting . "/" . $jns_kegiatan->Kodifikasi . "/" . date('m') . "/" . date('Y');
        $data = array('Nomor' => $number);
        $this->db->where('Status', 1);
        $this->db->update('t_suratkeluar_increment', $data);
        return $fix_number;
    }

    public function get_parent_id() {
        $this->db->where('UserID', $this->session->userdata('user_id'));
        $parent = $this->db->get('m_user')->row()->unitkerja;
        return $parent; //unitkerja user fp sbg parent
    }

    public function get_list_pemohon() {
        $this->db->where('m_unit.Parent', $this->get_parent_id());
        $this->db->where('m_unit.Status', '1');
        $this->db->where_in('m_unit.Group', array(1, 2));
        $this->db->from('m_unit_kerja_institusi as m_unit');
        $this->db->join('m_user as mu', 'm_unit.ID = mu.unitkerja', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result();
        return FALSE;
    }

    public function get_kegiatan() {
        $id_jenis = $this->input->post('id_jenis');
        $where = array(
            'JenisKegiatan' => $id_jenis,
            'Status' => '1'
        );
        $this->crud_ajax->init('m_kegiatan', 'ID', null);
        $this->crud_ajax->setExtraWhere($where);
        $query = $this->crud_ajax->get_data();
        if (count($query) > 0) {
            foreach ($query as $row) {
                echo '<option value="">--Pilih--</option>';
                echo '<option value="' . $row->ID . '">' . trim($row->NamaKegiatan) . '</option>';
            }
        } else
            echo '<option value="">--Kegiatan Tidak Tersedia--</option>';
    }

    public function get_detail_keg() {
        $id_kegiatan = $this->input->post('id_keg');
        $data = array();
        $this->db->from('view_kegiatan');
        $this->db->where('id_kegiatan', $id_kegiatan);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            $data['status'] = TRUE;
            foreach ($result->result() as $row) {
                $data['penyelenggara'] = $row->penyelenggara;
                $data['negara'] = $row->nmnegara;
                $data['kota'] = $row->nmkota;
                $data['tgl_mulai_kegiatan'] = day($row->tgl_mulai_kegiatan);
                $data['tgl_akhir_kegiatan'] = day($row->tgl_akhir_kegiatan);
            }
            $data['status'] = TRUE;
        } else
            $data['status'] = FALSE;
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    // =====stream_context_set_default(options)
    public function arsip() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_arsip';
        $data['title'] = 'Permohonan Dalam Proses';
        $data['title_page'] = 'Permohonan Dalam Proses';
        $data['breadcrumb'] = 'Permohonan Dalam Proses';
        page_render($data);
    }

    public function is_arsip() {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        $handle_negara = $this->get_handle_negara($id_user);
        $this->crud_ajax->init('m_pdln', 'id_pdln', array('m_pdln.tgl_surat_usulan_pemohon' => 'asc'));
        if ($level == LEVEL_PEMOHON) {
            $where = array('m_pdln.status' => 200, 'm_pdln.unit_pemohon' => $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('m_pdln.status' => 200, 'm_pdln.unit_fp' => $id_user);
        }
        $this->crud_ajax->set_select_field('m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_surat_usulan_pemohon,m_pdln.status,m_pdln.no_surat_usulan_fp,m_pdln.create_date,m_kegiatan.NamaKegiatan,
                                            unit_kerja.Name as unit_pemohon,unit_kerja2.Name as unit_fp,m_pdln.jenis_permohonan');
        if ($level == LEVEL_PEMOHON) {
            $join = array(
                'm_user as user1' => array('user1.UserID = m_pdln.unit_pemohon', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user1.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                // 'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user1.unitkerja', 'left')
            );
        }else if  ($level == LEVEL_FOCALPOINT) {
            $join = array(
                'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user2.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user2.unitkerja', 'left')
            );
        }

        $this->crud_ajax->setExtraWhere($where);
        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start']))?$_POST['start']:0;
        foreach ($list as $pdln) {
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = str_pad($pdln->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = date("d/m/Y", ($pdln->tgl_surat_usulan_pemohon));
            $row[] = '<a href="' . base_url() . 'kotak_surat/approval/view_arsip/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
            $row[] = $pdln->no_surat_usulan_fp == 'undefined' ?  '' : $pdln->no_surat_usulan_fp;
            $row[] = $pdln->unit_pemohon;
            $row[] = $pdln->unit_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = $pdln->NamaKegiatan;
            $row[] = '<span class="label label-danger">' . setStatus_doc($pdln->status) . '</span>';

            $row[] = $pdln->tgl_surat_usulan_pemohon;
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function do_archiv($id_pdln){
        $response['status'] = false;
        $note =$this->input->post('note');
        if ($note === "") {
            $data['status'] = FALSE;
            $data['message'] = "Silahkan berikan catatan terlebih dahulu sebelum menjadikan !!!";
            echo json_encode($data);
            exit;
        }
        if (!empty($id_pdln)) {
            $data = array(
                'status'=>200
            );
            $this->db->where('id_pdln',$id_pdln);
            $affected_rows = $this->db->update('m_pdln',$data);
            $level_user = $this->db->get_where('m_user', array('UserID' => $this->session->user_id))->row()->level;
            $level_name = $this->db->get_where('m_level', array('LevelID' => $level_user))->row()->NamaLevel;
            $data_approval = array(
                                'id_pdln'=>$id_pdln,
                                'user_id'=>$this->session->user_id,
                                'note'=>$this->input->post('note'),
                                'submit_date'=>date("Y-m-d H:i:s"),
                                'assign_date'=>'',
                                'level'=>$level_name,
                                'aksi'=>'Arsip',
                                'is_done'=>1
                        );
            $insert_id_approval = $this->db->insert('t_approval_pdln',$data_approval);
            $response['status'] = TRUE;
            $response['msg'] = "Dokumen berhasil di Arsipkan";
        }
        echo json_encode($response);
    }
    public function view_arsip($id_pdln) {
        $where = array('Status' => '1');
        $this->crud_ajax->init('r_template_tembusan', 'ID', null);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_temp_tembusan'] = $this->crud_ajax->get_data();
        $data['id_pdln'] = $id_pdln;
        $this->crud_ajax->init('t_approval_pdln', 'id', null);
        $where_pdln = array('id_pdln' => $id_pdln, 'is_done' => 1);
        $this->crud_ajax->setExtraWhere($where_pdln);
        $data['list_approval'] = $this->crud_ajax->get_data(); //get history approval
        $this->db->select('lp.nama as level_pejabat, p.id_pdln,p.id_kegiatan,p.no_surat_usulan_pemohon,p.tgl_surat_usulan_pemohon,p.no_surat_usulan_fp,
                           p.tgl_surat_usulan_fp,p.pejabat_sign_sp,p.id_level_pejabat,p.format_tembusan,p.jenis_permohonan,
                           p.status,p.id_level_pejabat,p.author');
        $this->db->where('p.id_pdln', $id_pdln);
        $this->db->from('m_pdln p');
        $this->db->join("r_level_pejabat lp", "lp.id = p.id_level_pejabat");
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
        $data['page'] = 'v_view_arsip';
        $data['title'] = 'Form Arsip';
        $data['title_page'] = 'Form Arsip';
        $data['breadcrumb'] = 'Form Arsip';
        page_render($data);
    }


    public function progress() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_progress';
        $data['title'] = 'Permohonan Dalam Proses';
        $data['title_page'] = 'Permohonan Dalam Proses';
        $data['breadcrumb'] = 'Permohonan Dalam Proses';
        page_render($data);
    }

    public function progress_list() {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where_pdln = "";
        $handle_negara = $this->get_handle_negara($id_user);
        $this->crud_ajax->init('m_pdln', 'id_pdln', array('m_pdln.tgl_surat_usulan_pemohon' => 'asc'));
        if ($level == LEVEL_PEMOHON) {
            $where_pdln = array('m_pdln.status <' => 11,'m_pdln.status >' => 1 ,'m_pdln.unit_pemohon =' => $id_user );
        }else if ($level == LEVEL_FOCALPOINT) {
            $where_pdln = array('m_pdln.status <' => 11,'m_pdln.status >' => 2 ,'m_pdln.unit_fp =' => $id_user );
        }
        $this->crud_ajax->set_select_field('m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_surat_usulan_pemohon,m_pdln.status,
                                            m_pdln.no_surat_usulan_fp,m_pdln.create_date,m_kegiatan.NamaKegiatan,
                                            unit_kerja.Name as unit_pemohon,unit_kerja2.Name as unit_fp,m_pdln.jenis_permohonan');
        if ($level == LEVEL_PEMOHON) {
            $join = array(
                'm_user as user1' => array('user1.UserID = m_pdln.unit_pemohon', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user1.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                // 'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user1.unitkerja', 'left')
            );
        }else if  ($level == LEVEL_FOCALPOINT) {
            $join = array(
                'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user2.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user2.unitkerja', 'left')
            );
        }
        $this->crud_ajax->setExtraWhere($where_pdln);
        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start']))?$_POST['start']:0;
        foreach ($list as $pdln) {
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = str_pad($pdln->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = date("d/m/Y", ($pdln->create_date));

            $row[] = '<a href="' . base_url() . 'kotak_surat/approval/edit_task/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
            $row[] = $pdln->no_surat_usulan_fp == 'undefined' ?  '' : $pdln->no_surat_usulan_fp;
            $row[] = $pdln->unit_pemohon;
            $row[] = $pdln->unit_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = $pdln->NamaKegiatan;
            $row[] = '<span class="label label-danger">' . setStatusPdln($pdln->status) . '</span>';
             // if ($level == LEVEL_PEMOHON) {
                // $row[] = '<a href="' . base_url() . 'kotak_surat/approval/edit_task/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Viewxxx</button></a>';
             // }else if  ($level == LEVEL_FOCALPOINT) {
            // }
            $row[] = $pdln->tgl_surat_usulan_pemohon;
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function retur() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_return';
        $data['title'] = 'Permohonan Dalam Proses';
        $data['title_page'] = 'Permohonan Dalam Proses';
        $data['breadcrumb'] = 'Permohonan Dalam Proses';
        page_render($data);
    }

    public function balik_list() {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        $handle_negara = $this->get_handle_negara($id_user);
        $this->crud_ajax->init('m_pdln', 'id_pdln', array('m_pdln.tgl_surat_usulan_pemohon' => 'asc'));
        if ($level == LEVEL_PEMOHON) {
            $where = array('m_pdln.status' => 0, 'm_pdln.unit_pemohon' => $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('m_pdln.status' => 12, 'm_pdln.unit_fp' => $id_user);
        }

        $this->crud_ajax->set_select_field('m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_surat_usulan_pemohon,
                                            m_pdln.status,m_pdln.no_surat_usulan_fp,m_pdln.create_date,m_kegiatan.NamaKegiatan,
                                            unit_kerja.Name as unit_pemohon,unit_kerja2.Name as unit_fp,m_pdln.jenis_permohonan');
        if ($level == LEVEL_PEMOHON) {
            $join = array(
                'm_user as user1' => array('user1.UserID = m_pdln.unit_pemohon', 'left'),
                'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user1.unitkerja', 'left'),
                'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
                // 'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
                'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user1.unitkerja', 'left')
            );
        }else if  ($level == LEVEL_FOCALPOINT) {
            $join = array(
            'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
            'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user2.unitkerja', 'left'),
            'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
            'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user2.unitkerja', 'left')
            );
        }
        $this->crud_ajax->setExtraWhere($where);

        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();

        $data = array();
        if (isset($_POST['start'])) {
            $no = $_POST['start'];
        } else {
            $no = 0;
        }
        foreach ($list as $pdln) {
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = str_pad($pdln->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = date("d/m/Y", ($pdln->tgl_surat_usulan_pemohon));
            $row[] = '<a href="' . base_url() . 'kotak_surat/modify/edit_wizard/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
            $row[] = $pdln->no_surat_usulan_fp == 'undefined' ?  '' : $pdln->no_surat_usulan_fp;
            $row[] = $pdln->unit_pemohon;
            $row[] = $pdln->unit_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = $pdln->NamaKegiatan;
            $row[] = '<span class="label label-danger">' . setStatus_doc($pdln->status) . '</span>';
             // if ($level == LEVEL_PEMOHON) {
                // $row[] = '<a href="' . base_url() . 'kotak_surat/approval/edit_task/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Viewxxx</button></a>';
             // }else if  ($level == LEVEL_FOCALPOINT) {

            // }
            $row[] = $pdln->tgl_surat_usulan_pemohon;
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function done() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_done';
        $data['title'] = 'Permohonan Sudah Selesai';
        $data['title_page'] = 'Permohonan Sudah Selesai';
        $data['breadcrumb'] = 'Permohonan Sudah Selesai';
        page_render($data);
    }

    public function done_list() {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        $handle_negara = $this->get_handle_negara($id_user);
        $this->crud_ajax->init('m_pdln', 'id_pdln', array('m_pdln.tgl_register' => 'asc'));
            // $this->db->where('unit_pemohon',$id_user);
        // $this->db->where_in('m_pdln.status',array(11,13,14,15));
        if ($level == LEVEL_PEMOHON) {
            $where = array('m_pdln.unit_pemohon' => $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('m_pdln.unit_fp' => $id_user);
        }

        $where = array('m_pdln.status' => 11);
        $this->crud_ajax->set_select_field('m_pdln.id_signed,m_pdln.id_pdln,m_pdln.no_register,m_pdln.tgl_register,m_pdln.status,
                                            m_pdln.no_surat_usulan_fp,m_pdln.create_date,m_kegiatan.NamaKegiatan,
                                            unit_kerja.Name as unit_pemohon,unit_kerja2.Name as unit_fp,m_pdln.jenis_permohonan');
        $join = array(
            'm_user as user1' => array('user1.UserID = m_pdln.unit_pemohon', 'left'),
            'm_unit_kerja_institusi as unit_kerja' => array('unit_kerja.ID = user1.unitkerja', 'left'),
            'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
            'm_user as user2' => array('user2.UserID = m_pdln.unit_fp', 'left'),
            'm_unit_kerja_institusi as unit_kerja2' => array('unit_kerja2.ID = user2.unitkerja', 'left')
        );
        $this->crud_ajax->setExtraWhere($where);
        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        if (isset($_POST['start'])) {
            $no = $_POST['start'];
        } else {
            $no = 0;
        }
        foreach ($list as $pdln) {
            $no++;
            $row = array();
            $row[] = $pdln->id_pdln;
            $row[] = str_pad($pdln->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = date("d/m/Y", ($pdln->tgl_register));
            $row[] = '<a href="' . base_url() . 'kotak_surat/approval/view_disetujui/' . $pdln->id_pdln . '"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button></a>';
            if ($pdln->id_signed) {
                $row[] = '<a href="' . base_url() . 'kotak_surat/approval/download/' . $pdln->id_pdln . '" target="_blank"><button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Download</button></a>';
            }else{
              $row[] = '<button id="download_sp" class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> Download</button>';
            }
            $row[] = $pdln->no_surat_usulan_fp;
            $row[] = $pdln->unit_pemohon;
            $row[] = $pdln->unit_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = $pdln->NamaKegiatan;
            $row[] = '<span class="label label-info">' . setStatus_doc($pdln->status) . '</span>';


            $row[] = $pdln->tgl_register;
			$data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function get_handle_negara($id_user) {
        $this->db->select('RoleID');
        $this->db->from('t_user_role');
        $this->db->where('UserID', $id_user);
        $query = $this->db->get();
        $list_role = $query->result();
        $list_negara = array();
        foreach ($list_role as $role) {
            $id = $this->input->post('RoleID');
            $this->db->select('*');
            $this->db->from('t_role_negara');
            $this->db->where('RoleID', $role->RoleID);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $list_negara[] = $row->NegaraID;
            }
        }

    }
    public function get_handle_unitkerja($unitkerja) {
        $this->db->select('ID');
        $this->db->from('m_unit_kerja_institusi');
        $this->db->where('FocalPoint', $unitkerja);
        $query = $this->db->get();
        $list_role = $query->result();
        $list_pemohon = array();
        foreach ($list_role as $row) {
            $list_pemohon[] = $row->ID;
        }
        // $this->crud_ajax->init('m_user', 'UserID ', array('UserID ' => 'asc'));
        $this->db->select('UserID');
        $this->db->from('m_user');
        $this->db->where_in('unitkerja', $list_pemohon);
        $user_login = $this->db->get()->result();
        $list_usernya = array();
        foreach ($user_login as $log) {
            $list_usernya[] = $log->UserID;
        }
        //var_dump($list_usernya);
        // mesti nyari user mana di unit kerja ini
        return $list_usernya;
    }

    public function get_data_pdln() {
        $response = array();
        $id_pdln = $this->input->post('id_pdln');
        $this->db->select('p.id_kegiatan,p.no_surat_usulan_pemohon,p.tgl_surat_usulan_pemohon,p.id_level_pejabat,p.no_surat_usulan_fp,p.pejabat_sign_sp');
        $this->db->from('m_pdln as p');
        $this->db->where('id_pdln', $id_pdln);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $response = array(
                'id_kegiatan' => $row->id_kegiatan,
                'no_surat_usulan_pemohon' => $row->no_surat_usulan_pemohon,
                'tgl_surat_usulan_pemohon' => $row->tgl_surat_usulan_pemohon,
                'id_level_pejabat' => $row->id_level_pejabat,
                'no_surat_usulan_fp' => $row->no_surat_usulan_fp,
                'pejabat_sign_sp' => $row->pejabat_sign_sp,
                'status' => TRUE
            );
        } else {
            $response['status'] = FALSE;
        }
        echo json_encode($response);
    }

    public function cetak_permohonan_final($id_surat) {
        setlocale(LC_ALL, 'id_ID');
        $this->db->select(' m_pdln.id_pdln,m_pdln.id_kegiatan,m_pdln.id_level_pejabat,m_pdln.no_sp,
                            m_pdln.tgl_sp,m_pdln.tgl_surat_usulan_fp,m_pdln.no_surat_usulan_fp,m_pdln.pejabat_sign_sp,
                            m_pdln.format_tembusan,m_pdln.penandatangan_persetujuan,m_pdln.barcode,
                            r_kota.nmkota,m_pdln.update_date,m_pdln.create_date');
        $this->db->join('m_user', 'm_user.UserID = m_pdln.unit_fp');
        $this->db->join('r_institution', 'r_institution.ID = m_user.instansi');
        $this->db->join('r_kota', 'r_kota.id = r_institution.Kota');
        $this->db->where('m_pdln.id_pdln', $id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        // $base_path = $this->config->item('pdln_upload_path');
        $CI = & get_instance();
        $CI->load->config('pdln');
        $base_path = $CI->config->item('pdln_upload_path');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = month(date('n', $create_date));
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = $result_data->path_sp;
        $filename = is_null($filename) ? "sp_pdln_{$id_surat}_{$update_date}.pdf" : $filename;
        $fullpath = "{$targetPath}{$filename}";
        // READY FOR METADATA
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
        $this->db->select('r_unit_tembusan.Nama');
        $this->db->join('r_unit_tembusan', 'r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
        $this->db->where('TemplateID', $result_data->format_tembusan);
        $unit_tembusan = $this->db->get('t_template_unit_tembusan')->result();
        $this->db->select('t_log_peserta.id_log_peserta,t_log_peserta.start_date,t_log_peserta.end_date,
                           m_pemohon.nama,m_pemohon.jabatan,m_pemohon.nip_nrp,r_institution.Nama as instansi
                           m_pemohon.instansi_lainnya,m_pemohon.id_instansi,t_log_peserta.id_kategori_biaya,t_log_peserta.id_biaya');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi','left');
        $this->db->where('id_pdln', $id_surat);
        $temp_pemohon = $this->db->get('t_log_peserta');
        $list_pemohon = array();
        foreach ($temp_pemohon->result() as $pemohon) {
            $list_pemohon[$pemohon->id_log_peserta] = $pemohon;
            $list_pemohon[$pemohon->id_log_peserta]->pembiayaan = $this->_get_detail_pembiayaan($pemohon->id_kategori_biaya, $pemohon->id_biaya); // Get the categories sub categories
        }
        $this->db->select('m_kegiatan.NamaKegiatan, m_kegiatan.StartDate, m_kegiatan.EndDate, r_negara.nmnegara as Negara');
        $this->db->join('r_negara', 'r_negara.ID = m_kegiatan.negara', 'left');
        $this->db->where('m_kegiatan.ID', $result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
        $this->db->select('*');
        $this->db->where('m_user.UserID', $result_data->penandatangan_persetujuan);
        $penandatangan_persetujuan = $this->db->get('m_user')->row();
        $label_penandatangan = $this->get_label_penandatangan($result_data->penandatangan_persetujuan, $result_data->id_level_pejabat);
        $data = array(
            'title' => "Surat Persetujuan",
            'unit_tembusan' => $unit_tembusan,
            'data_sp' => $result_data,
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan_persetujuan,
            'label_penandatangan' => $label_penandatangan,
            'm_pdf' => $this->load->library('M_pdf'));
        $html = $this->load->view('kotak_surat/v_print_permohonan', $data, TRUE);
        $data = array(
            'title' => "Lampiran SP",
            'data_lampiran' => $result_data,
            'kegiatan' => $kegiatan,
            'list_pemohon' => $list_pemohon,
            'label_penandatangan' => $label_penandatangan,
            'm_pdf' => $this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('kotak_surat/v_print_lampiran_permohonan', $data, TRUE);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        if (isset($result_data->barcode)) {
            $this->m_pdf->pdf->SetHTMLFooter('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
        }
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        if (isset($result_data->barcode)) {
            $this->m_pdf->pdf->SetHTMLFooter('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
        }
        $this->m_pdf->pdf->WriteHTML($html_lampiran);
        setlocale(LC_ALL, 'id_ID');
        $this->m_pdf->debug = true;
        $this->m_pdf->pdf->Output($fullpath, 'F');
        send_file_to_browser($fullpath);
        //$this->m_pdf->pdf->Output($filename,'I');
        return $filename;
    }

    private function _get_detail_pembiayaan($id_karegori_biaya, $id_biaya) {
        if ($id_karegori_biaya == "0") {
            $this->db->select('t_ref_pembiayaan_tunggal.id_log_dana_tunggal,r_institution.Nama');
            $this->db->where('id_log_dana_tunggal', $id_biaya);
            $this->db->join('r_institution', "r_institution.ID = t_ref_pembiayaan_tunggal.id_instansi", "left");
            return $this->db->get('t_ref_pembiayaan_tunggal')->row()->Nama;
        } else if ($id_karegori_biaya == "1") {
            $this->db->select('t_pemb.id_dana_campuran,ref_camp.by,r_jenis_pembiayaan.Description AS jenis_biaya,
                                (CASE WHEN ref_camp.by=1 THEN t_pemb.instansi_gov WHEN ref_camp.by=2 THEN t_pemb.instansi_donor
                                ELSE 0 END) AS id_instansi_pembiayaan', false);
            $this->db->where('t_pemb.id_dana_campuran', $id_biaya);
            $this->db->from('t_pembiayaan_campuran t_pemb');
            $this->db->join('t_ref_pembiayaan_campuran as ref_camp', "t_pemb.id_dana_campuran = ref_camp.id_dana_campuran");
            $this->db->join('r_jenis_pembiayaan', "r_jenis_pembiayaan.ID = ref_camp.id_jenis_biaya");
            $pembiayaan = "";
            foreach ($this->db->get()->result() as $pembiaya) {
                $id_instansi = $pembiaya->id_instansi_pembiayaan;
                if ($id_instansi == 0) {
                    $pembiayaan = $pembiayaan . "- " . $pembiaya->jenis_biaya . " : Perseorangan";
                } else {
                    $pembiayaan = $pembiayaan . "- " . $pembiaya->jenis_biaya . " : " . $this->db->select('*')->where("ID = $id_instansi")->get('r_institution')->row()->Nama . '<br/><br/>';
                }
            }
            return $pembiayaan;
        }
    }

    public function print_permohonan($id_surat) {
        setlocale(LC_ALL, 'id_ID');
        $this->db->select('m_pdln.id_pdln,m_pdln.id_kegiatan,m_pdln.id_level_pejabat,m_pdln.no_sp,
                           m_pdln.tgl_sp,m_pdln.tgl_surat_usulan_pemohon,m_pdln.no_surat_usulan_pemohon,
                           m_pdln.pejabat_sign_sp,m_pdln.path_sp,m_pdln.format_tembusan,m_pdln.penandatangan_persetujuan,
                           m_pdln.barcode,r_kota.nmkota,m_pdln.update_date,m_pdln.create_date');
        $this->db->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan');
        $this->db->join('r_kota', 'r_kota.id = m_kegiatan.Tujuan');
        // $this->db->join('r_kota', 'r_kota.id = m_kegiatan.Kota');
        $this->db->where('m_pdln.id_pdln', $id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        $CI = & get_instance();
        $CI->load->config('pdln');
        $base_path = $CI->config->item('pdln_upload_path');
        // $base_path = $this->config->item('pdln_upload_path','pdln');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = month(date('n', $create_date));
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = $result_data->path_sp;
        $filename = is_null($filename) ? "sp_pdln_{$id_surat}_{$update_date}.pdf" : $filename;
        $fullpath = "{$targetPath}{$filename}";
        // READY FOR METADATA
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
        $this->db->select('r_unit_tembusan.Nama');
        $this->db->join('r_unit_tembusan', 'r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
        $this->db->where('TemplateID', $result_data->format_tembusan);
        $unit_tembusan = $this->db->get('t_template_unit_tembusan')->result();
        $this->db->select('t_log_peserta.id_log_peserta,t_log_peserta.start_date,t_log_peserta.end_date,
                           m_pemohon.nama,m_pemohon.jabatan,m_pemohon.nip_nrp,r_institution.Nama as instansi,
                           m_pemohon.instansi_lainnya,m_pemohon.id_instansi,t_log_peserta.id_kategori_biaya,t_log_peserta.id_biaya');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi');
        $this->db->where('id_pdln', $id_surat);
        $temp_pemohon = $this->db->get('t_log_peserta');
        $list_pemohon = array();
        foreach ($temp_pemohon->result() as $pemohon) {
            $list_pemohon[$pemohon->id_log_peserta] = $pemohon;
            $list_pemohon[$pemohon->id_log_peserta]->pembiayaan = $this->_get_detail_pembiayaan($pemohon->id_kategori_biaya, $pemohon->id_biaya); // Get the categories sub categories
        }
        $this->db->select('m_kegiatan.NamaKegiatan,m_kegiatan.StartDate,m_kegiatan.EndDate,r_negara.nmnegara as Negara');
        $this->db->join('r_negara', 'r_negara.ID = m_kegiatan.negara', 'left');
        $this->db->where('m_kegiatan.ID', $result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
        $this->db->select('*');
        $this->db->where('m_user.UserID', $result_data->penandatangan_persetujuan);
        $penandatangan_persetujuan = $this->db->get('m_user')->row();
        $label_penandatangan = $this->get_label_penandatangan($result_data->penandatangan_persetujuan, $result_data->id_level_pejabat);
        $data = array(
            'title' => "Surat Persetujuan",
            'unit_tembusan' => $unit_tembusan,
            'data_sp' => $result_data,
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan_persetujuan,
            'label_penandatangan' => $label_penandatangan,
            'm_pdf' => $this->load->library('M_pdf'));
        $html = $this->load->view('kotak_surat/v_print_permohonan', $data, TRUE);
        $data = array(
            'title' => "Lampiran SP",
            'data_lampiran' => $result_data,
            'kegiatan' => $kegiatan,
            'list_pemohon' => $list_pemohon,
            'label_penandatangan' => $label_penandatangan,
            'm_pdf' => $this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('kotak_surat/v_print_lampiran_permohonan', $data, TRUE);
        //$filename = 'sp_pdln' . $id_surat . '_' . date('d_m_Y');
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        if (isset($result_data->barcode)) {
            $this->m_pdf->pdf->SetHTMLFooter('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
        }
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        if (isset($result_data->barcode)) {
            $this->m_pdf->pdf->SetHTMLFooter('<barcode code="' . $result_data->barcode . '" type="EAN13" size="0.5" height="1.0" alt= "testing"/>');
        }
        $html_lampiran = mb_convert_encoding($html_lampiran, 'UTF-8', 'UTF-8');
        $this->m_pdf->pdf->WriteHTML($html_lampiran);
        //$this->m_pdf->pdf->Output($filename . '.pdf', 'I');
        $this->m_pdf->pdf->Output($fullpath, 'F');
        // update sp_path on table m_pdln
        $this->db->reset_query();
        $this->db->set('path_sp', $filename);
        $this->db->where('id_pdln', $id_surat);
        if (! $this->db->update('m_pdln'))
        {
            // if false
            die('Gagal mengupdate data path_sp, silahkan hubungi Administrator.');
        }
        send_file_to_browser($fullpath);
    }

    public function print_perpanjangan($id_surat) {
        setlocale(LC_ALL, 'id_ID');
        $this->db->select('m_pdln.id_pdln, m_pdln.id_kegiatan, m_pdln.no_sp, m_pdln.tgl_sp, m_pdln.tgl_surat_usulan_fp,
                           m_pdln.no_surat_usulan_fp, m_pdln.pejabat_sign_sp, m_pdln.format_tembusan,
                           m_pdln.id_pdln_lama,m_pdln.create_date,m_pdln.update_date');
        $this->db->where('m_pdln.id_pdln', $id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        // $base_path = $this->config->item('pdln_upload_path');
        $CI = & get_instance();
        $CI->load->config('pdln');
        $base_path = $CI->config->item('pdln_upload_path');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = month(date('n', $create_date));
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = $result_data->path_sp;
        $filename = is_null($filename) ? "sp_pdln_{$id_surat}_{$update_date}.pdf" : $filename;
        $fullpath = "{$targetPath}{$filename}";
        // READY FOR METADATA
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
        $this->db->select('r_unit_tembusan.Nama');
        $this->db->join('r_unit_tembusan', 'r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
        $this->db->where('TemplateID', $result_data->format_tembusan);
        $unit_tembusan = $this->db->get('t_template_unit_tembusan')->result();
        $this->db->select('t_log_peserta.id_log_peserta,t_log_peserta.start_date,t_log_peserta.end_date,m_pemohon.nama,m_pemohon.jabatan,m_pemohon.nip_nrp,
                            r_institution.Nama as instansi,m_pemohon.instansi_lainnya,m_pemohon.id_instansi,t_log_peserta.id_kategori_biaya,t_log_peserta.id_biaya');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi');
        $this->db->where('id_pdln', $id_surat);
        $temp_pemohon = $this->db->get('t_log_peserta');
        $list_pemohon = array();
        foreach ($temp_pemohon->result() as $pemohon) {
            $list_pemohon[$pemohon->id_log_peserta] = $pemohon;
            $list_pemohon[$pemohon->id_log_peserta]->pembiayaan = $this->_get_detail_pembiayaan($pemohon->id_kategori_biaya, $pemohon->id_biaya); // Get the categories sub categories
        }
        $this->db->select('m_kegiatan.NamaKegiatan,m_kegiatan.StartDate,m_kegiatan.EndDate,r_negara.nmnegara as Negara');
        $this->db->join('r_negara', 'r_negara.ID = m_kegiatan.negara', 'left');
        $this->db->where('m_kegiatan.ID', $result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
        $this->db->select('m_pdln.no_sp, m_pdln.tgl_sp');
        $this->db->where('m_pdln.id_pdln', $result_data->id_pdln_lama);
        $pdln_lama = $this->db->get('m_pdln')->row();
        $data = array(
            'title' => "Surat Persetujuan",
            'unit_tembusan' => $unit_tembusan,
            'data_sp' => $result_data,
            'kegiatan' => $kegiatan,
            'data_sp_lama' => $pdln_lama,
            'm_pdf' => $this->load->library('M_pdf'));

        $html = $this->load->view('kotak_surat/v_print_perpanjangan', $data, TRUE);
        $data = array(
            'title' => "Lampiran SP",
            'data_lampiran' => $result_data,
            'list_pemohon' => $list_pemohon,
            'm_pdf' => $this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('kotak_surat/v_print_lampiran_perpanjangan', $data, TRUE);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html_lampiran);
        //$filename = 'resi_sm_' . $id_surat . '_' . date('d_m_Y');
        $this->m_pdf->pdf->Output($fullpath, 'F');
        send_file_to_browser($fullpath); // this function will exec die() and exit
    }

    public function print_ralat($id_surat) {
        setlocale(LC_ALL, 'id_ID');
        $this->db->select(' m_pdln.id_pdln, m_pdln.id_kegiatan, m_pdln.no_sp, m_pdln.tgl_sp, m_pdln.tgl_surat_usulan_fp,
                            m_pdln.no_surat_usulan_fp, m_pdln.pejabat_sign_sp, m_pdln.format_tembusan, m_pdln.id_pdln_lama,
                            m_pdln.update_date,m_pdln.create_date');
        $this->db->where('m_pdln.id_pdln', $id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        // $base_path = $this->config->item('pdln_upload_path');
        $CI = & get_instance();
        $CI->load->config('pdln');
        $base_path = $CI->config->item('pdln_upload_path');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = date('m', $create_date);
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = $result_data->path_sp;
        $filename = is_null($filename) ? "sp_pdln_{$id_surat}_{$update_date}.pdf" : $filename;
        $fullpath = "{$targetPath}{$filename}";
        // READY FOR METADATA
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
        $this->db->select('r_unit_tembusan.Nama');
        $this->db->join('r_unit_tembusan', 'r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
        $this->db->where('TemplateID', $result_data->format_tembusan);
        $unit_tembusan = $this->db->get('t_template_unit_tembusan')->result();
        $this->db->select('t_log_peserta.id_log_peserta,m_pemohon.nama,m_pemohon.jabatan');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon');
        $this->db->where('id_pdln', $id_surat);
        $list_pemohon = $this->db->get('t_log_peserta')->result();
        $this->db->select('m_kegiatan.NamaKegiatan');
        $this->db->where('m_kegiatan.ID', $result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
        $this->db->select('m_pdln.no_sp, m_pdln.tgl_sp');
        $this->db->where('m_pdln.id_pdln', $result_data->id_pdln_lama);
        $pdln_lama = $this->db->get('m_pdln')->row();
        $data = array(
            'title' => "Surat Persetujuan",
            'unit_tembusan' => $unit_tembusan,
            'data_sp' => $result_data,
            'kegiatan' => $kegiatan,
            'data_sp_lama' => $pdln_lama,
            'm_pdf' => $this->load->library('M_pdf'));
        $html = $this->load->view('kotak_surat/v_print_ralat', $data, TRUE);
        $data = array(
            'title' => "Lampiran SP",
            'data_lampiran' => $result_data,
            'list_pemohon' => $list_pemohon,
            'm_pdf' => $this->load->library('M_pdf'));
        $html_lampiran = $this->load->view('kotak_surat/v_print_lampiran_ralat', $data, TRUE);
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html_lampiran);
        $this->m_pdf->pdf->Output($fullpath, 'F');
        send_file_to_browser($fullpath); // this function will exec die() and exit
    }

    public function print_pembatalan($id_surat) {
        setlocale(LC_ALL, 'id_ID');
        $this->db->select('m_pdln.id_pdln, m_pdln.id_kegiatan, m_pdln.no_sp, m_pdln.tgl_sp, m_pdln.tgl_surat_usulan_fp,
                           m_pdln.no_surat_usulan_fp, m_pdln.pejabat_sign_sp, m_pdln.format_tembusan, m_pdln.id_pdln_lama,
                           m_pdln.create_date, m_pdln.update_date');
        $this->db->where('m_pdln.id_pdln', $id_surat);
        $result_data = $this->db->get('m_pdln')->row();
        // ----------------------------------------------------------------------
        // checking existing file
        // ----------------------------------------------------------------------
        // $base_path = $this->config->item('pdln_upload_path');
        $CI = & get_instance();
        $CI->load->config('pdln');
        $base_path = $CI->config->item('pdln_upload_path');
        $update_date = $result_data->update_date;
        $create_date = $result_data->create_date;
        $month = month(date('n', $create_date));
        $year = date('Y', $create_date);
        $additional_path = $year . '/' . $month . '/pdln/' . $id_surat . "/";
        $targetPath = $base_path . $additional_path;
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, TRUE);
        }
        $filename = $result_data->path_sp;
        $filename = is_null($filename) ? "sp_pdln_{$id_surat}_{$update_date}.pdf" : $filename;
        $fullpath = "{$targetPath}{$filename}";
        // READY FOR METADATA
        if (file_exists($fullpath)) {
            send_file_to_browser($fullpath); // this function will exec die() and exit
        }
        // ----------------------------------------------------------------------
        $this->db->select('r_unit_tembusan.Nama');
        $this->db->join('r_unit_tembusan', 'r_unit_tembusan.ID = t_template_unit_tembusan.UnitID');
        $this->db->where('TemplateID', $result_data->format_tembusan);
        $unit_tembusan = $this->db->get('t_template_unit_tembusan')->result();
        $this->db->select('t_log_peserta.id_log_peserta,m_pemohon.nama,m_pemohon.jabatan');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon');
        $this->db->where('id_pdln', $id_surat);
        $list_pemohon = $this->db->get('t_log_peserta')->result();
        $this->db->select('m_kegiatan.NamaKegiatan');
        $this->db->where('m_kegiatan.ID', $result_data->id_kegiatan);
        $kegiatan = $this->db->get('m_kegiatan')->row();
        $this->db->select('m_pdln.no_sp, m_pdln.tgl_sp');
        $this->db->where('m_pdln.id_pdln', $result_data->id_pdln_lama);
        $pdln_lama = $this->db->get('m_pdln')->row();
        $data = array(
            'title' => "Surat Persetujuan",
            'unit_tembusan' => $unit_tembusan,
            'data_sp' => $result_data,
            'kegiatan' => $kegiatan,
            'data_sp_lama' => $pdln_lama,
            'm_pdf' => $this->load->library('M_pdf'));
        $html = $this->load->view('kotak_surat/v_print_pembatalan', $data, TRUE);
        /*
          $data = array(
          'title' => "Lampiran SP",
          'data_lampiran' => $result_data,
          'list_pemohon' => $list_pemohon,
          'm_pdf' => $this->load->library('M_pdf'));
          $html_lampiran = $this->load->view('kotak_surat/v_print_lampiran_pembatalan', $data, TRUE);
         */
        $this->load->library('M_pdf');
        $this->m_pdf->pdf->AddPage('P', // L - landscape, P - portrait
                '', '', '', '', 15, // margin_left
                15, // margin right
                15, // margin top
                10, // margin bottom
                18, // margin header
                5); // margin footer
        $this->m_pdf->pdf->WriteHTML($html);
        /*
          $this->m_pdf->pdf->AddPage('L', // L - landscape, P - portrait
          '', '', '', '', 15, // margin_left
          15, // margin right
          15, // margin top
          10, // margin bottom
          18, // margin header
          5); // margin footer
          $this->m_pdf->pdf->WriteHTML($html_lampiran);
         */
        $this->m_pdf->pdf->Output($fullpath, 'F');
        send_file_to_browser($fullpath); // this function will exec die() and exit
    }

    public function get_list_peserta() {
        $id_pdln = $this->uri->segment(4);
        $this->crud_ajax->init('t_log_peserta', 'id_log_peserta', NULL);
        $join = array(
            'm_pemohon' => array('m_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'left'),
            'r_institution' => array('m_pemohon.id_instansi = r_institution.ID', 'left'),
        );
        $this->crud_ajax->setJoinField($join);
        $this->crud_ajax->set_select_field('id_log_peserta,m_pemohon.id_pemohon,id_kategori_biaya,id_biaya,nik,nip_nrp,m_pemohon.nama nama_peserta,jabatan,start_date,end_date,r_institution.Nama,m_pemohon.id_instansi,instansi_lainnya');
        $where_data = array(
            'id_pdln' => $id_pdln
        );
        $this->crud_ajax->setExtraWhere($where_data);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $peserta) {
            $row = array();
            $row[] = $peserta->id_log_peserta;
            $row[] = ++$no . '.';
            $row[] = $peserta->nip_nrp;
            $row[] = $peserta->nik;
            $row[] = $peserta->nama_peserta;
            $row[] = ucwords($peserta->jabatan);
            $row[] = (empty($peserta->start_date) || empty($peserta->end_date)) ? '' : day(date("Y-m-d", $peserta->start_date)) . ' s.d ' . day(date("Y-m-d", $peserta->end_date));
            $row[] = ($peserta->id_kategori_biaya == 1) ? "Campuran" : "Tunggal";
            $row[] = ((empty($peserta->id_instansi)) OR ($peserta->id_instansi) == 0 ) ? $peserta->instansi_lainnya : $peserta->Nama;
            $id_peserta = $peserta->id_log_peserta;
            $result = $this->db->get_where('view_biaya_log_peserta', array("id_log_peserta" => $id_peserta));
            if ($result->num_rows() > 0) {
                $biaya;
                foreach ($result->result() as $value) {
                    $biaya = $value->biaya;
                }
                $row[] = (empty($biaya)) ? '' : 'Rp. ' . number_format(intval($biaya));
            } else {
                $row[] = '';
            }
            if(null!==($this->uri->segment(5))){
                $row[] = '<a onClick="view_peserta('.$id_peserta.');return false;" id="v'.$no.'" title="View" class="btn btn-xs view_peserta"><i class="fa fa-search"></i>&nbsp; View </button>';
            }else{
                $row[] = '<button type="button" id="edit_peserta" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i>&nbsp; Edit </button>
                        <button type="button" id="remove_peserta" title="Hapus" class="btn btn-xs red"><i class="fa fa-remove"></i>&nbsp; Hapus</button>';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : NULL),
            "recordsTotal" => $this->crud_ajax->count_filtered(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
            "query" => $this->db->last_query()
        );
        echo json_encode($output);
    }

    public function get_label_penandatangan($id_user, $level_pejabat) {
        $data_user_plh = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->is_plh;
        $jenis_plh = '';
        if ($data_user_plh) {
            $sekarang = date("Y-m-d");
            $this->db->select('jenis_plh,start_date,end_date');
            $this->db->where('id_user_plh', $id_user);
            $this->db->limit(1);
            $this->db->order_by('id_plh', 'desc');
            $jenis = $this->db->get('t_log_plh')->row();
            $str_date = strtotime($jenis->start_date);
            $end_date = strtotime($jenis->end_date);
            $now_date = strtotime($sekarang);
            if (isset($jenis->jenis_plh) ) {
                $jenis_plh = $jenis->jenis_plh;
            }
        }
        $label = "";
        if ($level_pejabat == 4) {
            if($jenis_plh == LEVEL_SESMEN){
                 $label = "Plh. Sekretaris Kementerian Sekretariat Negara";
            } else{
                $label = "   Sekretaris Kementerian Sekretariat Negara";
            }
        } else if ($level_pejabat == 5) {

            if ($jenis_plh == LEVEL_KARO) {
                $label = "a.n. Sekretaris Kementerian Sekretariat Negara <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plh. Kepala Biro Kerja Sama Teknik Luar Negeri";
            }else{
                $label = "a.n.  Sekretaris Kementerian Sekretariat Negara <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepala Biro Kerja Sama Teknik Luar Negeri";
            }
        } else {
            if($jenis_plh == LEVEL_SESMEN){
                 $label = "a.n. Menteri Sekretaris Negara <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plh. Sekretaris Kementerian Sekretariat Negara";
            }else{
                $label = "a.n. Menteri Sekretaris Negara <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sekretaris Kementerian Sekretariat Negara";
            }
        }

        return $label;
    }

    public function get_detail_tembusan() {
        $template_tembusan = $this->input->post('template_tembusan');
        if ($template_tembusan === "0" || !isset($template_tembusan)) {
            $data['status'] = FALSE;
            $data['message'] = "Silahkan pilih salah satu format tembusan terlebih dahulu";
            echo json_encode($data);
            exit;
        } else {
            $data['status'] = TRUE;
            $data['nama_format'] = $this->db->where('ID', $template_tembusan)->get('r_template_tembusan')->row()->Nama;
            $data['message'] = "";
            $this->db->select('*');
            $this->db->from('t_template_unit_tembusan');
            $this->db->where('TemplateID', $template_tembusan);
            $query = $this->db->get();
            $no = 1;
            foreach ($query->result() as $row) {
                $data['message'] = $data['message'] . "<br/>" . $no . ". " . $this->db->where('ID', $row->UnitID)->get('r_unit_tembusan')->row()->Nama;
                $no++;
            }
            $data['message'] = $data['message'] . "<br/>" . $no . ". Yang Bersangkutan";
            echo json_encode($data);
        }
    }

    public function send_email($data){
        $this->config->load('email', TRUE);
        $from=isset($data['from'])?$data['from']:$this->config->item('email_from', 'email');
        $reply=isset($data['reply'])?$data['reply']:$this->config->item('email_reply', 'email');
        $to=isset($data['to'])?$data['to']:'';
        $subject==isset($data['subject'])?$data['subject']:'';
        $body=isset($data['body'])?$data['body']:'';
        $this->load->library('email');
        $result = $this->email
                        ->from($from)
                        ->reply_to($reply)    // Optional, an account where a human being reads.
                        ->to($to)
                        ->subject($subject)
                        ->message($body)
                        ->send();
        return $result;
    }

    public function download($id_pdln)
    {
        $data_pdln = $this->db->get_where('m_pdln', array('id_pdln' => $id_pdln))->row();
        //$idSigned = $data_pdln->id_signed;
        $filename = get_file_pdln1('sp', date('Y-m-d', $data_pdln->create_date), $data_pdln->id_pdln, $data_pdln->id_signed . '.pdf'); 
        send_file_to_browser($filename);
    }
    // public function download($id_pdln) {
    //     $data_pdln = $this->db->get_where('m_pdln', array('id_pdln' => $id_pdln))->row();
    //     $idSigned = $data_pdln->id_signed;
    //     $this->config->load('esign', TRUE);
    //     $this->load->library('Esign', $this->config->item('esign'));
    //     $clientId = $this->config->item('clientId', 'esign');
    //     $clientSecret = $this->config->item('clientSecret', 'esign');
    //     $username = $this->config->item('userIdMenteri', 'esign');
    //     $password = $this->config->item('passwordMenteri', 'esign');
    //     $authEntity = new AuthRequestEntity();
    //     $authEntity->client_id = $clientId;
    //     $authEntity->client_secret = $clientSecret;
    //     $authEntity->grant_type = Esign::GRANT_TYPE_PASSWORD;
    //     $authEntity->username = $username;
    //     $authEntity->password = $password;
    //     $authResponse = json_decode($this->esign->GetUserAccessToken($authEntity), true);
    //     if (array_key_exists('access_token', $authResponse)) {
    //         $accessToken = $authResponse['access_token'];
    //         $response = $this->esign->DownloadSignedDoc($idSigned, $accessToken);
    //         $this->output
    //                 ->set_status_header(200)
    //                 ->set_content_type("application/pdf")
    //                 //->set_header("Content-Disposition:attachment;filename='SP_{$idSigned}.pdf'")
    //                 ->set_output($response);
    //         return;
    //     }
    //     $this->output->set_status_header(204);
    // }

    public function get_list_peserta_view() {
        $id_pdln = $this->uri->segment(4);
        $this->crud_ajax->init('t_log_peserta', 'id_log_peserta', NULL);
        $join = array(
            'm_pemohon' => array('m_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'left'),
            'r_institution' => array('m_pemohon.id_instansi = r_institution.ID', 'left'),
        );
        $this->crud_ajax->setJoinField($join);
        $this->crud_ajax->set_select_field('id_log_peserta,m_pemohon.id_pemohon,id_kategori_biaya,id_biaya,nik,nip_nrp,m_pemohon.nama nama_peserta,jabatan,start_date,end_date,r_institution.Nama,m_pemohon.id_instansi,instansi_lainnya');
        $where_data = array(
            'id_pdln' => $id_pdln
        );
        $this->crud_ajax->setExtraWhere($where_data);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = (isset($_POST['start'])) ? $_POST['start']:0;
        foreach ($list as $peserta) {
            $row = array();
            $row[] = $peserta->id_log_peserta;
            $row[] = ++$no . '.';
            $row[] = $peserta->nip_nrp;
            $row[] = $peserta->nik;
            $row[] = $peserta->nama_peserta;
            $row[] = ucwords($peserta->jabatan);
            $row[] = (empty($peserta->start_date) || empty($peserta->end_date)) ? '' : day(date("Y-m-d", $peserta->start_date)) . ' s.d ' . day(date("Y-m-d", $peserta->end_date));
            $row[] = ($peserta->id_kategori_biaya == 1) ? "Campuran" : "Tunggal";
            $row[] = ((empty($peserta->id_instansi)) OR ($peserta->id_instansi) == 0 ) ? $peserta->instansi_lainnya : $peserta->Nama;
            $id_peserta = $peserta->id_log_peserta;
            $result = $this->db->get_where('view_biaya_log_peserta', array("id_log_peserta" => $id_peserta));
            if ($result->num_rows() > 0) {
                foreach ($result->result() as $value)  $biaya = $value->biaya;
                $row[] = (empty($biaya)) ? '' : 'Rp. ' . number_format(intval($biaya));
            } else {
                $row[] = '';
            }
            $row[] = '<a onClick="view_peserta('.$id_peserta.');return false;" id="v'.$no.'" title="View" class="btn btn-xs view_peserta"><i class="fa fa-search"></i>&nbsp; View </button>';
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : NULL),
            "recordsTotal" => $this->crud_ajax->count_filtered(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
            "query" => $this->db->last_query()
        );
        echo json_encode($output);
    }
    public function get_data_peserta()
    {
        $id_log_peserta = $this->input->post('id_log_peserta');
        $this->db->select("
                            a.id_log_peserta,
                            b.id_pemohon,
                            a.id_kategori_biaya,
                            a.id_biaya,
                            b.nik,
                            b.paspor,
                            b.nip_nrp,
                            b.nama AS nama_peserta,
                            b.jabatan,
                            CONCAT(DATE_FORMAT(FROM_UNIXTIME(a.start_date),'%d-%m-%Y'),' s.d. ',DATE_FORMAT(FROM_UNIXTIME(a.end_date),'%d-%m-%Y')) AS tgl,
                            c.Nama,
                            b.id_instansi,
                            b.instansi_lainnya
                         ")
            ->from('t_log_peserta a')
            ->join('m_pemohon b','b.id_pemohon = a.id_pemohon', 'left')
            ->join('r_institution c','b.id_instansi = c.ID', 'left')
            ->where(array('a.id_log_peserta' => $id_log_peserta));
        $result=$this->db->get()->row();
        $data['peserta'] = $result;
        if($data['peserta']->id_kategori_biaya == "0"){
            $this->crud_ajax->init('t_ref_pembiayaan_tunggal', 'id_log_dana_tunggal', NULL);
            $this->crud_ajax->setExtraWhere(array('id_log_dana_tunggal' => $data['peserta']->id_biaya));
            $biaya = $this->crud_ajax->get_data();
            $data['biaya'] = number_format($biaya[0]->biaya,2,'.',',');
        }else if($data['peserta']->id_kategori_biaya == "1"){
            $this->crud_ajax->init('t_pembiayaan_campuran', 'id_dana_campuran', NULL);
            $this->crud_ajax->setExtraWhere(array('id_dana_campuran' => $data['peserta']->id_biaya));
            $biaya = $this->crud_ajax->get_data();
            $data['biaya'] =  number_format($biaya[0]->biaya_apbn,2,'.',',');
        }
        echo json_encode($data);
	}
}
