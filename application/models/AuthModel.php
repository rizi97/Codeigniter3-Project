<?php

class AuthModel extends CI_Model {

    private $tableName = "users";


    public function register_user($data) {

        $data['password'] = password_hash($data["password"], PASSWORD_DEFAULT);

        return $this->db->insert($this->tableName, $data);

    }


    public function get_user( $username ) {
        return $this->db->get_where( $this->tableName, array( "username" => $username ) )->row();
    }

}