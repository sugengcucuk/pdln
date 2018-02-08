<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jumlah extends CI_Controller {

    function __construct()
    {
        parent ::__construct();
        if (!$this->is_logged_in()) {
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

    public function get_handle_negara($id_user)
    {
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
        return $list_negara;
    }

    public function task()
    {
        $id_user = $this->session->user_id;
        $data_user = $this->db->get_where('m_user', array('UserID' => $id_user))->row();
        $level = $data_user->level;
        $where = "";
        if ($level == LEVEL_PEMOHON) {
            $where = array('m_pdln.status' => '1');
            $this->db->where('m_pdln.unit_pemohon',$id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('m_pdln.status' => '2');
            $this->db->where('m_pdln.unit_fp', $id_user);
        } else {
            $this->db->where_not_in('m_pdln.status', array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'));
        }
        $this->db->select('COUNT(*) AS jml',false)
            ->from('m_pdln')
            ->where($where);
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function progress()
    {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        if ($level == LEVEL_PEMOHON) {
            $this->db->where('m_pdln.status <', "11");
            $this->db->where('m_pdln.unit_pemohon =', $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $this->db->where('m_pdln.status <', "11");
            $this->db->where('m_pdln.unit_fp =', $id_user);
        }
        $this->db->select('COUNT(*) AS jml',false)
                 ->from('m_pdln');
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function retur()
    {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        $handle_negara = $this->get_handle_negara($id_user);
        if ($level == LEVEL_PEMOHON) {
            $this->db->where('m_pdln.status =', "0");
            $this->db->where('m_pdln.unit_pemohon =', $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $this->db->where('m_pdln.status =', "12");
            $this->db->where('m_pdln.unit_fp =', $id_user);
            $this->db->where_in('negara', $handle_negara);
        }
        if ($level == LEVEL_PEMOHON) {
            $this->db->select('COUNT(*) AS jml',false)
                 ->from('m_pdln')
                 ->join('m_user as user1','user1.UserID = m_pdln.unit_pemohon', 'left')
                 ->join('m_unit_kerja_institusi as unit_kerja','unit_kerja.ID = user1.unitkerja', 'left')
                 ->join('m_kegiatan','m_kegiatan.ID = m_pdln.id_kegiatan', 'left');
        }else if  ($level == LEVEL_FOCALPOINT) {
            $this->db->select('COUNT(*) AS jml',false)
                 ->from('m_pdln')
                 ->join('m_user as user2','user2.UserID = m_pdln.unit_fp', 'left')
                 ->join('m_unit_kerja_institusi as unit_kerja','unit_kerja.ID = user2.unitkerja', 'left')
                 ->join('m_kegiatan','m_kegiatan.ID = m_pdln.id_kegiatan', 'left');
        }
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function done()
    {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        $this->db->where_in('m_pdln.status',array(11,13,14,15));
        if ($level == LEVEL_PEMOHON) {
            $where = array('m_pdln.unit_pemohon' => $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $where = array('m_pdln.unit_fp' => $id_user);
        }
        $this->db->select('COUNT(*) AS jml',false)
            ->from('m_pdln')
            ->where($where);
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function archive()
    {
        $id_user = $this->session->user_id;
        $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
        $where = "";
        if ($level == LEVEL_PEMOHON) {
            $this->db->where('m_pdln.status =', "200");
            $this->db->where('m_pdln.unit_pemohon =', $id_user);
        }else if ($level == LEVEL_FOCALPOINT) {
            $this->db->where('m_pdln.status =', "200");
            $this->db->where('m_pdln.unit_fp =', $id_user);
        }
        $this->db->select('COUNT(*) AS jml',false)
                 ->from('m_pdln');
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function request()
    {
        $this->db->select('COUNT(*) AS jml',false);
        $this->db->where('is_request', 1);
        $this->db->where('ModifiedBy', $this->session->userdata('user_id'));
        $this->db->from('m_kegiatan');
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }

    public function help()
    {
        $this->db->select('COUNT(*) AS jml',false);
        $this->db->where('status', 0);
        $this->db->where('is_read_backend', 0);
        $this->db->where('help', 1);
        $this->db->from('t_tiket');
        $result=$this->db->get()->row();
        $output = array(
            "jml" => ($result->jml ? $result->jml:0)
        );
        echo json_encode($output);
    }
}
