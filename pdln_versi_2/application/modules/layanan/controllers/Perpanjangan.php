<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Perpanjangan extends CI_Controller {

    function __construct() {
        parent ::__construct();
    }

    public function index() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_perpanjangan';
        $data['title'] = 'Perpanjangan/Ralat';
        $data['title_page'] = 'Perpanjangan/Ralat';
        $data['breadcrumb'] = 'Perpanjangan/Ralat';
        page_render($data);
    }

    public function perpanjangan_list() {
        $this->crud_ajax->init('m_pdln', 'id_pdln',  array('tgl_surat_usulan_pemohon' => 'asc'));

        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
        $where = "";
        if ($level == LEVEL_PEMOHON) {
            $where = array('unit_pemohon'=> $this->session->user_id,'status' => '1'
                    );
            $status_pdln = 1;

        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('unit_fp'=> $this->session->user_id,'status' => '2'
                    );

            $status_pdln = 2;
        }
        $this->db->where_in('jenis_permohonan', array('20','30'));
        $this->crud_ajax->setExtraWhere($where);
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
            $row[] = $no . '.';
            if ($this->ion_auth->is_allowed(26, 'update')) {
                if ($pdln->status == $status_pdln  && $pdln->is_draft > 0) {
                    $row[] = '<a href="'.base_url().'kotak_surat/modify/edit_wizard/'.$pdln->id_pdln.'" >'.'<button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button> </a>';
                }else{
                    $row[] = '<a href="'.base_url().'layanan/perpanjangan/view_perpanjang_ralat/'.$pdln->id_pdln.'" >'.'<button class="btn btn-sm green btn-outline filter-submit margin-bottom"><i class="fa fa-search"></i> View</button> </a>';
                }
            } else {
                $row[] = '';
            }
            $no_register = $pdln->no_register;
            $row[] = (empty($no_register)) ? '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day_dashboard($pdln->tgl_register);
            $row[] = $pdln->no_surat_usulan_fp;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = '<span class="label label-' . setLabelPdln($pdln->status) . '">' . setStatusPdln($pdln->status) . '</span>';
            

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


    public function view_perpanjang_ralat($id_pdln) {
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
        $data['title'] = 'Form Perpanjangan / Ralat';
        $data['title_page'] = 'Form Perpanjangan / Ralat';
        $data['breadcrumb'] = 'Form Perpanjangan / Ralat';
        page_render($data);
    }

    public function persetujuan_list()
    {

        $data['theme'] = 'pdln';
        $data['page'] = 'v_disetujui_batal';
        $data['title'] = 'Perpanjangan/Ralat';
        $data['title_page'] = 'Perpanjangan/Ralat';
        $data['breadcrumb'] = 'Perpanjangan/Ralat';
        page_render($data);
    }
    
    public function disetujui_list() {
        $this->crud_ajax->init('view_monitoring_pdln', 'id_pdln',  array('tgl_surat_usulan_pemohon' => 'asc'));

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
        $this->db->where_in('status', array(11,13,14));
        // kalo 11 muncul semua 
        // kalo 20, 20 , muncul semua

        $this->crud_ajax->setExtraWhere($where);
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
            $row[] = $no . '.';
            $no_register = $pdln->no_register;
            $row[] = (empty($no_register)) ? '' : str_pad($no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day_dashboard($pdln->tgl_register);
            $row[] = $pdln->no_surat_fp;
            $row[] =  $pdln->nama_jenis_kegiatan;
            $row[] = '<span class="label label-' . setLabel($pdln->jenis_permohonan) . '">' . setJenisPermohonan($pdln->jenis_permohonan) . '</span>';
            $row[] = '<span class="label label-' . setLabelPdln($pdln->status) . '">' . setStatusPdln($pdln->status) . '</span>';
            if ($this->ion_auth->is_allowed(26, 'update')) {
                $row[] = '<button type="button" id="form_renew" title="Perpanjangan" class="btn btn-xs yellow"><i class="fa fa-edit"></i>Perpanjangan</button>';

                $row[] = '<button type="button" id="form_renew" title="Ralat" class="btn btn-xs purple"><i class="fa fa-edit"></i>Ralat</button>';
            } else {
                $row[] = '';
            }

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

    public function add() {

        $data['theme'] = 'pdln';
        $data['page'] = 'v_perpanjangan_baru';
        $data['title'] = 'Tambah Perpanjangan/Ralat Baru';
        $data['title_page'] = 'Tambah Perpanjangan/Ralat Baru';
        $data['breadcrumb'] = 'Tambah Perpanjangan/Ralat Baru';

        $where = array('Status' => '1');

        $this->crud_ajax->init('r_level_pejabat', 'id', NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['level_pejabat'] = $this->crud_ajax->get_data();

        $this->crud_ajax->init('r_negara', 'id', NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['negara'] = $this->crud_ajax->get_data();

        $this->crud_ajax->init('r_jenis_pembiayaan', 'ID', NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['jenis_pembiayaan'] = $this->crud_ajax->get_data();

        $this->crud_ajax->init('r_institution', 'ID', NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_instansi'] = $this->crud_ajax->get_data();

        $where['SubKategori'] = 2;
        $this->crud_ajax->init('r_jenis_kegiatan', 'ID', NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['jenis_kegiatan'] = $this->crud_ajax->get_data();

        if (!is_pemohon($this->session->userdata('user_id'))) {//Jika user login adalah FP
            $this->get_list_pemohon();
            $data['list_pemohon'] = $this->get_list_pemohon();
        }

        page_render($data);
    }

    public function list_pemohon() {
        $this->crud_ajax->init('view_list_pemohon', 'id_pemohon', NULL);
        $where = array('status' => 1);
        $this->crud_ajax->setExtraWhere($where);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;

        foreach ($list as $pemohon) {
            $no++;
            $row = array();
            $row[] = $pemohon->id_pemohon;
            $row[] = $no . '.';
            $row[] = $pemohon->nip_nrp;
            $row[] = $pemohon->nik;
            $row[] = $pemohon->nama;
            $row[] = $pemohon->jabatan;
            $row[] = $pemohon->instansi;
            $row[] = $pemohon->jenis_peserta;
            // $row[] = '<span class="label label-'.setLabelStatus($pemohon->status).'">'.setStatus($pemohon->status).'</span>';
            $row[] = '<button type="button" id="btn_set_peserta" title="Pilih" class="btn btn-xs blue-chambray"><i class="fa fa-share-square-o"></i> </button>';
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function list_permohonan() {

        $q = $this->db->select("id_pdln, no_register, tgl_register, no_sp, tgl_sp, k.NamaKegiatan, n.nmnegara, m.status ")
                ->from("m_pdln m")
                ->join("m_kegiatan k", "k.ID = m.id_kegiatan", "left")
                ->join("r_negara n", "n.ID = k.Negara", "left")
                ->where("m.status", "11")
                ->where("m.jenis_permohonan <> ", "40")
                ->get();

        $list = $q->result();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;

        foreach ($list as $pemohon) {
            $no++;
            $row = array();
            $row[] = $pemohon->id_pdln;
            $row[] = $no . '.';
            $row[] = (empty($pemohon->no_register)) ? '' : str_pad($pemohon->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day(date("Y-m-d", $pemohon->tgl_register));
            $row[] = $pemohon->no_sp;
            $row[] = day(date("Y-m-d", $pemohon->tgl_sp));
            $row[] = $pemohon->NamaKegiatan;
            $row[] = $pemohon->nmnegara;
            $row[] = setStatusPdln($pemohon->status);
            // $row[] = '<span class="label label-'.setLabelStatus($pemohon->status).'">'.setStatus($pemohon->status).'</span>';
            $row[] = '<button type="button" id="btn_set_permohonan" title="Pilih" class="btn btn-xs blue-chambray"><i class="fa fa-share-square-o"></i> </button>';
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $q->num_rows(),
            "recordsFiltered" => $q->num_rows(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function get_data_permohonan() {
        $response = array();
        $response['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');
        $this->db->where('id_pdln', $id_pdln);

        $row = $this->db->get('m_pdln')->row();

        if (!empty($row)) {
            $date_created = date("Y-m-d", $row->create_date);
            $file_pemohon = $row->path_file_sp_pemohon;
            $file_fp = $row->path_file_sp_fp;

            $response['status'] = TRUE;

            $path_pemohon = get_file_pdln("pdln", $date_created, $id_pdln, $file_pemohon);
            $path_fp = get_file_pdln("pdln", $date_created, $id_pdln, $file_fp);
            $response['no_register'] = (empty($row->no_register)) ? '' : str_pad($row->no_register, 8, '0', STR_PAD_LEFT);
            $response['no_surat_usulan_pemohon'] = $row->no_surat_usulan_pemohon;
            $response['tgl_surat_usulan_pemohon'] = $row->tgl_surat_usulan_pemohon;
            $response['no_surat_usulan_fp'] = $row->no_surat_usulan_fp;
            $response['tgl_surat_usulan_fp'] = $row->tgl_surat_usulan_fp;
            $response['pejabat_sign_sp'] = $row->pejabat_sign_sp;
            $response['path_file_sp_fp'] = $path_fp;
            $response['id_level_pejabat'] = $row->id_level_pejabat;

            $id_kegiatan = $row->id_kegiatan;
            $jenis_kegiatan;
            if (!empty($id_kegiatan)) {
                $result_keg = $this->db->get_where('m_kegiatan', array('ID' => $id_kegiatan));
                if ($result_keg->num_rows() > 0)
                    $jenis_kegiatan = $result_keg->row()->JenisKegiatan;
            }
            $response['jenis_kegiatan'] = (empty($jenis_kegiatan)) ? '' : $jenis_kegiatan;
            $response['kegiatan'] = (empty($id_kegiatan)) ? '' : $id_kegiatan;
        }

        echo json_encode($response);
    }

    public function get_parent_id() {
        $this->db->where('UserID', $this->session->userdata('user_id'));
        $parent = $this->db->get('m_user')->row()->unitkerja;
        return $parent; //unitkerja user fp sbg parent
    }

    public function get_list_pemohon() {
        $this->db->where('m_unit.Parent', $this->get_parent_id());
        $this->db->where('m_unit.Status', '1');
        $this->db->where_in('m_unit.Group', array(1, 2)); //Pemohon
        $this->db->from('m_unit_kerja_institusi as m_unit');
        $this->db->join('m_user as mu', 'm_unit.ID = mu.unitkerja', 'left');

        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result();
        return FALSE;
    }

    public function get_data_pemohon() {
        $response = array();
        $response['status'] = TRUE;
        $id_pemohon = $this->input->post('id_pemohon');
        $this->crud_ajax->init('view_list_pemohon', 'id_pemohon', NULL);
        $where = array('id_pemohon' => $id_pemohon);
        $this->crud_ajax->setExtraWhere($where);
        $result = $this->crud_ajax->get_data();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $response['jenis_peserta'] = $row->jenis_peserta;
                $response['nip_peserta'] = $row->nip_nrp;
                $response['nik_peserta'] = $row->nik;
                $response['nama_peserta'] = $row->nama;
                $response['jabatan_peserta'] = $row->jabatan;
                $response['instansi'] = $row->instansi;
                $response['email_peserta'] = $row->email;
                $response['telp_peserta'] = $row->telp;
            }
        } else {
            $response['status'] = FALSE;
            $response['msg'] = "Error Getting Data";
        }
        echo json_encode($response);
    }

    private function copy_file_kegiatan($id_pdln_lama, $id_pdln) {

        $this->db->select('create_date');
        $this->db->from('m_pdln');
        $this->db->where('id_pdln', $id_pdln_lama);
        $create_pdln_date_lama = $this->db->get()->row()->create_date;

        $this->db->select('create_date');
        $this->db->from('m_pdln');
        $this->db->where('id_pdln', $id_pdln);
        $create_pdln_date_baru = $this->db->get()->row()->create_date;

        $this->db->select('*');
        $this->db->from('m_dok_pdln');
        $this->db->where('id_pdln', $id_pdln_lama);
        $this->db->where('kategori_doc', 1); // file kegiatan
        $files = $this->db->get()->result();

        foreach ($files as $file) {
            $parts = explode('_', $file->dir_path);
            $needle = $id_pdln_lama . '_';
            $pos = strpos($file->dir_path, $needle);
            if ($pos !== false) {
                $new_name = substr_replace($file->dir_path, '', $pos, strlen($needle));
            }

            $dir_path = $id_pdln . '_' . $new_name;
            $data_save = array(
                'id_pdln' => $id_pdln,
                'dir_path' => $dir_path,
                'id_jenis_doc' => $file->id_jenis_doc,
                'kategori_doc' => $file->kategori_doc,
                'create_date' => strtotime(date("Y-m-d H:i:s")),
                'update_date' => strtotime(date("Y-m-d H:i:s")),
                'author' => $this->session->user_id,
                'is_final' => 1
            );

            $this->crud_ajax->init('m_dok_pdln', 'id_dok_pdln', NULL);
            $insert_id = $this->crud_ajax->save($data_save);
            if (!empty($insert_id)) {
                $old_file = get_file_pdln1("kegiatan", date("Y-m-d", $create_pdln_date_lama), $id_pdln_lama, $file->dir_path);
                $new_file = get_file_pdln1("kegiatan", date("Y-m-d", $create_pdln_date_baru), $id_pdln, $dir_path);

                copy_file_pdln($old_file, $new_file);
            }
        }
    }

    private function copy_file_peserta($id_pdln_lama, $id_peserta_lama, $id_pdln, $id_peserta) {

        $this->db->select('create_date');
        $this->db->from('m_pdln');
        $this->db->where('id_pdln', $id_pdln_lama);
        $create_pdln_date_lama = $this->db->get()->row()->create_date;

        $this->db->select('create_date');
        $this->db->from('m_pdln');
        $this->db->where('id_pdln', $id_pdln);
        $create_pdln_date_baru = $this->db->get()->row()->create_date;

        $pattern = '%' . $id_pdln_lama . '_' . $id_peserta_lama . '_%';
        $this->db->select('*');
        $this->db->from('m_dok_pdln');
        $this->db->where('id_pdln', $id_pdln_lama);
        $this->db->where('kategori_doc', 2); // file peserta
        $this->db->like('dir_path', $pattern, 'none', FALSE);
        $files = $this->db->get()->result();

        foreach ($files as $file) {
            $parts = explode('_', $file->dir_path);
            $needle = $id_pdln_lama . '_' . $id_peserta_lama;
            $pos = strpos($file->dir_path, $needle);
            if ($pos !== false) {
                $new_name = substr_replace($file->dir_path, '', $pos, strlen($needle));
            }

            $dir_path = $id_pdln . '_' . $id_peserta . '_' . $new_name;

            $data_save = array(
                'id_pdln' => $id_pdln,
                'dir_path' => $dir_path,
                'id_jenis_doc' => $file->id_jenis_doc,
                'kategori_doc' => $file->kategori_doc,
                'create_date' => strtotime(date("Y-m-d H:i:s")),
                'update_date' => strtotime(date("Y-m-d H:i:s")),
                'author' => $this->session->user_id,
                'is_final' => 1
            );
            $this->crud_ajax->init('m_dok_pdln', 'id_dok_pdln', NULL);
            $insert_id = $this->crud_ajax->save($data_save);

            if (!empty($insert_id)) {
                $old_file = get_file_pdln1("peserta", date("Y-m-d", $create_pdln_date_lama), $id_pdln_lama, $file->dir_path);
                $new_file = get_file_pdln1("peserta", date("Y-m-d", $create_pdln_date_baru), $id_pdln, $dir_path);

                copy_file_pdln($old_file, $new_file);
            }
        }
    }

    private function copy_peserta($id_pdln_lama, $id_pdln) {

        $pesertas = $this->db->select('*')
                        ->from('t_log_peserta')
                        ->where('id_pdln', $id_pdln_lama)
                        ->get()->result();

        foreach ($pesertas as $peserta) {
            if ($peserta->id_kategori_biaya == 0) {
                $dana_tunggal = $this->db->select('*')
                                ->from('t_ref_pembiayaan_tunggal')
                                ->where('id_log_dana_tunggal', $peserta->id_biaya)
                                ->get()->result();

                $data_biaya_tunggal = array(
                    'id_instansi' => $dana_tunggal[0]->id_instansi,
                    'biaya' => $dana_tunggal[0]->biaya,
                );
                $this->crud_ajax->init('t_ref_pembiayaan_tunggal', 'id_log_dana_tunggal', NULL);
                $insert_row_tunggal = $this->crud_ajax->save($data_biaya_tunggal);

                $data_save_log_peserta = array(
                    'id_pemohon' => $peserta->id_pemohon,
                    'id_pdln' => $id_pdln,
                    'id_kategori_biaya' => $peserta->id_kategori_biaya,
                    'id_biaya' => $insert_row_tunggal,
                    'start_date' => $peserta->start_date,
                    'end_date' => $peserta->end_date
                );
                $this->crud_ajax->init('t_log_peserta', 'id_log_peserta', NULL);
                $insert_row_log_peserta_tunggal = $this->crud_ajax->save($data_save_log_peserta);
                $this->copy_file_peserta($id_pdln_lama, $peserta->id_pemohon, $id_pdln, $peserta->id_pemohon);
            } else if ($peserta->id_kategori_biaya == 1) {
                $dana_campur = $this->db->select('*')
                                ->from('t_pembiayaan_campuran')
                                ->where('id_dana_campuran', $peserta->id_biaya)
                                ->get()->result();

                $data_biaya_campuran = array(
                    'instansi_gov' => $dana_campur[0]->instansi_gov,
                    'instansi_donor' => $dana_campur[0]->instansi_donor,
                    'biaya_apbn' => $dana_campur[0]->biaya_apbn,
                );

                $this->crud_ajax->init('t_pembiayaan_campuran', 'id_dana_campuran', NULL);
                $insert_row_campuran = $this->crud_ajax->save($data_biaya_campuran);

                $data_save_log_peserta_campuran = array(
                    'id_pemohon' => $peserta->id_pemohon,
                    'id_pdln' => $id_pdln,
                    'id_kategori_biaya' => $peserta->id_kategori_biaya,
                    'id_biaya' => $insert_row_campuran,
                    'start_date' => $peserta->start_date,
                    'end_date' => $peserta->end_date
                );
                $this->crud_ajax->init('t_log_peserta', 'id_log_peserta', NULL);
                $insert_row_log_peserta_campuran = $this->crud_ajax->save($data_save_log_peserta_campuran);

                $dana_campur = $this->db->select('*')
                                ->from('t_ref_pembiayaan_campuran')
                                ->where('id_dana_campuran', $peserta->id_biaya)
                                ->get()->result();

                foreach ($dana_campur as $dana) {

                    $data_det_biaya_campuran = array(
                        'id_dana_campuran' => (empty($insert_row_campuran)) ? NULL : $insert_row_campuran,
                        'id_jenis_biaya' => $dana->id_jenis_biaya,
                        'by' => $dana->by,
                    );
                    $this->crud_ajax->init('t_ref_pembiayaan_campuran', 'id_dana_campuran', NULL);
                    $insert_row_det_campuran = $this->crud_ajax->save($data_det_biaya_campuran);
                }

                $this->copy_file_peserta($id_pdln_lama, $peserta->id_pemohon, $id_pdln, $peserta->id_pemohon);
            }
        }
    }

    private function _copy_file_draft($id_pdln_lama, $id_pdln) {

        $this->db->where('id_pdln', $id_pdln_lama);
        // Berdasarkan tanggal first submit / create date insert row
        $row = $this->db->get('m_pdln')->row();

        $path_file_sp_fp_lama = get_file_pdln1("pdln", $row->created_date, $id_pdln_lama, $row->path_file_sp_fp);
        $path_file_sp_pemohon_lama = get_file_pdln1("pdln", $row->created_date, $id_pdln_lama, $row->path_file_sp_pemohon);

        $file_ext = strtolower(end((explode(".", $path_file_sp_pemohon_lama))));
        $path_file_sp_pemohon_baru = $id_pdln . '_' . $this->session->user_id . 'file_surat_usulan_pemohon.' . $file_ext;

        $file_ext = strtolower(end((explode(".", $path_file_sp_fp_lama))));
        $path_file_sp_fp_baru = $id_pdln . '_' . $this->session->user_id . 'file_surat_usulan_fp.' . $file_ext;

        $this->db->where('id_pdln', $id_pdln);
        // Berdasarkan tanggal first submit / create date insert row
        $row = $this->db->get('m_pdln')->row();
        $new_path_file_sp_fp = get_file_pdln1("pdln", $row->created_date, $id_pdln, $path_file_sp_fp_baru);
        $new_path_file_sp_pemohon = get_file_pdln1("pdln", $row->created_date, $id_pdln, $path_file_sp_pemohon_baru);

        if (copy_file_pdln($path_file_sp_pemohon_lama, $new_path_file_sp_pemohon)) {
            $this->crud_ajax->init('m_pdln', 'id_pdln', NULL);
            $data_path = array(
                'path_file_sp_pemohon' => $path_file_sp_pemohon_baru,
                'update_date' => strtotime(date("Y-m-d H:i:s")),
                'update_by' => $this->session->user_id
            );
            $where = array(
                'id_pdln' => $id_pdln
            );
            $this->crud_ajax->setExtraWhere($where);
            $affected_rows = $this->crud_ajax->update($where, $data_path);
        }
        if (copy_file_pdln($path_file_sp_fp_lama, $new_path_file_sp_fp)) {
            $this->crud_ajax->init('m_pdln', 'id_pdln', NULL);
            $data_path = array(
                'path_file_sp_fp' => $path_file_sp_fp_baru,
                'update_date' => strtotime(date("Y-m-d H:i:s")),
                'update_by' => $this->session->user_id
            );
            $where = array(
                'id_pdln' => $id_pdln
            );
            $this->crud_ajax->setExtraWhere($where);
            $affected_rows = $this->crud_ajax->update($where, $data_path);
        }
    }

    private function _upload_file_draft($id, $jenis_file, $field_file, $file, $file_ext) {
        $new_name = $id . '_' . $this->session->user_id . '_' . $jenis_file . '.' . $file_ext;

        $this->db->where('id_pdln', $id);
        // Berdasarkan tanggal first submit / create date insert row
        $create_date = $this->db->get('m_pdln')->row()->create_date;

        $response = FALSE;

        if (upload_pdln("pdln", $id, $new_name, $file, date("Y-m-d", $create_date)))
            $response = TRUE;

        $this->crud_ajax->init('m_pdln', 'id_pdln', NULL);
        $data_path = array(
            $field_file => $new_name,
            'update_date' => strtotime(date("Y-m-d H:i:s")),
            'update_by' => $this->session->user_id
        );
        $where = array(
            'id_pdln' => $id
        );
        $this->crud_ajax->setExtraWhere($where);
        $affected_rows = $this->crud_ajax->update($where, $data_path);
        if ($affected_rows > 0) {
            // $affected_rows doesnt necesseraly mean failed
            //$response = TRUE;
        }
        // $affected_rows doesnt necesseraly mean failed
        //$response = TRUE;
        return (bool) $response;
    }

    public function save_draft_insert() {
        // print_r($_FILES['file_surat_usulan_fp']);exit();
        if (is_pemohon($this->session->user_id)) {
            $id_pemohon = $this->session->user_id;
            $fp = get_focal_point_by($this->session->user_id);
            (empty($fp)) ? $fp = NULL : $fp;
        } else {
            // check if Suser fp not select any pemohon from list dropdown select2
            $id_pemohon = $this->input->post('list_pemohon');
            (empty($id_pemohon)) ? $id_pemohon = NULL : $id_pemohon;
            $fp = $this->session->user_id;
        }
        // init table m_pdln
        $this->crud_ajax->init('m_pdln', 'id_pdln', NULL);
        $response = array();
        /*         * * */
        $id_level_pejabat = $this->input->post('level_pejabat');
        $no_surat_usulan_pemohon = $this->input->post('no_surat_usulan_pemohon');
        $tgl_surat_usulan_pemohon = $this->input->post('tgl_surat_usulan_pemohon');
        $no_surat_usulan_fp = $this->input->post('no_surat_usulan_focal_point');
        $tgl_surat_usulan_fp = $this->input->post('tgl_surat_usulan_fp');
        $pejabat_sign_sp = $this->input->post('pejabat_sign_permohonan');
        $id_pdln_lama = $this->input->post('id_pdln_lama');

        $this->db->where('id_pdln', $id_pdln_lama);
        $row = $this->db->get('m_pdln')->row();
        $opt_jenis_permohonan = $this->input->post('opt_jenis_permohonan');

        if (!empty($row)) {
            $data_save = array(
                'id_pdln_lama' => $id_pdln_lama,
                'unit_pemohon' => $row->unit_pemohon,
                'unit_fp' => $row->unit_fp,
                'id_level_pejabat' => $row->id_level_pejabat,
                'no_surat_usulan_pemohon' => $row->no_surat_usulan_pemohon,
                'tgl_surat_usulan_pemohon' => $row->tgl_surat_usulan_pemohon,
                'no_surat_usulan_fp' => $row->no_surat_usulan_fp,
                'tgl_surat_usulan_fp' => $row->tgl_surat_usulan_fp,
                'create_date' => strtotime(date("Y-m-d H:i:s")),
                'path_file_sp_pemohon' => $row->path_file_sp_pemohon,
                'path_file_sp_fp' => $row->path_file_sp_fp,
                'pejabat_sign_sp' => $row->pejabat_sign_sp,
                'id_kegiatan' => $row->id_kegiatan,
                'status' => 2,
                'jenis_permohonan' => $opt_jenis_permohonan == 0 ? '20' : '30', //Diperpanjang/Diralat
                'is_draft' => 1,
                'path_sp' => $row->path_sp,
                'no_register' => $row->no_register,
                'no_sp' => $row->no_sp,
                'tgl_register' => $row->tgl_register,
                'tgl_sp' => $row->tgl_sp,
                'author' => $this->session->user_id,
                'update_date' => NULL,
                'update_by' => NULL,
                'last_download' => NULL,
                'format_tembusan' => NULL,
                'penandatangan_persetujuan' => NULL,
                'is_final_print' => NULL,
                'keterangan' => NULL,
                'barcode' => NULL
            );
        } else {
            $data_save = array(
                'id_pdln_lama' => $id_pdln_lama,
                'unit_pemohon' => $id_pemohon,
                'unit_fp' => $fp,
                'id_level_pejabat' => (empty($id_level_pejabat)) ? NULL : $id_level_pejabat,
                'no_surat_usulan_pemohon' => (empty($no_surat_usulan_pemohon)) ? NULL : $no_surat_usulan_pemohon,
                'tgl_surat_usulan_pemohon' => (empty($tgl_surat_usulan_pemohon)) ? NULL : strtotime($tgl_surat_usulan_pemohon),
                'no_surat_usulan_fp' => (empty($no_surat_usulan_fp)) ? NULL : $no_surat_usulan_fp,
                'tgl_surat_usulan_fp' => (empty($tgl_surat_usulan_fp)) ? NULL : strtotime($tgl_surat_usulan_fp),
                'create_date' => strtotime(date("Y-m-d H:i:s")),
                'path_file_sp_pemohon' => NULL,
                'path_file_sp_fp' => NULL,
                'pejabat_sign_sp' => (empty($pejabat_sign_sp)) ? NULL : $pejabat_sign_sp,
                'id_kegiatan' => NULL,
                'status' => 2,
                'jenis_permohonan' => "10", // Baru
                'is_draft' => 1,
                'path_sp' => NULL,
                'no_register' => NULL,
                'no_sp' => NULL,
                'tgl_register' => NULL,
                'tgl_sp' => NULL,
                'author' => $this->session->user_id,
                'update_date' => NULL,
                'update_by' => NULL,
                'last_download' => NULL,
                'format_tembusan' => NULL,
                'penandatangan_persetujuan' => NULL,
                'is_final_print' => NULL,
                'keterangan' => NULL,
                'barcode' => NULL
            );
        }

        $response['status'] = TRUE;
        $response['data_save'] = $data_save;

        $insert_id_pdln_new_draft = $this->crud_ajax->save($data_save);

        $this->copy_file_kegiatan($id_pdln_lama, $insert_id_pdln_new_draft);
        $this->copy_peserta($id_pdln_lama, $insert_id_pdln_new_draft);


        if (empty($insert_id_pdln_new_draft)) { // If Failed then trans_rollback
            $response['msg'] = "Simpan data gagal, harap coba lagi setelah beberapa waktu";
            $response['status'] = FALSE;
            exit();
        } else {
            //upload file_sp_pemohon
            if (isset($_FILES['file_surat_usulan_pemohon']['name'])) {
                $file_surat_usulan_pemohon = $_FILES['file_surat_usulan_pemohon'];
                $name = $_FILES["file_surat_usulan_pemohon"]["name"];
                $file_ext = strtolower(end((explode(".", $name))));
                if ($this->_upload_file_draft($insert_id_pdln_new_draft, 'file_surat_usulan_pemohon', 'path_file_sp_pemohon', 'file_surat_usulan_pemohon', $file_ext)) {
                    $response['status'] = TRUE;
                } else
                    $response['status'] = FALSE;
            }
            // upload file_sp_fp
            if (isset($_FILES['file_surat_usulan_fp']['name'])) {
                $file_surat_usulan_fp = $_FILES['file_surat_usulan_fp'];
                $name = $_FILES["file_surat_usulan_fp"]["name"];
                $file_ext = strtolower(end((explode(".", $name))));
                if ($this->_upload_file_draft($insert_id_pdln_new_draft, 'file_surat_usulan_fp', 'path_file_sp_fp', 'file_surat_usulan_fp', $file_ext)) {
                    $response['status'] = TRUE;
                } else
                    $response['status'] = FALSE;
            }


            $response['id_pdln'] = $insert_id_pdln_new_draft;
        }
        echo json_encode($response);
    }

    public function save_draft_update() {
        if (is_pemohon($this->session->user_id)) {
            $id_pemohon = $this->session->user_id;
            $fp = get_focal_point_by($this->session->user_id);
            (empty($fp)) ? $fp = NULL : $fp;
        } else {
            // check if user fp not select any pemohon from list select
            $id_pemohon = $this->input->post('list_pemohon');
            (empty($id_pemohon)) ? $id_pemohon = NULL : $id_pemohon;
            $fp = $this->session->user_id;
        }
        // init table m_pdln
        $this->crud_ajax->init('m_pdln', 'id_pdln', NULL);
        $response = array();

        /*         * * */
        $id_level_pejabat = $this->input->post('level_pejabat');
        $no_surat_usulan_pemohon = $this->input->post('no_surat_usulan_pemohon');
        $tgl_surat_usulan_pemohon = $this->input->post('tgl_surat_usulan_pemohon');
        $no_surat_usulan_fp = $this->input->post('no_surat_usulan_focal_point');
        $tgl_surat_usulan_fp = $this->input->post('tgl_surat_usulan_fp');
        $pejabat_sign_sp = $this->input->post('pejabat_sign_permohonan');
        $id_kegiatan = $this->input->post('kegiatan');
        $jenis_permohonan = $this->input->post('opt_jenis_permohonan') == '0' ? '20' : '30'; //Diperpanjang/Diralat
        // Not use for insert to table
        // Data Update
        $data_save = array(
            'jenis_permohonan' => $jenis_permohonan,
            'unit_pemohon' => $id_pemohon,
            'unit_fp' => $fp,
            'id_level_pejabat' => (empty($id_level_pejabat)) ? NULL : $id_level_pejabat,
            'no_surat_usulan_pemohon' => (empty($no_surat_usulan_pemohon)) ? NULL : $no_surat_usulan_pemohon,
            'tgl_surat_usulan_pemohon' => (empty($tgl_surat_usulan_pemohon)) ? NULL : strtotime($tgl_surat_usulan_pemohon),
            'no_surat_usulan_fp' => (empty($no_surat_usulan_fp)) ? NULL : $no_surat_usulan_fp,
            'tgl_surat_usulan_fp' => (empty($tgl_surat_usulan_fp)) ? NULL : strtotime($tgl_surat_usulan_fp),
            'update_date' => strtotime(date("Y-m-d H:i:s")),
            'pejabat_sign_sp' => (empty($pejabat_sign_sp)) ? NULL : $pejabat_sign_sp,
            'id_kegiatan' => (empty($id_kegiatan)) ? NULL : $id_kegiatan,
            'update_by' => $this->session->user_id
        );
        $response['status'] = TRUE;
        // Key for Updates
        $id_pdln = $this->input->post('id_pdln');
        if (empty($id_pdln)) {
            $response['msg'] = "Gagal mendapatkan Data Kode PDLN";
            $response['status'] = FALSE;
            exit();
        } else {
            $where = array('id_pdln' => $id_pdln);
            $affected_rows = $this->crud_ajax->update($where, $data_save);
            if ($affected_rows < 1) { // if failed then trans_rollback
                $response['msg'] = "Update data gagal, penyimpanan data tidak perlu dilakukan";
                $response['status'] = FALSE;
            } else {
                //upload file_sp_pemohon
                if (isset($_FILES['file_surat_usulan_pemohon']['type'])) {
                    $file_surat_usulan_pemohon = $_FILES['file_surat_usulan_pemohon'];
                    $name = $_FILES["file_surat_usulan_pemohon"]["name"];
                    $file_ext = strtolower(end((explode(".", $name))));
                    if ($this->_upload_file_draft($id_pdln, 'file_surat_usulan_pemohon', 'path_file_sp_pemohon', 'file_surat_usulan_pemohon', $file_ext)) {
                        $response['status'] = TRUE;
                    } else {
                        $response['msg'] = "Sukses simpan data";
                        $response['status'] = FALSE;
                    }
                }
                // upload file_sp_fp
                if (isset($_FILES['file_surat_usulan_fp']['type'])) {
                    $file_surat_usulan_fp = $_FILES['file_surat_usulan_fp'];
                    $name = $_FILES["file_surat_usulan_fp"]["name"];
                    $file_ext = strtolower(end((explode(".", $name))));
                    if ($this->_upload_file_draft($id_pdln, 'file_surat_usulan_fp', 'path_file_sp_fp', 'file_surat_usulan_fp', $file_ext)) {
                        $response['status'] = TRUE;
                    } else {
                        $response['msg'] = "Sukses simpan data";
                        $response['status'] = FALSE;
                    }
                }
            }
            $response['id_pdln'] = $id_pdln;
        }
        echo json_encode($response);
    }

    public function set_no_register(){
        $conter_doc = $this->db->get('t_suratmasuk_increment')->first_row();        
        if(empty($conter_doc)){
            $last_doc = 1;
            $data_init = array(
                        'nomor'=>  $last_doc ,
                        'last_update'=>strtotime(date("Y-m-d H:i:s")),
                        'tahun'=>date("Y"),
                        'status'=>1
            );
            $this->db->insert('t_suratmasuk_increment',$data_init);
        }else{
            $no = $this->db->get('t_suratmasuk_increment')->row()->nomor;

            $last_doc = $no+1;
            $data = array(
                        'nomor'=> $last_doc ,
                        'last_update'=>strtotime(date("Y-m-d H:i:s")),
                        'tahun'=>date("Y"),
                        'status'=>1
                    );
            $this->db->update('t_suratmasuk_increment',$data);
        }
        return $last_doc;
    }

    public function submit_permohonan() {
        $status;
        $response['status'] = TRUE;
        $id_pdln = $this->input->post('id_pdln');

        $data_pdln = $this->db->from('m_pdln')->where('id_pdln', $id_pdln)->get()->row();

        if ($data_pdln->status == 12) { //Jika status dikembalikan
            $this->db->from('t_approval_pdln');
            $this->db->where('id_pdln', $id_pdln);
            $this->db->where('aksi', 'tolak');
            $reject_approval = $this->db->get()->row();

            $data = array(
                'update_date' => strtotime(date('Y-m-d H:i:s')),
                'status' => get_id_status_approval($reject_approval->level),
                'is_draft' => 0
            );

            /* $response['status'] = False;
              $response['msg']    = $data;
              echo json_encode($response);
              exit; */

            $this->db->where('id_pdln', $id_pdln);
            $affected_rows = $this->db->update('m_pdln', $data);

            $data_approval = array(
                'id_pdln' => $id_pdln,
                'note' => '',
                'submit_date' => '',
                'assign_date' => date("Y-m-d H:i:s"),
                'level' => $reject_approval->level,
                'aksi' => '',
                'is_done' => 0
            );
            $insert_id_approval = $this->db->insert('t_approval_pdln', $data_approval);
            if (empty($insert_id_approval)) {
                $response['msg'] = "Gagal Simpan Data Workflow";
                $response['status'] = FALSE;
            }
            /* Update Current Data Approval */
            $this->crud_ajax->init('t_approval_pdln', 'id', null);
            $data_update_approval = array(
                'user_id' => $this->session->user_id,
                'note' => 'Submit perbaikan',
                'submit_date' => date('Y-m-d H:i:s'),
                'aksi' => 'resubmit',
                'is_done' => 1,
            );
            $where_approval = array('level' => 'Focalpoint', 'id_pdln' => $id_pdln, 'is_done' => 0);
            $affected_row_u = $this->crud_ajax->update($where_approval, $data_update_approval);
            $response['msg'] = "SETNEG";
        } else {
            if (is_pemohon($this->session->user_id)) {
                $status = 2;
                $no_register = NULL;
                $tgl_register = NULL;
                $level = "Draft Focalpoint"; //Dicatat di tabel approval hanya sebagai log, tidak ditampilkan di history approval untuk internal
                $is_draft = 1;
            } else {
                $no_register = $this->set_no_register();
                $tgl_register = strtotime(date('Y-m-d H:i:s'));
                $status = 3;
                $level = "Analis";
                $is_draft = 0;
            }
            $data = array(
                'no_register' => $no_register,
                'tgl_register' => $tgl_register,
                'update_date' => strtotime(date('Y-m-d H:i:s')),
                'status' => $status,
                'id_kegiatan' => $this->input->post('kegiatan'),
                'is_draft' => $is_draft
            );
            $id_pdln = $this->input->post('id_pdln');
            $this->db->where('id_pdln', $id_pdln);
            $affected_rows = $this->db->update('m_pdln', $data);

            $data_approval = array(
                'id_pdln' => $id_pdln,
                'user_id' => $this->session->user_id,
                'note' => '',
                'submit_date' => date("Y-m-d H:i:s"),
                'assign_date' => '',
                'level' => $level,
                'aksi' => '',
                'is_done' => 0
            );
            $insert_id_approval = $this->db->insert('t_approval_pdln', $data_approval);

            if (empty($insert_id_approval)) {
                $response['msg'] = "Gagal Simpan Data Workflow";
                $response['status'] = FALSE;
            } else {
                if (is_pemohon($this->session->user_id))
                    $response['msg'] = "Focal Point";
                else {
                    $response['msg'] = "SETNEG";
                    $response['no_register'] = str_pad($no_register, 8, '0', STR_PAD_LEFT);
                }
            }
        }
        echo json_encode($response);
    }

    public function upload_file_kegiatan() {
        $id_pdln = $this->input->post('id_pdln');
        $name_file = $this->input->post('jenis_doc');
        $file = $this->input->post('name_attr');
        $file_ext = $this->input->post('type_file');

        $this->db->select('create_date');
        $this->db->from('m_pdln');
        $this->db->where('id_pdln', $id_pdln);
        $create_pdln_date = $this->db->get()->row()->create_date;

        $response['status'] = FALSE;
        $data_save = array(
            'id_pdln' => $id_pdln,
            'dir_path' => NULL,
            'id_jenis_doc' => $this->input->post('id_jenis_doc'),
            'kategori_doc' => $this->input->post('kategori_doc'),
            'create_date' => strtotime(date("Y-m-d H:i:s")),
            'update_date' => strtotime(date("Y-m-d H:i:s")),
            'author' => $this->session->user_id,
            'is_final' => 1
        );
        $this->crud_ajax->init('m_dok_pdln', 'id_pdln', NULL);

        //check is already filename on db
        $check = $id_pdln . '_' . $name_file . $file_ext;

        $is_exist = $this->db->get_where('m_dok_pdln', array('dir_path' => $check));
        if ($is_exist->num_rows() > 0) {
            foreach ($is_exist->result() as $row) {
                $id_doc_db = $row->id_dok_pdln;
            }
            $new_name = $id_pdln . '_' . $name_file . $file_ext;
            $data_save_update = array(
                'dir_path' => $new_name,
                'id_jenis_doc' => $this->input->post('id_jenis_doc'),
                'kategori_doc' => $this->input->post('kategori_doc'),
                'update_date' => strtotime(date("Y-m-d H:i:s")),
                'author' => $this->session->user_id
            );
            upload_pdln("kegiatan", $id_pdln, $new_name, $file, date("Y-m-d", $create_pdln_date));
            $where_update = array('id_dok_pdln' => $id_doc_db);
            $affected_rows = $this->crud_ajax->update($where_update, $data_save_update);
        } else {
            $insert_id = $this->crud_ajax->save($data_save);
            if (!empty($insert_id)) {
                $response['status'] = TRUE;
                $new_name = $id_pdln . '_' . $name_file . $file_ext;
                upload_pdln("kegiatan", $id_pdln, $new_name, $file, date("Y-m-d", $create_pdln_date));
                $save = array('dir_path' => $new_name);
                $where = array('id_dok_pdln' => $insert_id);

                $affected_rows = $this->crud_ajax->update($where, $save);
            }
        }
        echo json_encode($response);
    }

}
