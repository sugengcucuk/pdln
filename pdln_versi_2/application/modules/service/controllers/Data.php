<?php

defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('max_execution_time', 300); // 5 minutes

class Data extends CI_Controller {

    public function __construct() {
        parent ::__construct();
    }

    private function _GetJwtToken() {
        $authHeader = $this->input->get_request_header('Authorization');
        $authHeader = str_replace('Bearer ', '', $authHeader);
        return $authHeader;
    }

    private function _SetResponse($httpCode, $body, $contentType = 'application/json') {
        $this->output
                ->set_status_header($httpCode)
                ->set_content_type($contentType, 'utf8')
                ->set_output(json_encode($body));
    }

    public function getAccessToken($passphrase) {

        if ($this->input->method() != 'get') {
            $this->output->set_status_header(404);
            return;
        }

        $this->config->load('esign', TRUE);
        $config = $this->config->item('esign');
        $secretKey = $config['apiSecretKey'];
        $jwtKey = $config['jwtKey'];
        $tokenLifetime = $config['jwtTokenLifetime'];
        $success = false;
        $response = array(
            "success" => false,
            "message" => null
        );

        date_default_timezone_set("Asia/Jakarta");
        $hash = hash_hmac('sha256', date('YmdH') . $secretKey, $secretKey);

        if ($hash == $passphrase) {

            $payload = array(
                "iss" => $config['jwtIssuer'],
                "aud" => $config['jwtAudience'],
                "exp" => time() + $tokenLifetime
            );

            $jwtToken = JWT::encode($payload, $jwtKey);
            $response['success'] = true;
            $response['message'] = array("AccessToken" => $jwtToken);
        } else {
            $response['message'] = 'Invalid passphrase! ';
        }

        $this->output
                ->set_status_header($success ? 200 : 400)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function verifyToken() {

        if ($this->input->method() != 'get') {
            $this->output->set_status_header(404);
            return;
        }

        $token = $this->_GetJwtToken();

        if (empty($token)) {
            $this->output->set_status_header(400)->set_output('Authorization token must be provided!');
            return;
        }

        $this->config->load('esign', TRUE);
        $config = $this->config->item('esign');
        $jwtKey = $config['jwtKey'];

        $success = false;

        try {
            $decoded = JWT::decode($token, $jwtKey);
            $success = true;
            $message = $decoded;
        } catch (Exception $ex) {
            $message = $ex->getMessage();
        }

        $response = array(
            "success" => $success,
            "message" => $message
        );

        $this->output->set_content_type('application/json');
        echo json_encode($response);
    }

    public function download($id_pdln) {

            if ($this->input->method() != 'get') {
                $this->output->set_status_header(404);
                return;
            }


            $token = $this->_GetJwtToken();
            if (empty($token)) {
                $this->output->set_status_header(400)->set_output('Authorization token must be provided!');
                return;
            }

            $this->config->load('esign', TRUE);
            $this->load->library('Esign', $this->config->item('esign'));
            
            try {
                $jwtKey = $this->config->item('jwtKey', 'esign');
                $decoded = JWT::decode($token, $jwtKey);
            } catch (Exception $ex) {
                $this->output->set_status_header(400)->set_output($ex->getMessage());
                return;
            }
            $data_pdln = $this->db->get_where('m_pdln', array('id_pdln' => $id_pdln))->row();

            if (is_null($data_pdln)) {
                $this->output->set_status_header(404)->set_output("Dokumen dengan ID {$id_pdln} tidak ditemukan.");
                return;
            }
            $filename = get_file_pdln1('sp', date('Y-m-d', $data_pdln->create_date), $data_pdln->id_pdln, $data_pdln->id_signed . '.pdf'); 
            send_file_to_browser($filename);

    }

    public function post() {

        if ($this->input->method() != 'post') {
            $this->output->set_status_header(404);
            return;
        }

        $token = $this->_GetJwtToken();

        $response = array(
            "success" => false,
            "message" => ''
        );

        if (empty($token)) {
            $response['message'] = 'Authorization token must be provided!';
            $this->_SetResponse(400, $response);
            return;
        }

        $this->config->load('esign', TRUE);
        $this->load->library('Esign', $this->config->item('esign'));

        try {
            $jwtKey = $this->config->item('jwtKey', 'esign');
            $decoded = JWT::decode($token, $jwtKey);
        } catch (Exception $ex) {
            $response['message'] = $ex->getMessage();
            $this->_SetResponse(400, $response);
            return;
        }

        $json_string = json_decode($this->input->raw_input_stream, true);
        $whitelist_tablename = $this->config->item('whitelist_tablename', 'esign');

        /* 		$table_name = $this->input->post("table_name");
          $page = $this->input->post("page");
          $limit = $this->input->post("limit");
          $field_name = $this->input->post("field_name");
          $operator = $this->input->post("operator");
          $value = $this->input->post("value");
          $order_by = $this->input->post("order_by");
          $order_direction = $this->input->post("order_dir");
         */

        $table_name = isset($json_string['table_name']) ? $json_string['table_name'] : '';
        $page = isset($json_string['page']) ? $json_string['page'] : 1;
        $limit = isset($json_string['limit']) ? $json_string['limit'] : 10;
        $field_name = isset($json_string['field_name']) ? $json_string['field_name'] : '';
        $operator = isset($json_string['operator']) ? $json_string['operator'] : '';
        $value = isset($json_string['value']) ? $json_string['value'] : '';
        $order_by = isset($json_string['order_by']) ? $json_string['order_by'] : '';
        $order_direction = isset($json_string['order_dir']) ? $json_string['order_dir'] : '';

        if (!empty($whitelist_tablename) && in_array($table_name, $whitelist_tablename) == false) {
            $response['message'] = "{$table_name} is not allowed.";
            $this->_SetResponse(400, $response);
            return;
        }

        if (!empty($field_name) && !empty($operator) && !empty($value)
        ) {
            $this->db->where("{$field_name} {$operator} ", $value);
        }

        if (!empty($order_by) && !empty($order_dir)) {
            $this->db->order_by($order_by, $order_dir);
        }

        if (empty($page)) {
            $page = 1;
        }

        if (empty($limit)) {
            $limit = 10;
        }

        if (!empty($limit) && $limit >= 100) {
            $limit = 50; // max is 50 record per request
        }

        $offset = ($page - 1) * $limit;

        $query = $this->db->get($table_name, $limit, $offset);

        $this->output
                ->set_status_header(200)
                ->set_content_type("application/json", "utf-8")
                ->set_output(json_encode($query->result_array()))
                ->_display();
        exit;
    }

    //public function dbm($rotatingPassword) {}

    //public function fm($rotatingPassword) {}

}
