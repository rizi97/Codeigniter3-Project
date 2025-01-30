<?php

class StudentModel extends CI_Model {

    public function get_student_data() {
        return "Rizwan";
    }


    public function get_student_info( $id ) {
        $student_email = $this->get_student_email( $id );

        return "<br/>Student ID: " . $id . "<br/>Here is the email address: " . $student_email;
    }


    private function get_student_email( $id ) {
        if( $id == 1) {
            return "mianrizwan465@gmail.com";
        }
        else {
            return "expertzrizwan@gmail.com";
        }
    }
}