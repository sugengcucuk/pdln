<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemohon extends CI_Controller {
	function __construct()
	{
		parent ::__construct();
	}

	/*Start Master Pemohon Management*/
	public function index()
	{
		$where = array('Status'=>'1');
		$this->crud_ajax->init('r_institution','ID',NULL);
        $this->crud_ajax->setExtraWhere($where);
        $data['list_instansi']= $this->crud_ajax->get_data();
		$data['theme'] 		= 'pdln';
        $data['page'] 		= 'manajemen/v_pemohon';
		$data['title'] 		= 'Master Pemohon';
		$data['title_page'] = 'Manajemen Data Pemohon';
		$data['breadcrumb'] = 'Pemohon';
		page_render($data);
	}

	public function pemohon_list()
	{
		$this->crud_ajax->init('m_pemohon','id_pemohon',array('m_pemohon.id_pemohon'=>'asc'));
		$list = $this->crud_ajax->get_datatables();
        $data = array();
		if(isset($_POST['start'])){
        	$no = $_POST['start'];
		} else {
			$no=0;
		}
        foreach ($list as $pemohon) {
            $row = array();
			$row[] = $pemohon->id_pemohon;
            $row[] = ++$no.'.';
			$row[] = ucwords($pemohon->nama);
            $row[] = $pemohon->nip_nrp;
			$row[] = $pemohon->nik;
			$row[] = $pemohon->jabatan;//isset($pemohon->jabatan) ? ucwords($pemohon->jabatan) : '';
			//$row[] = $pemohon->email;
			//$row[] = $pemohon->telp;
			if($pemohon->status == 1) {
				$status = "Aktif";
				$label = "primary";
			} else {
				$status = "Tidak Aktif" ;
				$label = "danger";
			}
			$row[] = '<span class="label label-'.$label.'">'.$status.'</span>';
			if($this->ion_auth->is_allowed(41,'update')) {
				$row[] ='<button type="button" id="edit_pemohon" title="Edit" class="btn btn-xs purple"><i class="fa fa-edit"></i> </button>';
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

	public function get_data_pemohon()
	{
    	$response = array();
    	$id = $this->input->post('ID');
    	$this->db->select('*');
    	$this->db->from('m_pemohon');
    	$this->db->where('id_pemohon',$id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		$row = $query->row();
    		$response = array(
                            'nama'=>$row->nama,
							'nip_nrp'=>$row->nip_nrp,
							'nik'=>$row->nik,
							'jabatan'=>$row->jabatan,
							'instansi'=>$row->id_instansi,
							'instansi_lainnya'=>$row->instansi_lainnya,
							'email'=>$row->email,
							'telp'=>$row->telp,
							'is_active'=>$row->status,
    						'status'=>TRUE
    				);
    	}else{
    		$response['status'] = FALSE;
    	}
    	echo json_encode($response);
	}

	public function pemohon_save(){
        $this->pemohon_validate();
        echo json_encode(array("status" => TRUE));
    }

	 /**
	 * @method private _validate handle validation data users
	 * @return json output status on form or modal
	 */
	private function pemohon_validate()
	{
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
		$nama = $this->input->post('nama');
		$nip_nrp = $this->input->post('nip_nrp');
		$nik = $this->input->post('nik');
		$paspor = $this->input->post('paspor');
		$jabatan = $this->input->post('jabatan');
		$email = $this->input->post('email');
		$telp = $this->input->post('telp');
		$instansi_lainnya = $this->input->post('instansi_lainnya');
		$instansi = $this->input->post('instansi');
        if($nama === '')
        {
            $data['inputerror'][] = 'Nama';
            $data['error_string'][] = 'Nama Pemohon tidak boleh kosong';
            $data['status'] = FALSE;
        }
		if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }else if($data['status'] === TRUE){
        	if($this->input->post('method') === "tambah"){
	        	//insert to table m_pemohon
				if(!$this->isExist_NamaPemohon($nik)){
					$data['inputerror'][] = 'NIK';
					$data['error_string'][] = 'Maaf NIK Pemohon sudah digunakan';
					$data['msg'] = "Maaf NIK Pemohon sudah digunakan";
					$data['status'] = FALSE;
					echo json_encode($data);
                    exit();
				}else{
					$this->crud_ajax->init('m_pemohon','id_pemohon',null);
					$data_save_pemohon = array(
											'nama' => $nama,
											'nip_nrp' => $nip_nrp,
											'nik' => $nik,
											'paspor' => $paspor,
											'jabatan' => $jabatan,
											'email' => $email,
											'telp' => $telp,
											'id_instansi'=>$instansi,
											'is_pns'=>(strlen($nip_nrp) < 8) ? 0 : 1,
											'instansi_lainnya'=>$instansi_lainnya,
											'status'=>$this->input->post('opt_status')
										);
					$insert_id_u = $this->crud_ajax->save($data_save_pemohon);
				}
	        }else if($this->input->post('method') === "ubah"){
	        	$ID = $this->input->post('ID');
	        	//update to table m_pemohon
	        	$this->crud_ajax->init('m_pemohon','id_pemohon',null);
	        	$data_save_pemohon = array(
	        							'nama' => $nama,
											'nip_nrp' => $nip_nrp,
											'nik' => $nik,
											'paspor' => $paspor,
											'jabatan' => $jabatan,
											'email' => $email,
											'telp' => $telp,
											'id_instansi'=>$instansi,
											'is_pns'=>(strlen($nip_nrp) < 8) ? 0 : 1,
											'instansi_lainnya'=>$instansi_lainnya,
											'status'=>$this->input->post('opt_status')
										);
	        	$where_pemohon = array('id_pemohon'=>$ID);
	        	$affected_row_u = $this->crud_ajax->update($where_pemohon,$data_save_pemohon);
                if($affected_row_u < 1){
                    $data['status'] = FALSE;
                    $data['msg'] = "Gagal Update Data Pemohon";
                    echo json_encode($data);
                    exit();
                }
	        }
        }
    }

	public function isExist_NamaPemohon($NamaPemohon)
	{
    	$this->db->where('nik',$NamaPemohon);
    	$query = $this->db->get('m_pemohon');
    	$result = true;
    	if($query->num_rows() > 0){
    		$result = false;
    	}
    	return (bool) $result;
    }

	public function pemohon_delete()
	{
		$ID = $this->input->post('ID');
		$response = array();
		$this->crud_ajax->init('m_pemohon','id_pemohon',null);
		if(!$this->crud_ajax->delete_by_id($ID))
			$response['success'] = FALSE;
		else
			$response['success'] = TRUE;
		echo json_encode($response);
	}

	public function process_mass_upload_pemohon()
	{
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
                    INTO TABLE temp_pemohon
                    FIELDS TERMINATED BY ',' ENCLOSED BY '\"'
					LINES TERMINATED BY '\n'
                    IGNORE 1 LINES
                    (
						nama,
						nip_nrp,
						nik,
						paspor,
						jabatan,
						email,
						telp,
						id_instansi,
						instansi_lainnya,
						last_update,
						update_by,
						status
                    )
                    SET
                        last_update=UNIX_TIMESTAMP(NOW()),
						update_by=".$this->session->userdata('user_id').",
						status=1;
                    ";
					$this->db->query($sql);
					$sql = "INSERT IGNORE INTO m_pemohon(
								nama,
								nip_nrp,
								nik,
								paspor,
								jabatan,
								email,
								telp,
								is_pns,
								id_instansi,
								instansi_lainnya,
								last_update,
								update_by,
								status
							)
							SELECT
								nama,
								nip_nrp,
								nik,
								paspor,
								jabatan,
								email,
								telp,
								IF(LENGTH(nip_nrp) < 8,0,1),
								id_instansi,
								instansi_lainnya,
								last_update,
								update_by,
								status
							FROM
								temp_pemohon";
					$this->db->query($sql);
                    $sql = "TRUNCATE TABLE temp_pemohon";
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

	public function process_mass_upload_pemohon_xlsx(){
		require_once APPPATH.'third_party/PHPExcel.php';
        $MIME_types = array(
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
		$result=false;
		$sheet_data=array();
        if(!empty($_FILES['fileUpload']['name']) && in_array($_FILES['fileUpload']['type'],$MIME_types)){
            if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
                $fileName=addslashes($_FILES['fileUpload']['tmp_name']);
				$file_type	= PHPExcel_IOFactory::identify($fileName);
				$objReader	= PHPExcel_IOFactory::createReader($file_type);
				$objPHPExcel = $objReader->load($fileName);
				$sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				print_r($sheet_data);
				try{
					$this->db->trans_begin();
					$values=array();
					$i=0;
					foreach($sheet_data as $d){
						if(++$i>1){
							$values[]=array(
								'nama'=>$d[0],
								'nip_nrp'=>$d[1],
								'nik'=>$d[2],
								'paspor'=>$d[3],
								'jabatan'=>$d[4],
								'email'=>$d[5],
								'telp'=>$d[6],
								'id_instansi'=>$d[7],
								'instansi_lainnya'=>$d[8],
								'last_update'=> UNIX_TIMESTAMP(NOW()),
								'update_by'=> $this->session->userdata('user_id'),
								'status'=>'1'
							);
						}
					}
					$this->db->insert_batch('temp_pemohon',$values);
					$sql = "INSERT IGNORE INTO m_pemohon(
								nama,
								nip_nrp,
								nik,
								paspor,
								jabatan,
								email,
								telp,
								is_pns,
								id_instansi,
								instansi_lainnya,
								last_update,
								update_by,
								status
							)
							SELECT
								nama,
								nip_nrp,
								nik,
								paspor,
								jabatan,
								email,
								telp,
								IF(LENGTH(nip_nrp) < 8,0,1),
								id_instansi,
								instansi_lainnya,
								last_update,
								update_by,
								status
							FROM
								temp_pemohon";
					$this->db->query($sql);
                    $sql = "TRUNCATE TABLE temp_pemohon";
                    $this->db->query($sql);
                    $this->db->trans_commit();
                    $result=true;
                }
                catch(Exception $e){
                    $this->db->trans_rollback();
                }
            }
        }
        echo json_encode($result?$sheet_data:'error');
	}

	public function template_download(){
		$file=site_url('assets/template/pdln_template_pemohon_01.csv');
		echo $file;
	}
	/*End Master Pemohon Management*/
}
