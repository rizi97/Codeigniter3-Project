<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('StudentModel', 'std');
        
    }

    public function studentName() {
        
        // $this->studentModel = new StudentModel();
        
        // $student = $this->studentModel->student_data();

        $student = $this->std->get_student_data();

        echo "Student Name: " . $student;
    }


    public function studentInfoByID( $id ) {
        $student = $this->std->get_student_info( $id );

        echo "Student Information: " . $student;
    }

}