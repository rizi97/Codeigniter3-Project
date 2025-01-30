<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('EmployeeModel', 'emp');

    }

    public function index() {

        $data = $this->emp->getData();

        $this->load->view('frontend/employee', ['data' => $data]);
    }


    public function create() {
        $this->load->view('frontend/create');
    }


    public function store() {

        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required');

        if( $this->form_validation->run() == FALSE ) {
            $this->create();
        }
        else {
            $data = [
                'name'  => $this->input->post('name'),
                'email' => $this->input->post('email'),
            ];
    
            
            $this->emp->insertData($data);  

            $this->session->set_flashdata('message','Employee sucessfully added.');

            redirect( base_url('employee') );
        }
    }


    public function edit( $id ) {
        $data = $this->emp->getDataById( $id );

        $this->load->view('frontend/edit', ['data'=> $data]);
    }


    public function update( $id ) {

        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required');

        if( $this->form_validation->run() == FALSE ) {
            $this->edit( $id );
        }
        else {
            $data = [
                'name' => $this->input->post('name'),
                'email'=> $this->input->post('email'),
            ];

            $this->emp->updateData( $id, $data );

            $this->session->set_flashdata('message','Employee sucessfully updated.');

            redirect( base_url('employee') );
        }
    }


    public function delete( $id ) {
        $this->load->view('frontend/delete', ['id' => $id]);
    }


    public function deleteEmployee( $id ) {
        if( $this->emp->deleteData($id) ) {

            $this->session->set_flashdata('message','Employee sucessfully deleted.');
            
            redirect( base_url('employee') );
        }
        else {
            echo "Deleting issue";
        }
    }
}