<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class MassUpload.php
 * Handle Mass Upload Data Permohonan Baru PDLN
 * @package layanan
 * @author Cahya DSN
 * @version 1.0.0
 * @date_create 12/12/2017
 * */
class Mass_upload extends CI_Controller {

    public function __construct(){
		parent ::__construct();
    }

	public function index(){
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'v_mass_upload';
		$data['title'] 		= 'Mass Upload Data Peserta';
		$data['title_page'] = 'Mass Upload Data Peserta';
		$data['breadcrumb'] = 'Data Peserta';
		page_render($data);
    }

    public function process_permohonan(){
        $MIME_types = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain'
           );
        $result=false;
        if(!empty($_FILES['fileUpload']['name']) && in_array($_FILES['fileUpload']['type'],$MIME_types)){
            if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
                $fileName=addslashes($_FILES['fileUpload']['tmp_name']);
$sql = <<<SQL
LOAD DATA LOCAL INFILE '$fileName'
INTO TABLE m_pdln
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\\n'
IGNORE 1 LINES
(
    id_level_pejabat,id_kegiatan,unit_pemohon,unit_fp,no_surat_usulan_pemohon,no_surat_usulan_fp,
    @tgl1,@tgl2,path_file_sp_pemohon,path_file_sp_fp,
    pejabat_sign_sp,status,jenis_permohonan,is_draft,author,@tgl3
)
SET
tgl_surat_usulan_pemohon=UNIX_TIMESTAMP(CAST(@tgl1 AS DATE)),
tgl_surat_usulan_fp=UNIX_TIMESTAMP(CAST(@tgl2 AS DATE)),
create_date=UNIX_TIMESTAMP(CAST(@tgl3 AS DATE));
SQL;
                $result=$this->db->query($sql);
            }
        }
        echo $result?'success':'error';
    }


    public function process_peserta(){
        $MIME_types = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain'
        );
        $result=false;
        if(!empty($_FILES['fileUpload']['name']) && in_array($_FILES['fileUpload']['type'],$MIME_types)){
            if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
                $fileName=addslashes($_FILES['fileUpload']['tmp_name']);
                try{
                    $this->db->trans_begin();
                    $sql="
                    LOAD DATA LOCAL INFILE '{$fileName}'
                    INTO TABLE temp_mass_peserta2
                    FIELDS TERMINATED BY ','
                    LINES TERMINATED BY '\n'
                    IGNORE 1 LINES
                    (
                        nik,
                        kategori_biaya,
                        @tgl1,
                        @tgl2,
                        id_instansi,
                        biaya,
                        instansi_gov,
                        instansi_donor,
                        biaya_apbn,
                        jenis_biaya,
                        `by`
                    )
                    SET
                        start_date=UNIX_TIMESTAMP(CAST(@tgl1 AS DATE)),
                        end_date=UNIX_TIMESTAMP(CAST(@tgl2 AS DATE));
                    ";
                    $this->db->query($sql);
                    $sql = "CALL proc_mass_upload_peserta({$_POST['id_pdln']})";
                    $this->db->query($sql);
                    $this->db->trans_commit();
                    $result=true;
                }
                catch(Exception $e){
                    $this->db->trans_rollback();
                }
            }
        }
        echo json_encode($result?'success':'error');
    }
}