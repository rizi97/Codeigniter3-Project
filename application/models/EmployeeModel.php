<?php

class EmployeeModel extends CI_Model {

    private $tableName = "employee";
    

    public function getData() {
        $this->db->order_by('id', 'DESC');
        return $this->db->get( $this->tableName )->result();
    }


    public function getDataById( $id ) {
        return $this->db->get_where( $this->tableName, array('id' => $id) )->row();
    }


    public function insertData($data) {
        return $this->db->insert( $this->tableName, $data);
    }


    public function updateData($id, $data) {
        return $this->db->update( $this->tableName, $data, array('id' => $id ) );
    }


    public function deleteData( $id ) {
        return $this->db->delete( $this->tableName, array('id' => $id));
    }
}