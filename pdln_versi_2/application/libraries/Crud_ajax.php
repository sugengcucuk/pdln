<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Crud Ajax Library
*
* Version: 1.0
*
* Author: Guntar Pardede
*         guntarp4rdede@gmail.com
*         @guntarp4rdedes
* 
* Created:  17.08.2016
*
* Description: Simple Common Method CRUD model database function
*
* Requirements: CI Ver 3.x.x or above
*
*/

class Crud_ajax {
    /**
     * CI Singleton
     *
     * @var object
     */
    public $CI;

    /**
     * Tabel Name
     *
     * @var string
     **/
    public $table;
    /**
     * column name
     *
     * @var string
     **/
    public $column = array();
    /**
     * join set for generate first datatable
     *
     * @var array
     **/
    public $join_set = array(); 
    /**
     * extra where for generate first datatable
     *
     * @var array
     **/
    public $extra_where = array();
    /**
     * extra where_not_in
     *
     * @var array
     **/
    public $extra_where_not_in = array();
    /**
     * field_not_in 
     *
     * @var array
     **/
    public $field_not_in;
    /**
     * order set
     *
     * @var array
     **/
    public $order_set = array();
    /**
     * primary field
     *
     * @var string
     **/
    public $select_set;
    /**
     * primary field
     *
     * @var string
     **/
    public $primary_field;

    /**
     * __construct
     *
     * @author Guntar
     */
    public function __construct(){
        $this->CI =& get_instance();        
        $this->CI->load->helper('url');
        $this->CI->load->library('session');        
    }
    /**
     * init
     *
     * @author Guntar
     */
    public function init($table, $id, $order){
        $this->extra_where = null;
        $this->extra_where_not_in = null;
        $this->field_not_in = null;
        $this->join_set = null;     
        $this->order_set = null;
        $this->select_set = null;

        $this->primary_field = $id;
        $this->table = $table;
        $this->set_column();
        $this->order_set = $order;

    }
    /**
     * _get_datatables_query
     *   
     * @access private
     * @param   
     * @return  
     */
    private function _get_datatables_query() {
        if(!empty($this->select_set)){            
            $this->CI->db->select($this->select_set);
        }
        if(!empty($this->extra_where)){            
            $where = $this->extra_where;
            $this->CI->db->where($where, $where[key($where)]);
        }
        if(!empty($this->extra_where_not_in)){            
            $where = $this->extra_where_not_in;
            $this->CI->db->where_not_in($this->field_not_in, $where);
        }
        if(!empty($this->join_set)){
            $joins = $this->join_set;
            foreach ($joins as $key=>$value) {       
                $this->CI->db->join($key, $value[0],$value[1]);
            }
        }

        $table_name = $this->table;

        $this->CI->db->from($this->table);
        $i = 0;
        foreach ($this->column as $item) { // loop column
            if(isset($_POST['search']['value'])) {// if datatable send POST for search            
                if($i===0) {// first loop                
                    $this->CI->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->CI->db->like($table_name.'.'.$item, $_POST['search']['value']);
                }else{
                    $this->CI->db->or_like($table_name.'.'.$item, $_POST['search']['value']);
                } 
                if(count($this->column) - 1 == $i) //last loop
                    $this->CI->db->group_end(); //close bracket
            }
            $this->column[$i] = $item; // set column array variable to order processing
            $i++;
        }         
        if(isset($_POST['order'])) {// here order processing        
            $this->CI->db->order_by($this->column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order_set)){
            $order = $this->order_set;
            $this->CI->db->order_by(key($order), $order[key($order)]);
        }
    }
    public function get_datatables(){
        $this->_get_datatables_query();
        if(isset($_POST['length'])){
        if($_POST['length'] != -1){
        $this->CI->db->limit($_POST['length'], $_POST['start']);}
        }
        $query = $this->CI->db->get();
        
        return $query->result();
    } 
    public function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->CI->db->get();
        return $query->num_rows();
    } 
    public function count_all(){
        // $this->CI->db->from($this->table);
        $this->_get_datatables_query();
        return $this->CI->db->count_all_results();
    } 
    public function get_by_id($id){
        $this->CI->db->from($this->table);
        $this->CI->db->where($this->primary_field,$id);
        $query = $this->CI->db->get();
 
        return $query->row();
    }
    public function get_by_field($field,$value){
        $this->CI->db->from($this->table);
        $this->CI->db->where($field,$value);
        $query = $this->CI->db->get();
 
        return $query->row();
    }
    public function setExtraWhere($extra_where){ //in array
        $this->extra_where = $extra_where;
    }
    public function setExtraWhereNotIn($field,$where){ //in array
        $this->extra_where_not_in = $where;
        $this->field_not_in = $field;
    }
    public function setJoinField($field){ //in array
        $this->join_set = $field;
    }
    public function set_select_field($field){ //in array
        $this->select_set = $field;
    }   
    public function get_data(){
        $this->CI->db->from($this->table);
        if(isset($this->select_set)){            
            $this->CI->db->select($this->select_set);
        }
        if(isset($this->extra_where)){
            $where = $this->extra_where;
            $this->CI->db->where($where, $where[key($where)]);            
        }
        if(isset($this->extra_where_not_in)){
            $this->CI->db->where_not_in($this->field_not_in,$this->extra_where_not_in);
        }
        if(isset($this->order_set)){
            $order = $this->order_set;
            $this->CI->db->order_by(key($order), $order[key($order)]);
        }
        $query = $this->CI->db->get(); 
        return $query->result();
    }
    public function login($username){        
        $this->CI->db->from('users');
        $this->CI->db->where('username',$username);
        
        $query = $this->CI->db->get();

        return $query;
    }
    public function logout(){
        $this->CI->session->unset_userdata( array('id','username', 'user_group', 'user_id','name') );
        $this->CI->session->sess_destroy();
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                session_start();
            }
        $this->CI->session->sess_regenerate(TRUE);
        redirect(base_url());
    }
    public function remove_session(){
        $this->CI->session->unset_userdata( array('id','username', 'user_group', 'user_id','name') );
        $this->CI->session->sess_destroy();
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                session_start();
            }
        $this->CI->session->sess_regenerate(TRUE);        
    }
    public function set_session($username){
        $this->CI->db->select('users.id as uid,users_groups.group_id,users.username,users.first_name');
        $this->CI->db->from($this->table);
        $this->CI->db->where('username',$username);        
        $this->CI->db->join('users_groups','users_groups.user_id = users.id');
        $query = $this->CI->db->get();
        $session_data = array(
                            'username'=>$username,
                            'name'=>$query->row()->first_name,
                            'user_group'=>$query->row()->group_id,
                            'user_id'=>$query->row()->uid
                    );
        $this->CI->session->set_userdata($session_data);
    }
    public function save($data){
        $this->CI->db->trans_start();
        $this->CI->db->insert($this->table, $data);
        $insert_id = $this->CI->db->insert_id();

        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE){
            $this->CI->db->trans_rollback();
        }else{
            $this->CI->db->trans_commit();
        }
        return $insert_id;
    }
    public function update($where, $data){
        $this->CI->db->trans_start();
        $this->CI->db->update($this->table, $data, $where);
        $affected_rows = $this->CI->db->affected_rows();
        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE){
            $this->CI->db->trans_rollback();
        }else{
            $this->CI->db->trans_commit();
        }
        return $affected_rows;
    }
    public function delete_by_id($id){
        $this->CI->db->trans_start();
        $this->CI->db->where($this->primary_field, $id);
        $this->CI->db->delete($this->table);
        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE){
            $this->CI->db->trans_rollback();
            $result = false;
        }else{
            $this->CI->db->trans_commit();
            $result = true;
        }
        return (bool) $result;
    }
    public function set_column(){
        if(isset($this->table)){
            $this->column = $this->CI->db->list_fields($this->table);
        }
    }
    /**
     * logged_in
     *
     * @return bool
     * @author Mathew
     **/
    public function logged_in(){
        return (bool) $this->CI->session->userdata('user_id');
    }
    /**
     * logged_in
     *
     * @return integer
     * @author jrmadsen67
     **/
    public function get_user_id()
    {
        $user_id = $this->CI->session->userdata('user_id');
        if (!empty($user_id))
        {
            return $user_id;
        }
        return null;
    }
    public function last_query()
    {
        return $this->CI->db->last_query();
    }
}