<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MyAcl  {
    function __construct() {
    }
    public function auth(){

        $CI =& get_instance();

        if (!isset($CI->session))
        { # Sessions are not loaded
            $CI->load->library('session');
        }

        if (!isset($CI->router))
        { # Router is not loaded
            $CI->load->library('router');
        }

        // get uri
        $module = $CI->router->fetch_module();
        $class = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        // get session user id
        $id_user =  $CI->session->userdata('user_id');
        $data_user = $CI->db->get_where('m_user', array('UserID' => $id_user))->row();

        // whitelist public uri
        $whitelist = [
            "auth/auth/login",
            "auth/auth/index",
            "auth/auth/logout",
            "dashboard/home/index",
            "dashboard/home/list_dashboard",
            "dashboard/home/print_permohonan",
            "page/faq/index",
            "page/manual/index",
            "page/tentang/index",

            // Exception, another hooks
            "layanan/customfile/pdf",
            "layanan/modify/edit_wizard",
            "layanan/modify/list_pemohon",
            "layanan/modify/is_pemohon",
            "layanan/modify/get_data_permohonan_exist",
            "layanan/modify/get_detail_keg",
            "layanan/modify/get_file_path",
            "layanan/modify/get_kegiatan",
            "layanan/modify/get_file_kegiatan",
            "layanan/modify/get_file_pemohon",
            "dashboard/home/get_log_catatan",
            "layanan/modify/get_list_peserta",
            "layanan/modify/submit_permohonan",

            "service/data/post",
            "service/data/download",
            "service/data/getaccesstoken",
            "service/data/verifytoken",

            "esign/generate/qrcode",
            "master/kegiatan/jenis_kegiatan",
            "master/kegiatan",
            
            "kotak_surat/approval/download"
            
        ];

        // http://localhost/pdln/layanan/customfile/pdf?t=sp&c=2017&id=8&f=sp_pdln_8.pdf
        // make sure only valid user can download the file
        // get id on query string check author on m_pdln
        $record_id = $CI->input->get('id');
        $filename = $CI->input->get('f');
        $type = $CI->input->get('t');
        $restricted_type = array("sp", "pdln", "laporan");

        if ($module == 'layanan'
            && $class == 'customfile'
            && isset($record_id)
            && isset($type)
            && in_array($type, $restricted_type)) {
            $this->check_row_restriction ($record_id, $id_user);
        }

        // http://localhost/pdln/dashboard/home/print_permohonan/8
        $print_permohonan_id = $CI->uri->segment(4, null);

        if ($module == 'dashboard'
            && $class == 'home'
            && $method == 'print_permohonan'
            && isset($print_permohonan_id)) {

            $this->check_row_restriction ($print_permohonan_id, $id_user);
        }
                // SUGENG SEMENTARA DIMATIKAN
        $wizard_permohonan_id = $CI->uri->segment(4, null);
        if ($module == 'layanan'
            && $class == 'modify'
            && $method == 'edit_wizard'
            && isset($print_permohonan_id)) {
            $this->check_row_restriction ($wizard_permohonan_id, $id_user);
        }
        if ($module == 'kotak_surat'
            && $class == 'modify'
            && $method == 'edit_wizard'
            && isset($print_permohonan_id)) {
            $this->check_row_restriction ($wizard_permohonan_id, $id_user);
        }

        // check is resource is public
        $uri = "{$module}/${class}/{$method}";
        if (in_array($uri, $whitelist)) {
            return; // no need to check role access
        }

        // get access based on user role
        $role_access = $CI->db->query("
            SELECT ur.UserID, ur.RoleID, r.NamaRole, m.MenuID, m.MenuName, m.URI, AccView, AccCreate, AccUpdate, AccDelete
                FROM `t_user_role` ur
                LEFT JOIN `m_role` r ON ur.RoleID = r.RoleID
                LEFT JOIN `t_role_menu` rm ON r.RoleID = rm.RoleID
                LEFT JOIN `m_menu` m ON m.MenuID = rm.MenuID
                WHERE ur.UserID={$id_user}
                AND (AccView=1 OR AccCreate=1 OR AccUpdate=1 OR AccDelete=1)
                AND r.Status=1 AND m.URI LIKE '{$module}/{$class}%'");

        // check query error
        if (! $role_access){
            $msg_error = $CI->db->error();
            log_message("error", $msg_error);
            show_error("Maaf, telah terjadi kesalahan di sisi server, harap menghubungi Admin ", 500, "Error");
            return;
        }

        $is_access_valid = false;

        // check is uri valid to access
        foreach ($role_access->result() as $row)
        {
            $uri = explode('/', $row->URI);

            if (count($uri) < 3) continue;

            // loosy-mode
            if ( $uri[2] == '*'
                || ($uri[2] == $method)){
                $is_access_valid = true;
                break;
            }

            // Strict-mode, need to input all method to database
            // if ( ($uri[2] == '*' && empty($method))
            //     || ($uri[2] == $method)){
            //     $is_access_valid = true;
            //     break;
            // }
        }

        // $is_ajax_request = $CI->input->is_ajax_request();
        // if ($is_access_valid == false && $is_ajax_request == false){
        //     // SUGENG SEMENTARA DIMATIKAN UNTUK MENU BARU FOCALPOINT INBOX
        //     if ($is_access_valid == false){
        //         show_error("Anda tidak memiliki akses terhadap halaman atau data di halaman ini. {$module}/{$class}/{$method}", 403, "Forbidden");
        //     }

        //     // this method need to adjust / modify client script to handle http status code 403 or 500
        //     if ($is_access_valid == false && $is_ajax_request){
        //         echo '<script type="text/javascript">document.location="http://www.google.com";</script>';
        //         exit(0);
        //     }
        // }
    }

    function check_row_restriction($record_id, $user_id, $additional_message = '') {
        $CI =& get_instance();
        $used_id = $CI->session->userdata('user_id');


        $user_login = $CI->db->query("SELECT * from m_user where UserID =".$used_id."")->row();
        $instansi = $user_login->instansi;

        $user_supervisi = $CI->db->query("SELECT * from m_user where instansi =".$instansi."")->result();
        // $user_supervisi = $this->db->get_where('m_user', array('instansi' => $instansi))->result();
        $row_user_id = [];
        foreach ($user_supervisi as $val) {
            $row_user_id[] = $val->UserID;   
        }


        if(is_pemohon( $CI->session->userdata('user_id'))){
            $response = "unit_pemohon in";
        }
        else{
            $response = "unit_fp in ";
        }

        $result = $CI->db->query("SELECT COUNT(*) as `found` FROM `m_pdln` WHERE ".$response." (".implode(',',$row_user_id) .")" );
        
        if (($result && (int)$result->row()->found == 0)  && ($user_login->level != 27) ){
            show_error("Anda tidak memiliki hak akses untuk melihat file surat permohonan {$additional_message}", 403, "Forbidden");
        }

    }
}
?>
