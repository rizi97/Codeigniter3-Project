<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
        parent::__construct();

		$this->load->model('AuthModel', 'auth');
	}

	public function index()
	{
		// check user is login or not
		$this->check_user_login();
		
		$this->load->view('frontend/auth/register');
	}

	public function register()
	{
		// check user is login or not
		$this->check_user_login();

		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('password','Password','required');

		if ( FALSE == $this->form_validation->run() ) {
			$this->load->view('frontend/auth/register');
		}

		else {
			$data = [
				'username'	=> $this->input->post('username'),
				'email'		=> $this->input->post('email'),
				'password'	=> $this->input->post('password'),
				'role'		=> $this->input->post('role'),
			];


			if ( $this->auth->register_user( $data ) ) {
				
				$this->session->set_flashdata([
					'message'	=> 'Registration successful. You can log in now.',
					'type'		=> 'success'
				]);

				redirect('login');
			} 
			
			else {

				$this->session->set_flashdata([
					'message'	=> 'Registration failed. Try again.',
					'type'		=> 'failed'
				]);

				redirect('/');
			}
		}
	}



	public function login() {

		// check user is login or not
		$this->check_user_login();

		$this->load->view('frontend/auth/login');
	}


	public function login_verify() {

		// check user is login or not
		$this->check_user_login();


        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ( FALSE == $this->form_validation->run() ) {
            $this->load->view('frontend/auth/login');
        } 
		else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->auth->get_user( $username );

            if ( $user && password_verify($password, $user->password ) ) {
                
				$session_data = [
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role,
                    'logged_in' => TRUE,
					'type'		=> 'success'
                ];

                $this->session->set_userdata($session_data);


                if ( $user->role == 'admin' ) {
                    redirect('employee');
                } 
				else {
                    redirect('home');
                }
            } 
			
			else {
				$this->session->set_flashdata([
					'message'	=> 'Invalid username or password',
					'type'		=> 'failed'
				]);

                redirect('login');
            }
        }
    }


	public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }


	private function check_user_login() {
		if( $this->session->userdata('logged_in') ) {
			if ( $this->session->userdata('role') == 'admin' ) {
				redirect('employee');
			} 
			else {
				redirect('home');
			}
		}
	}

}
