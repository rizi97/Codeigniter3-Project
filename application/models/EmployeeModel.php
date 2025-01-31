<?php

class EmployeeModel extends CI_Model {

    private $tableName = "employee";
    

    public function getData() {
        $this->db->order_by('id', 'DESC');
        return $this->db->get( $this->tableName )->result();
    }


    public function getDataJoinWithFiles() {
        $this->db->select("{$this->tableName}.id, {$this->tableName}.name, {$this->tableName}.email, employee_files.file_name");
        $this->db->from( $this->tableName );
        $this->db->join("employee_files", "{$this->tableName}.id = employee_files.emp_id", "left"); 
        $this->db->order_by("{$this->tableName}.id", 'DESC');

        $query = $this->db->get();
        return $query->result();
    }


    public function getDataById( $id ) {
        return $this->db->get_where( $this->tableName, array('id' => $id) )->row();
    }


    public function insertData($data) {
        $this->db->insert( $this->tableName, $data);
        return $this->db->insert_id();
    }


    public function updateData($id, $data) {
        return $this->db->update( $this->tableName, $data, array('id' => $id ) );
    }


    public function deleteData( $id ) {
        return $this->db->delete( $this->tableName, array('id' => $id));
    }
}