<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function index() {
        $this->load->view('home');
    }


    public function about() {
        $this->load->view('about');
    }
    
    
    public function blog( $id ) {
        $this->load->view('blog', [
            'id' => $id
        ]);
    }

}