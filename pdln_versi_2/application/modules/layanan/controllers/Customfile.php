<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Baru.php
 * Handle Permohonan Baru PDLN Proccess
 * @package layanan
 * @author Guntar
 * @version 1.0.0
 * @date_create 23/11/2016
 * */
class Customfile extends CI_Controller {

  public function pdf()
  {
    $type = $this->input->get('t');
    $create_date = $this->input->get('c');
    $id = $this->input->get('id');
    $filename = $this->input->get('f');

    $file = get_file_pdln1($type,date('Y-m-d', strtotime($create_date)),$id,$filename);

    if (file_exists($file)) {
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        // change inline to attachment if you want to download it instead
        header('Content-Disposition: inline; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
    else echo "Can not read the file";
  }
}
