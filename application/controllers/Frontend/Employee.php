<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Using helper, check role and restirct the controller functions
        check_role('admin');

        $this->load->model('EmployeeModel', 'emp');
        $this->load->model('EmployeeFilesModel', 'empFiles');
        $this->load->library('upload');
        $this->load->library('fpdf/fpdf');
        $this->load->library('image_lib');
    }

    public function index() {
        $data = $this->emp->getDataJoinWithFiles();

        $this->load->view('frontend/employee', ['data' => $data]);
    }


    public function create() {
        $this->load->view('frontend/create');
    }


    // Custom validation function to check if file is uploaded
    public function file_check($str) {
        if (empty($_FILES['image']['name'])) {
            $this->form_validation->set_message('file_check', 'The {field} field is required.');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function store() {

        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('image', 'Image', 'callback_file_check');

        if( $this->form_validation->run() === FALSE ) {
            
            // Validation failed
            $this->session->set_flashdata('message', validation_errors());
            
            $this->load->view('frontend/create');
        }
        else {

            $data = [
                'name'  => $this->input->post('name'),
                'email' => $this->input->post('email'),
            ];


            if( $this->emp->check_email_exists( $data['email'] ) ) {
                $this->session->set_flashdata('message','Employee email already exists');

                $this->load->view('frontend/create');
                return;
            }

            
            $user_id = $this->emp->insertData($data);  
            

            // Create a folder based on the ID
            $upload_path = './uploads/' . $user_id . '/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }



            // Upload Avatar Image
            if ( !empty($_FILES['image']['name']) ) {
                
                $avatar = $this->upload_user_file($user_id, $upload_path, 'image');

                if (!$avatar) {
                    $this->session->set_flashdata('message', 'Image upload failed: ' . $this->upload->display_errors());
                    redirect(base_url('employee/create'));
                }

                // Store the avatar filename in the database
                $data['avatar'] = $avatar;

                // Update avatar in database
                $this->emp->updateData($user_id, $data);
            }




            // Create a PDF on the basis of User form input field
            $response = $this->create_pdf($user_id, $upload_path, $data);

            if( $response === FALSE ) {
                $this->session->set_flashdata('message','Employee form data failed due to some reason.');

                redirect( base_url('employee') );
            }


            // Upload user form file 
            if( $user_id && !empty($_FILES['file']['name']) ) {
                $this->upload_user_file( $user_id, $upload_path, 'file' );   
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

            // Current database data
            $current_data = $this->emp->getDataById( $id );
            $current_name = $current_data->name;
            $current_email = $current_data->email;


            if( $data['name'] === $current_name && $data['email'] === $current_email ) {
                $this->session->set_flashdata('message','Data, is same. Nothing to change');

                redirect( base_url('employee/edit/' . $id ) );
            }


            if( $data['email'] != $current_email && $this->emp->check_email_exists( $data['email'] ) ) {
                $this->session->set_flashdata('message','Employee email already exists');

                redirect( base_url('employee/edit/' . $id ) );
            }


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

        // Form data
        $pdf->Cell(40, 10, 'Name: ' . $data['name']);
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Email: ' . $data['email']);
        $pdf->Ln();

        // Check if avatar exists
        $avatar_path = $upload_path . $data['avatar'];

        if (!empty($data['avatar']) && file_exists($avatar_path)) {
            // Add image to PDF
            $pdf->Image($avatar_path, 10, 50); 
            $pdf->Ln(60); // Adjust line height after image
        } else {
            $pdf->Cell(40, 10, 'No Avatar Uploaded');
            $pdf->Ln();
        }

        // Save the PDF to the specified path
        $pdf_path = $upload_path . '/' . $pdf_filename;
        $pdf->Output('F', $pdf_path); // Save to file

        if (file_exists($pdf_path)) {
            return true;
        } else {
            return false;
        }

    }




    private function upload_user_file($user_id, $upload_path, $type) {
        // File upload configuration
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = ($type == 'image') ? 'jpg|jpeg|png' : 'csv|xls|xlsx';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = ($type == 'image') ? 'avatar_' . $user_id : $_FILES['file']['name'];
    
        $this->upload->initialize($config);
    
        // Determine correct field name for file input
        $field_name = ($type == 'image') ? 'image' : 'file';
    
        if ( ! $this->upload->do_upload($field_name) ) {
            echo $this->upload->display_errors();
            return false;
        } 
        else {
            // Get uploaded file data
            $upload_data = $this->upload->data();
    
            if ($type == 'image') {
                $uploaded_image_path = $upload_data['full_path'];

                // Set up image cropping configuration
                $config_resize = [
                    'image_library'  => 'gd2',   // Use GD2 for image manipulation
                    'source_image'   => $uploaded_image_path, 
                    'maintain_ratio' => TRUE,   // Keep the aspect ratio to prevent distortion
                    'width'          => 50,    // Set the maximum width for the thumbnail
                    'height'         => 50,    // Set the maximum height for the thumbnail
                    'quality'        => '90%',  // High-quality compression
                ];

                // Specify the new image path where cropped image will be saved
                $cropped_image = 'avatar_' . $user_id . '_cropped' . $upload_data['file_ext'];
                $cropped_image_path = $upload_path . $cropped_image;
                $config_resize['new_image'] = $cropped_image_path; // Save cropped image to the new path

                // Initialize image cropping library
                $this->image_lib->initialize($config_resize);

                // Perform the resize operation
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                    return;
                }

                // Clean up memory for next operations
                $this->image_lib->clear();

                unlink($uploaded_image_path); // Delete the original image file

                // Get the cropped image file name
                return $cropped_image; // Return uploaded image name
            } 
            else {
                // Save file info in database
                $emp_data = [
                    'emp_id'    => $user_id,
                    'file_name' => $upload_data['file_name'], // Corrected variable
                ];
    
                $this->empFiles->save_file_info($emp_data);
            }
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