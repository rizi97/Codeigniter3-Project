<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function check_role( $role ) {
    $CI =& get_instance();
    
    if ( $CI->session->userdata('role') !== $role ) {
        redirect('login');
        exit;
    }
}
