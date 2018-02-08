<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent ::__construct();
    }

    public function index() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_laporan_umum';
        $data['title'] = 'Laporan Umum';
        $data['title_page'] = 'Laporan Umum';
        $data['breadcrumb'] = 'Laporan Umum';
        $data['kegiatan'] = $this->db->select("ID, NamaKegiatan Nama")
                ->from('m_kegiatan')
                ->where('status = 1')
                ->get()
                ->result();
        $data['instansi'] = $this->db->select("ID, Nama")
                ->from('r_institution')
                ->get()
                ->result();
        page_render($data);
    }

    private function to_unix_time($date) {
        $parts = explode('-', $date);
        return mktime(0, 0, 0, $parts[2], $parts[0], $parts[1]);
    }

    public function export_umum() {
        $from = $this->input->get('tgl_from');
        $to = $this->input->get('tgl_to');
        $kegiatan = $this->input->get('kegiatan');
        $instansi = $this->input->get('instansi');
        $status = $this->input->get('status');
        $this->db->select("mp.id_pdln, mp.tgl_register, mp.no_register, mp.unit_fp as unit_pemohon, lp.nama as level_pejabat,
                    mp.jenis_permohonan, rk.Nama as jenis_kegiatan, mk.NamaKegiatan, mp.status as status_sp, mk.StartDate, mk.EndDate,
                    mp.tgl_sp, mp.no_sp, mp.id_kegiatan, mp.update_by, vp.jumlah_peserta, vp.negara_tujuan, vp.id_instansi, ri.Nama as nama_instansi")
                ->from('m_pdln as mp')
                ->join('r_level_pejabat as lp', 'lp.id = mp.id_level_pejabat', 'left')
                ->join('m_kegiatan as mk', 'mk.ID = mp.id_kegiatan', 'left')
                ->join('r_jenis_kegiatan as rk', 'rk.ID = mk.JenisKegiatan', 'left')
                ->join('(select id_pdln, COUNT(*) as jumlah_peserta, negara_tujuan, id_instansi from view_log_peserta GROUP BY id_pdln) as vp', 'vp.id_pdln = mp.id_pdln', 'left')
                ->join('r_institution as ri', 'ri.ID = vp.id_instansi', 'left');
        if ($from != FALSE) {
            $this->db->where(array('mp.tgl_register >= ' => $this->to_unix_time($from)));
        }
        if ($to != FALSE) {
            $this->db->where(array('mp.tgl_register <= ' => $this->to_unix_time($to)));
        }
        if ($kegiatan != FALSE) {
            $this->db->where(array('mp.id_kegiatan' => $kegiatan));
        }
        if ($instansi != FALSE) {
            $this->db->where(array('vp.id_instansi' => $instansi));
        }
        if($status != FALSE){
            $this->db->where(array('mp.status' => $status));
        }
        $data = $this->db->get();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Laporan Umum');
        // Nama Field Baris Pertama
        $fields = $data->list_fields();
        $col = 0;
        foreach ($fields as $field) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        // Mengambil Data
        $row = 2;
        foreach ($data->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $data->$field);
            }
            $row++;
        }
        //Save ke .xlsx, kalau ingin .xls, ubah 'Excel2007' menjadi 'Excel5'
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        //Header
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //Nama File
        header('Content-Disposition: attachment;filename="laporan-umum.xlsx"');
        //Download
        $objWriter->save("php://output");
    }

    public function export_personal() {
        $from = $this->input->get('tgl_from');
        $to = $this->input->get('tgl_to');
        $nip = $this->input->get('nip');
        $instansi = $this->input->get('instansi');
        $status = $this->input->get('status');
        $this->db->select("
                    view_log_peserta.id_log_peserta, m_pemohon.nama as nama_peserta, m_pemohon.nik, m_pemohon.nip_nrp,
                    m_pdln.tgl_register, m_pdln.no_register, m_pdln.unit_fp as unit_pemohon, view_log_peserta.id_instansi,
                    r_institution.Nama as nama_instansi, r_level_pejabat.nama as level_pejabat, m_pdln.jenis_permohonan,
                    r_jenis_kegiatan.Nama as jenis_kegiatan, m_kegiatan.NamaKegiatan, view_log_peserta.negara_tujuan,
                    view_log_peserta.start_date, view_log_peserta.end_date, m_pdln.status, m_pdln.no_sp, m_pdln.tgl_sp
                ")
                ->from('view_log_peserta')
                ->join('m_pemohon', 'm_pemohon.id_pemohon = view_log_peserta.id_pemohon', 'left')
                ->join('m_pdln', 'm_pdln.id_pdln = view_log_peserta.id_pdln', 'left')
                ->join('r_level_pejabat', 'r_level_pejabat.id = m_pdln.id_level_pejabat', 'left')
                ->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan', 'left')
                ->join('r_jenis_kegiatan', 'r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan', 'left')
                ->join('r_institution', 'r_institution.ID = view_log_peserta.id_instansi', 'left');

        if ($from != FALSE) {
            $this->db->where(array('view_log_peserta.start_date >= ' => strtotime($from)));
        }
        if ($to != FALSE) {
            $this->db->where(array('view_log_peserta.end_date <= ' => strtotime($to)));
        }
        if ($instansi != FALSE) {
            $this->db->where(array('view_log_peserta.id_instansi' => $instansi));
        }

        // if ($nip != FALSE) {
        //     $this->db->where(array('CONCAT(m_pemohon.nama, m_pemohon.nik, m_pemohon.nip_nrp) LIKE ' => '%'.$nip.'%'));
        // }
        // if($status != FALSE){
        //     $this->db->where(array('status' => $status));
        // }
        // exit();
        $data = $this->db->get();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Laporan Personal');
        // Nama Field Baris Pertama
        $fields = $data->list_fields();
        $col = 0;
        foreach ($fields as $field) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        // Mengambil Data
        $row = 2;
        foreach ($data->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
                if ($data->start_date > 0 && $field =='start_date' ) {
                    $value = (date("d-m-Y", $data->$field));

                }else if ($data->end_date > 0  && $field =='end_date'){
                    $value = (date("d-m-Y", $data->$field));

                }else if ($data->tgl_register > 0  && $field =='tgl_register'){
                    $value = (date("d-m-Y", $data->$field));
                    
                }else{
                    $value =  $data->$field;

                }
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, (string)$value);
                $col++;
            }
            // var_dump($data->$field);
            $row++;
        }
        //Save ke .xlsx, kalau ingin .xls, ubah 'Excel2007' menjadi 'Excel5'
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        //Header
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //Nama File
        header('Content-Disposition: attachment;filename="laporan-personal.xlsx"');
        //Download
        $objWriter->save("php://output");
    }

    public function search_laporan() {
        $from = $this->input->post('tgl_from');
        $to = $this->input->post('tgl_to');
        $kegiatan = $this->input->post('kegiatan');
        $instansi = $this->input->post('instansi');
        $status = $this->input->post('status');
        $this->crud_ajax->init('m_pdln', 'm_pdln.id_pdln', array('m_pdln.id_pdln' => 'asc'));
        $this->crud_ajax->set_select_field('
            m_pdln.id_pdln, m_pdln.tgl_register, m_pdln.no_register, m_pdln.unit_fp as unit_pemohon, lp.nama as level_pejabat,
            m_pdln.jenis_permohonan, rk.Nama as jenis_kegiatan, mk.NamaKegiatan, m_pdln.status as status_sp, mk.StartDate, mk.EndDate,
            m_pdln.tgl_sp, m_pdln.no_sp, m_pdln.id_kegiatan, m_pdln.update_by, vp.jumlah_peserta, vp.negara_tujuan, vp.id_instansi,
            ri.Nama as nama_instansi
        ');
        $join = array(
            'r_level_pejabat as lp'=>array('lp.id = m_pdln.id_level_pejabat', 'left'),
            'm_kegiatan as mk'=>array('mk.ID = m_pdln.id_kegiatan', 'left'),
            'r_jenis_kegiatan as rk'=>array('rk.ID = mk.JenisKegiatan', 'left'),
            '(select id_pdln, COUNT(*) as jumlah_peserta, negara_tujuan, id_instansi from view_log_peserta GROUP BY id_pdln) as vp'=>array('vp.id_pdln = m_pdln.id_pdln', 'left'),
            'r_institution as ri'=>array('ri.ID = vp.id_instansi', 'left')
        );
        //$where=array('');
        if (!empty($from)) {
            $where= array('m_pdln.tgl_register >= ' => $this->to_unix_time($from));
        }
        if (!empty($to)) {
            $where= array('m_pdln.tgl_register <= ' => $this->to_unix_time($to));
        }
        if (!empty($kegiatan)) {
            $where= array('m_pdln.id_kegiatan' => $kegiatan);
        }
        if (!empty($instansi)) {
            $where= array('vp.id_instansi' => $instansi);
        }
        if(!empty($status)){
            $where= array('m_pdln.status' => $status);
        }
        $this->crud_ajax->setJoinField($join);
        $this->crud_ajax->setExtraWhere($where);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start']:0;
        foreach ($list as $lp) {
            $row = array();
            $row[] = $lp->id_pdln;
            $row[] = ++$no;
            $row[] = day(date("Y-m-d", $lp->tgl_register));
            $row[] = str_pad($lp->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day(date("Y-m-d", $lp->tgl_sp));
            $row[] = $lp->no_sp;
            $row[] = '<span class="label label-' . setLabel($lp->jenis_permohonan) . '">' . $arrJenisPermohonan[$lp->jenis_permohonan] . '</span>';
            $row[] = '<span class="label label-danger">' . $arrStatus[$lp->status_sp]. '</span>';
            $row[] = $lp->unit_pemohon;
            $row[] = $lp->nama_instansi;
            $row[] = $lp->level_pejabat;
            $row[] = $lp->jenis_kegiatan;
            $row[] = $lp->NamaKegiatan;
            $row[] = $lp->negara_tujuan;
            $row[] = $lp->jumlah_peserta;
            $row[] = date("d-m-Y", strtotime($lp->StartDate)). " - " .date("d-m-Y", strtotime($lp->EndDate));
            $row[] = $lp->update_by;
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
            "sql" => $this->db->last_query(),
            "tes" => $from
        );
        echo json_encode($output);
    }

    public function laporan_umum_list() {
        $this->crud_ajax->init('view_log_peserta', 'id_pemohon', array('view_log_peserta.id_pemohon' => 'asc'));
        $this->crud_ajax->set_select_field('view_log_peserta.id_log_peserta, m_pemohon.nik, m_pdln.no_register, m_pdln.unit_fp, r_institution.Nama as nama_instansi, m_pdln.jenis_permohonan,
				r_jenis_kegiatan.Nama as jenis_kegiatan, m_kegiatan.NamaKegiatan, view_log_peserta.negara_tujuan,
				view_log_peserta.start_date, view_log_peserta.end_date, m_pdln.status, m_pdln.no_sp,
				m_pdln.update_by, m_pdln.id_kegiatan as waktu_proses, m_pdln.tgl_sp');
        $join = array(
            'm_pemohon' => array('m_pemohon.id_pemohon = view_log_peserta.id_pemohon', 'left'),
            'm_pdln' => array('m_pdln.id_pdln = view_log_peserta.id_pdln', 'left'),
            'm_kegiatan' => array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
            'r_jenis_kegiatan' => array('r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan', 'left'),
            'r_institution' => array('r_institution.ID = view_log_peserta.id_instansi', 'left')
        );
        $this->crud_ajax->setJoinField($join);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = isset($_POST['start'])? $_POST['start']:0;
        foreach ($list as $lp) {
            $no++;
            $row = array();
            $row[] = $lp->id_log_peserta;
            $row[] = $lp->nik;
            $row[] = $lp->no_register;
            $row[] = $lp->unit_fp;
            $row[] = $lp->nama_instansi;
            $row[] = '<span class="label label-' . setLabel($lp->jenis_permohonan) . '">' . setJenisPermohonan($lp->jenis_permohonan) . '</span>';
            $row[] = $lp->jenis_kegiatan;
            $row[] = $lp->NamaKegiatan;
            $row[] = $lp->negara_tujuan;
            $row[] = day(date("d-m-Y", $lp->start_date)) . " - " . day(date("d-m-Y", $lp->end_date));
            $row[] = '<span class="label label-danger">' . setStatus($lp->status) . '</span>';
            $row[] = $lp->no_sp;
            $row[] = $lp->update_by;
            $row[] = $lp->waktu_proses;
            $row[] = day(date("d-m-Y", $lp->tgl_sp));
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

    public function personal() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_laporan_personal';
        $data['title'] = 'Laporan Personal';
        $data['title_page'] = 'Laporan Personal';
        $data['breadcrumb'] = 'Laporan Personal';
        $id_user = $this->session->user_id;
        $instansi = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->instansi;
        $data['instansi'] = $this->db->select("ID, Nama")
                ->from('r_institution')
                ->where('ID', $instansi)
                ->get()
                ->result();
        page_render($data);
    }

    public function laporan_personal_list() {
        $from = $this->input->post('tgl_from');
        $to = $this->input->post('tgl_to');
        $nip = $this->input->post('nip');
        $instansi = $this->input->post('instansi');
        $status = $this->input->post('status');
        $this->crud_ajax->init('view_log_peserta', 'id_pemohon', array('view_log_peserta.id_pemohon' => 'asc'));
        $this->crud_ajax->set_select_field('
            view_log_peserta.id_log_peserta, m_pemohon.nama as nama_peserta, m_pemohon.nip_nrp,
            m_pdln.no_register, m_pdln.unit_fp as unit_pemohon, view_log_peserta.id_instansi,
            r_institution.Nama as nama_instansi, r_level_pejabat.nama as level_pejabat,
            m_kegiatan.NamaKegiatan, view_log_peserta.negara_tujuan,
            view_log_peserta.start_date, view_log_peserta.end_date, m_pdln.no_sp, m_pdln.tgl_sp
        ');
        $join = array(
            'm_pemohon'=>array('m_pemohon.id_pemohon = view_log_peserta.id_pemohon', 'left'),
            'm_pdln'=>array('m_pdln.id_pdln = view_log_peserta.id_pdln', 'left'),
            'r_level_pejabat'=>array('r_level_pejabat.id = m_pdln.id_level_pejabat', 'left'),
            'm_kegiatan'=>array('m_kegiatan.ID = m_pdln.id_kegiatan', 'left'),
            'r_institution'=>array('r_institution.ID = view_log_peserta.id_instansi', 'left')
        );
        $where=array('');
        if (!empty($from)) {
            $where['view_log_peserta.start_date >='] = strtotime($from);
        }
        if (!empty($to)) {

            $where['view_log_peserta.end_date <='] = strtotime($to);
        }


        if (!empty($instansi)) {
            $where['view_log_peserta.id_instansi'] = $instansi;
        }
        $this->crud_ajax->setJoinField($join);
        $this->crud_ajax->setExtraWhere($where);
        $list = $this->crud_ajax->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start']:0;
        foreach ($list as $lp) {
            $row = array();
            $row[] = $lp->id_log_peserta;
            $row[] = ++$no;
            $row[] = $lp->nama_peserta;
            // $row[] = $lp->nip_nrp;
            $row[] = str_pad($lp->no_register, 8, '0', STR_PAD_LEFT);
            $row[] = day(date("d-m-Y", $lp->tgl_sp));
            $row[] = $lp->no_sp;
            $row[] = $lp->nama_instansi;
            // $row[] = $lp->level_pejabat;
            $row[] = $lp->NamaKegiatan;
            $row[] = $lp->negara_tujuan;
            $row[] = day(date("d-m-Y", $lp->start_date)) . " - " . day(date("d-m-Y", $lp->end_date));
            $data[] = $row;
        }
        $output = array(
            "draw" => (isset($_POST['draw']) ? $_POST['draw'] : null),
            "recordsTotal" => $this->crud_ajax->count_all(),
            "recordsFiltered" => $this->crud_ajax->count_filtered(),
            "data" => $data,
            "sql" => $this->db->last_query(),
            "tes" => $from
        );
        echo json_encode($output);
    }

    public function infografis_old() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_laporan_infografis';
        $data['title'] = 'Laporan Infografis';
        $data['title_page'] = 'Laporan Infografis';
        $data['breadcrumb'] = 'Laporan Infografis';
        // Penggunaan APBN
        $this->db->select(
               'r_institution.Nama as lembaga, SUM(view_biaya_log_peserta.biaya) as biaya,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=1,view_biaya_log_peserta.biaya,0)) as jan,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=2,view_biaya_log_peserta.biaya,0)) as feb,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=3,view_biaya_log_peserta.biaya,0)) as mar,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=4,view_biaya_log_peserta.biaya,0)) as apr,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=5,view_biaya_log_peserta.biaya,0)) as mei,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=6,view_biaya_log_peserta.biaya,0)) as jun,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=7,view_biaya_log_peserta.biaya,0)) as jul,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=8,view_biaya_log_peserta.biaya,0)) as agus,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=9,view_biaya_log_peserta.biaya,0)) as sep,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=10,view_biaya_log_peserta.biaya,0)) as okt,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=11,view_biaya_log_peserta.biaya,0)) as nov,
                SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=12,view_biaya_log_peserta.biaya,0)) as des
            ', false);
        $this->db->from('m_pdln');
        $this->db->join('view_biaya_log_peserta', 'view_biaya_log_peserta.id_pdln = m_pdln.id_pdln', 'INNER');
        $this->db->join('t_log_peserta', 't_log_peserta.id_log_peserta = view_biaya_log_peserta.id_log_peserta', 'INNER');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'INNER');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi', 'LEFT');
        $this->db->where('m_pemohon.id_instansi != ', 0);
        $this->db->group_by('m_pemohon.id_instansi');
        $this->db->order_by('SUM(view_biaya_log_peserta.biaya)', 'DESC');
        $result_apbn = $this->db->get()->result_array();
        $data['list_penggunaan_apbn'] = $result_apbn;
        // Jumlah Peserta
        $this->db->select('r_institution.Nama as lembaga, count(m_pdln.id_pdln) as jumlah,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=1,1,0)) as jan,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=2,1,0)) as feb,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=3,1,0)) as mar,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=4,1,0)) as apr,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=5,1,0)) as mei,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=6,1,0)) as jun,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=7,1,0)) as jul,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=8,1,0)) as agus,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=9,1,0)) as sep,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=10,1,0)) as okt,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=11,1,0)) as nov,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=12,1,0)) as des', false);
        $this->db->from('m_pdln');
        $this->db->join('t_log_peserta', 't_log_peserta.id_pdln = m_pdln.id_pdln', 'LEFT');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'LEFT');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi', 'LEFT');
        $this->db->where('m_pemohon.id_instansi != ', 0);
        $this->db->group_by('m_pemohon.id_instansi');
        $this->db->order_by('count(m_pdln.id_pdln)', 'DESC');
        $result_peserta = $this->db->get()->result_array();
        $data['list_peserta'] = $result_peserta;
        // Jumlah SP
        $this->db->select('r_institution.Nama as lembaga, count(m_pdln.id_pdln) as jumlah,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=1,1,0)) as jan,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=2,1,0)) as feb,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=3,1,0)) as mar,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=4,1,0)) as apr,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=5,1,0)) as mei,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=6,1,0)) as jun,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=7,1,0)) as jul,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=8,1,0)) as agus,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=9,1,0)) as sep,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=10,1,0)) as okt,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=11,1,0)) as nov,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=12,1,0)) as des', false);
        $this->db->from('m_pdln');
        $this->db->join('t_log_peserta', 't_log_peserta.id_pdln = m_pdln.id_pdln', 'LEFT');
        $this->db->join('m_user', 'm_user.UserID = m_pdln.unit_fp', 'LEFT');
        $this->db->join('r_institution', 'r_institution.ID = m_user.instansi', 'LEFT');
        $this->db->where('m_user.instansi != ', 0);
        $this->db->group_by('m_user.instansi');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result_sp = $this->db->get()->result_array();
        $data['list_sp'] = $result_sp;
        // Kunjungan Per Negara
        $this->db->select('r_negara.nmnegara as country, count(m_pdln.id_pdln) as jumlah,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=1,1,0)) as jan,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=2,1,0)) as feb,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=3,1,0)) as mar,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=4,1,0)) as apr,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=5,1,0)) as mei,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=6,1,0)) as jun,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=7,1,0)) as jul,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=8,1,0)) as agus,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=9,1,0)) as sep,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=10,1,0)) as okt,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=11,1,0)) as nov,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=12,1,0)) as des', false);
        $this->db->from('m_pdln');
        $this->db->join('t_log_peserta', 't_log_peserta.id_pdln = m_pdln.id_pdln', 'LEFT');
        $this->db->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan', 'LEFT');
        $this->db->join('r_negara', 'r_negara.id = m_kegiatan.Negara', 'LEFT');
        $this->db->where('m_pdln.id_kegiatan !=', null);
        $this->db->group_by('m_kegiatan.Negara');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result_negara = $this->db->get()->result_array();
        $data['list_negara'] = $result_negara;
        // jenis Penugasan
        $this->db->select(
               'r_jenis_kegiatan.Nama as jenis_tugas, count(m_pdln.id_pdln) as jumlah,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=1,1,0)) as jan,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=2,1,0)) as feb,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=3,1,0)) as mar,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=4,1,0)) as apr,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=5,1,0)) as mei,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=6,1,0)) as jun,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=7,1,0)) as jul,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=8,1,0)) as agus,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=9,1,0)) as sep,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=10,1,0)) as okt,
	            SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=11,1,0)) as nov,
                SUM(IF(month(FROM_UNIXTIME(t_log_peserta.start_date))=12,1,0)) as des'
               , false);
        $this->db->from('m_pdln');
        $this->db->join('t_log_peserta', 't_log_peserta.id_pdln = m_pdln.id_pdln', 'LEFT');
        $this->db->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan', 'LEFT');
        $this->db->join('r_jenis_kegiatan', 'r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan', 'LEFT');
        $this->db->where('m_pdln.id_kegiatan !=', null);
        $this->db->group_by('m_kegiatan.JenisKegiatan');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result_tugas = $this->db->get()->result_array();
        $data['list_tugas'] = $result_tugas;
        page_render($data);
    }

    public function infografis() {
        $data['theme'] = 'pdln';
        $data['page'] = 'v_laporan_tableu';
        $data['title'] = 'Laporan Infografis';
        $data['title_page'] = 'Laporan Infografis';
        $data['breadcrumb'] = 'Laporan Infografis';
        page_render($data);
    }

    public function get_kunjungan_negara() {
        $this->db->select('r_negara.nmnegara as country, count(m_pdln.id_pdln) as jumlah', false);
        $this->db->from('m_pdln');
        $this->db->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan', 'left');
        $this->db->join('r_negara', 'r_negara.id = m_kegiatan.Negara', 'left');
        $this->db->where('m_pdln.id_kegiatan !=', null);
        $this->db->group_by('m_kegiatan.Negara');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result = $this->db->get()->result();
        $data=array();
        $i=0;
        $sisa=0;
        foreach($result as $r){
            if(++$i==6){
                $i=5;
                $sisa+=$r->jumlah;
            }else{
                $data[$i-1]=array('country'=>$r->country,'jumlah'=>$r->jumlah);
            }
        }
        if($i==5) $data[4]=array('country'=>'Lainnya','jumlah'=>$sisa);
        echo json_encode($data);
    }

    public function get_jenis_penugasan() {
        $this->db->select('r_jenis_kegiatan.Nama as jenis_tugas, count(m_pdln.id_pdln) as jumlah', false);
        $this->db->from('m_pdln');
        $this->db->join('m_kegiatan', 'm_kegiatan.ID = m_pdln.id_kegiatan', 'left');
        $this->db->join('r_jenis_kegiatan', 'r_jenis_kegiatan.ID = m_kegiatan.JenisKegiatan', 'left');
        $this->db->where('m_pdln.id_kegiatan !=', null);
        $this->db->group_by('m_kegiatan.JenisKegiatan');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result = $this->db->get()->result();
        $data=array();
        $i=0;
        $sisa=0;
        foreach($result as $r){
            if(++$i==6){
                $i=5;
                $sisa+=$r->jumlah;
            }else{
                $data[$i-1]=array('jenis_tugas'=>$r->jenis_tugas,'jumlah'=>$r->jumlah);
            }
        }
        if($i==5) $data[4]=array('jenis_tugas'=>'Lainnya','jumlah'=>$sisa);
        echo json_encode($data);
    }

    public function get_jumlah_sp() {
        $this->db->select('r_institution.Nama as lembaga, count(m_pdln.id_pdln) as jumlah', false);
        $this->db->from('m_pdln');
        $this->db->join('m_user', 'm_user.UserID = m_pdln.unit_fp', 'left');
        $this->db->join('r_institution', 'r_institution.ID = m_user.instansi', 'left');
        $this->db->where('m_user.instansi !=', '0');
        $this->db->group_by('m_user.instansi');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result = $this->db->get()->result();
        $data=array();
        $i=0;
        $sisa=0;
        foreach($result as $r){
            if(++$i==6){
                $i=5;
                $sisa+=$r->jumlah;
            }else{
                $data[$i-1]=array('lembaga'=>$r->lembaga,'jumlah'=>$r->jumlah);
            }
        }
        if($i==5) $data[4]=array('lembaga'=>'Lainnya','jumlah'=>$sisa);
        echo json_encode($data);
    }

    public function get_jumlah_peserta() {
        $this->db->select('r_institution.Nama as lembaga, count(m_pdln.id_pdln) as jumlah', false);
        $this->db->from('m_pdln');
        $this->db->join('t_log_peserta', 't_log_peserta.id_pdln = m_pdln.id_pdln', 'left');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'left');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi', 'left');
        $this->db->where('m_pemohon.id_instansi !=', '0');
        $this->db->group_by('m_pemohon.id_instansi');
        $this->db->order_by('count(m_pdln.id_pdln)', ' DESC');
        $result = $this->db->get()->result();
        $data=array();
        $i=0;
        $sisa=0;
        foreach($result as $r){
            if(++$i==6){
                $i=5;
                $sisa+=$r->jumlah;
            }else{
                $data[$i-1]=array('lembaga'=>$r->lembaga,'jumlah'=>$r->jumlah);
            }
        }
        if($i==5) $data[4]=array('lembaga'=>'Lainnya','jumlah'=>$sisa);
        echo json_encode($data);
    }

    public function get_penggunaan_apbn() {
        $this->db->select('r_institution.Nama as lembaga, SUM(view_biaya_log_peserta.biaya) as biaya', false);
        $this->db->from('m_pdln');
        $this->db->join('view_biaya_log_peserta', 'view_biaya_log_peserta.id_pdln = m_pdln.id_pdln', 'inner');
        $this->db->join('t_log_peserta', 't_log_peserta.id_log_peserta = view_biaya_log_peserta.id_log_peserta', 'inner');
        $this->db->join('m_pemohon', 'm_pemohon.id_pemohon = t_log_peserta.id_pemohon', 'inner');
        $this->db->join('r_institution', 'r_institution.ID = m_pemohon.id_instansi', 'left');
        $this->db->where('m_pemohon.id_instansi !=', '0');
        $this->db->group_by('m_pemohon.id_instansi');
        $this->db->order_by('SUM(view_biaya_log_peserta.biaya)', ' DESC');
        $result = $this->db->get()->result();
        $data=array();
        $i=0;
        $sisa=0;
        foreach($result as $r){
            if(++$i==6){
                $i=5;
                $sisa+=$r->biaya;
            }else{
                $data[$i-1]=array('lembaga'=>$r->lembaga,'biaya'=>$r->biaya);
            }
        }
        if($i==5) $data[4]=array('lembaga'=>'Lainnya','biaya'=>$sisa);
        echo json_encode($data);
    }

}
