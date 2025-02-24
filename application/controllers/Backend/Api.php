<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/API_Controller.php';

class Api extends API_Controller {

    public function __construct() {
        parent::__construct();

        // check_role('admin');

        $this->load->model('EmployeeModel', 'empM');
    }


    public function employees_get() {
        $users = $this->empM->getData();
        $this->response($users);
    }


    public function employee_get_id($id) {
        $user = $this->empM->getDataById($id);
        if ($user) {
            $this->response($user);
        } else {
            $this->response(['error' => 'User not found'], 404);
        }
    }


    public function employee_insert() {
        $input = json_decode($this->input->raw_input_stream, true);
        
        $user_email = trim( $input['email'] );

        if( $this->empM->check_email_exists( $user_email ) ) {
            $this->response(['error' => 'User email already exists'], 400);
            
            return;
        }


        $user_id = $this->empM->insertData($input);

        if ($user_id) {
            $this->response(['message' => 'User created', 'id' => $user_id], 201);
        } else {
            $this->response(['error' => 'Failed to create user'], 400);
        }
        
    }


    public function employee_update($id) {
        $input = json_decode($this->input->raw_input_stream, true);
        $updated = $this->empM->updateData($id, $input);
        if ($updated) {
            $this->response(['message' => 'User updated']);
        } else {
            $this->response(['error' => 'Failed to update user'], 400);
        }
    }


    public function employee_delete($id) {
        $deleted = $this->empM->deleteData($id);
        if ($deleted) {
            $this->response(['message' => 'User deleted']);
        } else {
            $this->response(['error' => 'Failed to delete user'], 400);
        }
    }
}
