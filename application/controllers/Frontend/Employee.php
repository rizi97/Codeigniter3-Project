<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('EmployeeModel', 'emp');
        $this->load->model('EmployeeFilesModel', 'empFiles');
        $this->load->library('upload');
        $this->load->library('fpdf/fpdf');
    }

    public function index() {
        $data = $this->emp->getDataJoinWithFiles();

        $this->load->view('frontend/employee', ['data' => $data]);
    }


    public function create() {
        $this->load->view('frontend/create');
    }


    public function store() {

        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required');

        if( $this->form_validation->run() === FALSE ) {
            $this->create();
        }
        else {
            $data = [
                'name'  => $this->input->post('name'),
                'email' => $this->input->post('email'),
            ];
    
            
            $user_id = $this->emp->insertData($data);  


            // Create a folder based on the ID
            $upload_path = './uploads/' . $user_id . '/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }


            // Create a PDF on the basis of User form input field
            $response = $this->create_pdf($user_id, $upload_path, $data);

            if( $response === FALSE ) {
                $this->session->set_flashdata('message','Employee form data failed due to some reason.');

                redirect( base_url('employee') );
            }


            // Upload user form file 
            if( $user_id && !empty($_FILES['file']['name']) ) {
                $this->upload_user_file( $user_id, $upload_path );   
            }


            $this->session->set_flashdata('message','Employee data added against this user: ' . $data['name']);

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

        // Fetch employee files table data against specific user
        $emp_files_data = $this->empFiles->getDataById( $id );

        if ( $emp_files_data ) {
            $this->empFiles->deleteData( $id );     // delete employee file table entries
        }


        // Delete employee table entry
        $this->emp->deleteData($id); 

        // Delete a folder based on the user_ID
        $upload_path = './uploads/' . $id . '/';
        if (is_dir($upload_path)) {
            $this->delete_directory($upload_path);
        }


        $this->session->set_flashdata('message','Employee + relevant data sucessfully deleted.');
        
        redirect( base_url('employee') );
    }



    private function create_pdf($user_id, $upload_path, $data) {
        $pdf_filename = 'form_data_' . $user_id . '.pdf';

        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Form data (replace with actual form data)
        $form_data = 'Name: ' . $data['name'] . "\n";
        $form_data .= 'Email: ' . $data['email'] . "\n";

        // Write form data into the PDF
        $pdf->MultiCell(0, 10, $form_data);

        // Save the PDF to the specified path
        $pdf_path = $upload_path . '/' . $pdf_filename;
        $pdf->Output('F', $pdf_path);  // Save to file

        if (file_exists($pdf_path)) 
            return true;
        else 
            return false;
    }



    private function upload_user_file($user_id, $upload_path) {
        // File upload configuration
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'csv|xls|xlsx';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = $_FILES['file']['name'];

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            echo $this->upload->display_errors();
        }
        else {
            // Get file data
            $file_data = $this->upload->data();

            // Save file info in database
            $emp_data = [
                'emp_id'   => $user_id,
                'file_name' => $file_data['file_name'],
            ];
            
            $this->empFiles->save_file_info($emp_data);
        }
    }

    

    private function delete_directory($dir) {
        // Get all files and directories inside the folder
        $files = array_diff(scandir($dir), array('.', '..'));

        // Loop through files and subdirectories
        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                // If it's a directory, delete recursively
                $this->delete_directory($filePath);
            } else {
                // If it's a file, delete it
                unlink($filePath);
            }
        }

        // After deleting everything inside, remove the directory itself
        return rmdir($dir);
    }
}