<?php

class EmployeeModel extends CI_Model {

    private $tableName = "employee";

    private $cache_key = "employee_list";

    public function __construct() {
        parent::__construct();

        $this->load->driver('cache', array('adapter' => 'file'));
    }
    

    public function getData() {

        $cached_data = $this->cache->get( $this->cache_key );
        
        if ( $cached_data ) {
            return $cached_data;
        }
        else {
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get( $this->tableName );
            $result = $query->result();

            $this->cache->save( $this->cache_key, $result, 600);
            return $result;
        }

    }


    public function getDataJoinWithFiles() {
        $this->db->select("{$this->tableName}.*, employee_files.file_name");
        $this->db->from( $this->tableName );
        $this->db->join("employee_files", "{$this->tableName}.id = employee_files.emp_id", "left"); 
        $this->db->order_by("{$this->tableName}.id", 'DESC');

        $query = $this->db->get();
        return $query->result();
    }


    public function getDataById( $id ) {
        return $this->db->get_where( $this->tableName, array('id' => $id) )->row();
    }


    public function check_email_exists( $email ) {
        return $this->db->get_where( $this->tableName, array('email' => $email) )->row();
    }


    public function insertData($data) {
        $this->db->insert( $this->tableName, $data);

        $this->cache->delete( $this->cache_key );

        return $this->db->insert_id();
    }


    public function updateData($id, $data) {
        $result = $this->db->update( $this->tableName, $data, array('id' => $id ) );

        $this->cache->delete( $this->cache_key );

        return $result;
    }


    public function deleteData( $id ) {
        $result = $this->db->delete( $this->tableName, array('id' => $id));

        $this->cache->delete( $this->cache_key );

        return $result;
    }
}