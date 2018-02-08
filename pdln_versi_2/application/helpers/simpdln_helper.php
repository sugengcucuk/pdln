<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * simpdln helper
 *
 * */
// Global var set

/**
 * @author Guntar
 * Check is user pemohon or not
 * @param $user_id
 * @return type boolean check if user is pemohon or not
 * */
function is_pemohon($user_id) {
    $CI = & get_instance();
    $CI->load->database();

    $CI->db->from('m_user as mu');
    $CI->db->join('m_level as ml', 'mu.level = ml.LevelID');
    $CI->db->where('mu.UserID', $user_id);

    $result = FALSE;
    $group_id = $CI->db->get()->row()->GroupID;
    if ($group_id == 2)
        $result = TRUE;
    return (bool) $result;
}

/**
 * @author Guntar
 * Check is fp weather has assign pemohon or not
 * @param $value for where condition
 * @return type boolean
 * */
function is_has_assign($value) {
    $CI = & get_instance();
    $CI->load->database();

    $CI->db->select('unit_pemohon');
    $CI->db->from('m_pdln as mp');
    $CI->db->where('unit_pemohon', $value);

    $result = TRUE;

    $id_pemohon = $CI->db->get()->row()->unit_pemohon;
    if ($id_pemohon === '' OR $id_pemohon === NULL)
        $result = FALSE;
    return (bool) $result;
}

function get_focal_point_by($id_user) {
    $CI = & get_instance();
    $CI->load->database();
    $CI->db->where('UserID', $id_user);
    $unit_kerja = $CI->db->get('m_user')->row()->unitkerja;

    $CI->db->from('m_unit_kerja_institusi as m_unit');
    $CI->db->where('m_unit.ID', $unit_kerja);
    $id_fp = $CI->db->get()->row()->FocalPoint;
    return $id_fp;
}

// For get id fp by where condition
function get_focal_point_permohonan($where) {
    $CI = & get_instance();
    $CI->load->database();

    $CI->db->from('m_pdln as mp');
    $CI->db->where($where);
    $id_fp = $CI->db->get()->row()->unit_fp;
    return $id_fp;
}

function get_pemohon_id_pdln_by($field, $value) {
    $CI = & get_instance();
    $CI->load->database();

    $CI->db->select('unit_pemohon');
    $CI->db->from('m_pdln as mp');
    $CI->db->where($field, $value);

    $id_pemohon = $CI->db->get()->row()->unit_pemohon;
    if ($id_pemohon !== '' OR $id_pemohon !== NULL)
        return $id_pemohon;
}
function label_tolak()
{
    $alasan_tolak = array(
        array( 'code'=>10 , 'label' =>'Baru'),
        array( 'code'=>20 , 'label' =>'Perpanjangan'),
        array( 'code'=>30 , 'label' =>'Ralat'),
        array( 'code'=>40 , 'label' =>'Pembatalan'),
        array( 'code'=>50 , 'label' =>'Kosong')
    );
 return $alasan_tolak ;
}
function setJenisPermohonan($flag) {
    $status;
    switch ($flag) {
        case "10":
            $status = 'Baru';
            break;
        case "20":
            $status = 'Perpanjangan';
            break;
        case "30":
            $status = 'Ralat';
            break;
        case "40":
            $status = 'Pembatalan';
            break;
        case "50":
            $status = 'Kosong';
            break;
    }
    return $status;
}

function setLabel($flag) {
    $label;
    switch ($flag) {
        case "10":
        case "50":
            $label = 'success';
            break;
        case "20":
        case "30":
            $label = 'info';
            break;
        case "40":
        case "60":
            $label = 'warning';
            break;
        default:
            $label = 'danger';
    }
    return $label;
}

function setStatusPdln($flag) {
    $CI = & get_instance();
    $label ='';
    switch ($flag) {
        case NULL:
        case 0:
        case 1:
            $label = 'Draft';
            break;
        case 2:
            if (is_pemohon($CI->session->user_id))
                $label = 'Dalam Proses Focal Point';
            else
                $label = 'Draft';
            break;
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
            $label = 'Dalam Proses Setneg';
            break;
        case 11:
            $label = 'Disetujui';
            break;
        case 12:
            $label = 'Dikembalikan';
            break;
        case 13:
            $label = 'Diperpanjang';
            break;
        case 14:
            $label = 'Diralat';
            break;
        case 15:
            $label = 'Dibatalkan';
        break;
        case 200:
            $label = 'Arsip';
        break;
    }
    return $label;
}

function setLabelPdln($flag) {
    $label ='';
    switch ($flag) {
        case NULL:
        case 0:
        case 1:
        case 2:
            $label = 'info';
            break;
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
            $label = 'primary';
            break;
        case 11:
            $label = 'success';
            break;
        case 12:
            $label = 'danger';
            break;
        case 13:
            $label = 'warning';
            break;
        case 14:
            $label = 'warning';
            break;
        case 15:
            $label = 'warning';
            break;
        case 200:
            $label = 'warning';
            break;
    }
    return $label;
}

function day_dashboard($time) {
    if ($time == 0 OR $time === NULL)
        $date = "";
    else
        $date = day(date("Y-m-d", $time));
    return $date;
}

function copy_file_pdln($oldfile, $newfile) {
    $path = dirname($newfile);
    if (!is_dir($path)) {
        mkdir($path, 0777, TRUE);
    }

    if (file_exists($oldfile))
        return copy($oldfile, $newfile);
    return FALSE;
}

/**
 * @author  Guntar
 * @version v 1.0
 * @param   $type = pdln|signature|surat_masuk|surat_keluar|laporan|kegiatan|peserta
 * @param   $created_date from insert to database parse to structure dir name, avoid delayed time upload action by user
 * @param   $id is make root dir name like id_pdln,id_surat_masuk,id_surat_keluar,id_user
 * @return boolean TRUE or FALSE default TRUE
 *
 * */
function upload_pdln($type, $id, $new_name, $file_upload, $created_date) {
    $CI = & get_instance();
    //$CI->load->helper('url');

	$CI->load->config('pdln',true);
    //$root = FCPATH . DIRECTORY_SEPARATOR . "simpdln_upload/";
    $root = $CI->config->item('simpel_upload_path','pdln');
    $change = gmdate($created_date, time() + 60 * 60 * 8);
    $split = explode("-", $change, 3);
    $month = strtolower(month($split[1]));
    $year = $split[0];
    $success = TRUE;
    switch ($type) {
        case NULL:
        case "":
            break;
        case "pdln":
        case "sp":
        case "laporan":
            $allowed_types = 'pdf';
            $sub_dir = "pdln/";
            $path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/';
            break;
        case "signature":
            $allowed_types = 'gif|jpeg|jpg|png';
            $sub_dir = "signature/";
            $path = $root . $sub_dir;
            break;
        case "surat_masuk":
            $allowed_types = 'pdf';
            $sub_dir = "umum/surat_masuk/";
            $path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/';
            break;
        case "surat_keluar":
            $allowed_types = 'pdf';
            $sub_dir = "umum/surat_keluar/";
            $path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/';
            break;
        case "kegiatan":
            $allowed_types = 'pdf';
            $mid_path = "pdln/";
            $sub_dir = "doc_kegiatan/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir;
            break;
        case "peserta":
            $allowed_types = 'pdf';
            $mid_path = "pdln/";
            $sub_dir = "doc_peserta/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir;
            break;
    }
    $config['upload_path'] = $path;
    $config['allowed_types'] = $allowed_types;
    $config['max_size'] = '2000';
    $config['overwrite'] = TRUE;
    $config['file_ext_tolower'] = TRUE;
    $config['remove_spaces'] = TRUE;
    // $config['detect_mime']          = TRUE;
    // $config['mod_mime_fix']         = TRUE;
    $config['file_name'] = $new_name;
    $CI->load->library('upload', $config);
    if (!is_dir($path)) {
        mkdir($path, 0777, TRUE);
    }
    $CI->upload->initialize($config);
    $status_upload=$CI->upload->do_upload($file_upload);
    if (!$status_upload)
        $success = FALSE;
    else
        $success = TRUE;
    return (bool) $success;
}

function get_file_pdln($type, $created_date, $id, $filename) {
    return sprintf(base_url() . "layanan/customfile/pdf?t=%s&c=%s&id=%d&f=%s&_dc=%s", $type, $created_date, $id, $filename, time());
}

function get_file_pdln1($type, $created_date, $id, $filename) {
    $CI = & get_instance();
    $CI->load->helper('url');

	$CI->load->config('pdln');
    //$root   = base_url()."simpdln_upload/";
     $root = $CI->config->item('simpel_upload_path');
    //$root = FCPATH . DIRECTORY_SEPARATOR . "simpdln_upload/";

    $change = gmdate($created_date, time() + 60 * 60 * 8);
    $split = explode("-", $change, 3);
    $month = strtolower(month($split[1]));
    $year = $split[0];

    switch ($type) {
        case NULL:
        case "":
            break;
        case "pdln":
        case "laporan":
        case "sp":
            $mid_path = "pdln/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $filename;
            break;
        case "signature":
            $sub_dir = "signature/";
            $path = $root . $sub_dir . $filename;
            break;
        case "surat_masuk":
            $sub_dir = "umum/surat_masuk/";
            $sub_path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/' . $filename;
            break;
        case "surat_keluar":
            $sub_dir = "umum/surat_keluar/";
            $path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/' . $filename;
            break;
        case "kegiatan":
            $mid_path = "pdln/";
            $sub_dir = "doc_kegiatan/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir . $filename;
            break;
        case "peserta":
            $mid_path = "pdln/";
            $sub_dir = "doc_peserta/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir . $filename;
            break;
    }
    return $path;
}

function send_file_to_browser($fullpath, $mime_type = 'application/pdf'){
    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for internet explorer
    header("Content-Type: {$mime_type}");
    //header('Expires: 0');
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($fullpath));
    //header("Content-Disposition: attachment; filename={$filename}");
    readfile($fullpath);
    die();
    exit;
}

function recreate_dir_pdln($old_dir, $new_dir_path, $id_pdln) {

}

function delete_file_pdln($type, $id, $created_date, $filename) {
    $CI = & get_instance();
    // $CI->load->helper('url');

	$CI->load->config('pdln');
    //$root = FCPATH . DIRECTORY_SEPARATOR . "simpdln_upload/";
    // $root = "/Users/thinkpad/Downloads/FireShot/";
	$root = $CI->config->item('simpel_upload_path');;

    $change = gmdate($created_date, time() + 60 * 60 * 8);
    $split = explode("-", $change, 3);
    $month = strtolower(month($split[1]));
    $year = $split[0];

    $path;
    switch ($type) {
        case NULL:
        case "":
            break;
        case "pdln":
        case "laporan":
            $mid_path = "pdln/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $filename;
            break;
        case "sp":
            $mid_path = "pdln/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $filename;
            break;
        case "signature":
            $sub_dir = "signature/";
            $path = $root . $sub_dir . $filename;
            break;
        case "surat_masuk":
            $sub_dir = "umum/surat_masuk/";
            $sub_path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/' . $filename;
            break;
        case "surat_keluar":
            $sub_dir = "umum/surat_keluar/";
            $path = $root . $year . '/' . $month . '/' . $sub_dir . $id . '/' . $filename;
            break;
        case "kegiatan":
            $mid_path = "pdln/";
            $sub_dir = "doc_kegiatan/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir . $filename;
            break;
        case "peserta":
            $mid_path = "pdln/";
            $sub_dir = "doc_peserta/";
            $path = $root . $year . '/' . $month . '/' . $mid_path . $id . '/' . $sub_dir . $filename;
            break;
    }
    if (!empty($path)) {
        if (!unlink($path))
            $success = FALSE;
        else
            $success = TRUE;
    } else
        $success = FALSE;

    return (bool) $success;
}

function setStatus_doc($flag) {
    $status = "Lainnya";
    switch ($flag) {
        case "0":
            $status = 'Dikembalikan Pemohon';
            break;
        case "1":
            $status = 'Pemohon';
            break;
        case "2":
            $status = 'Focal Point';
            break;
        case "3":
            $status = 'Analis';
            break;
        case "4":
            $status = 'Kasubag';
            break;
        case "5":
            $status = 'Kabag';
            break;
        case "6":
            $status = 'Kepala Biro';
            break;
        case "7":
            $status = 'Sesmen';
            break;
        case "8":
            $status = 'Mensesneg';
            break;
        case "9":
            $status = 'TU Sesmen';
            break;
        case "10":
            $status = 'TU Mensesneg';
            break;
        case "11":
            $status = 'Disetujui';
            break;
        case "12":
            $status = 'Dikembalikan';
            break;
        case "13":
            $status = 'DiRalat';
            break;
        case "14":
            $status = 'DiPerpanjang';
            break;
        case "15":
            $status = 'DiBatalkan';
            break;
        case "200":
            $status = 'Arsip';
            break;
    }
    return $status;
}


function setStatus($flag) {
    $status;
    switch ($flag) {
        case 0:
            $status = 'Tidak Aktif';
            break;
        case 1:
            $status = 'Aktif';
            break;
    }
    return $status;
}

function setLabelStatus($flag) {
    $label;
    switch ($flag) {

        case 0:
            $label = 'danger';
            break;
        case 1:
            $label = 'info';
            break;
    }
    return $label;
}

function convert_to_rupiah($angka) {
    return 'Rp. ' . strrev(implode('.', str_split(strrev(strval($angka)), 3)));
}

function convert_to_number($rupiah) {
    return intval(preg_replace('/[^0-9]/', '', $rupiah));
}

function get_id_status_approval($status) {
    if ($status == "Focalpoint") {
        return 2;
    }else if ($status == "Analis") {
        return 3;
    } else if ($status == "Kasubag") {
        return 4;
    } else if ($status == "Kabag") {
        return 5;
    } else if ($status == "Kepala Biro") {
        return 6;
    } else if ($status == "Sesmen") {
        return 7;
    } else if ($status == "Mensesneg") {
        return 8;
    }
}

/* End of file simpdln_helper.php */
/* Location: ./system/application/helpers/simpdln_helper.php */