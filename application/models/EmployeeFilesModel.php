<?php

class EmployeeFilesModel extends CI_Model {

    private $tableName = "employee_files";
    
    
    public function getDataById( $id ) {
        return $this->db->get_where( $this->tableName, array('emp_id' => $id) )->row();
    }

    public function save_file_info($data) {
        return $this->db->insert( $this->tableName, $data);
    }

    public function deleteData( $id ) {
        return $this->db->delete( $this->tableName, array('emp_id' => $id));
    }
}