<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	private $page="login";
        
	public function index()
	{
            if($this->session->userdata('is_logged'))
            {
                redirect('company',"refresh");
            }
            else
            {
                $data["page"]=  $this->page;
                $this->load->view('login',$data);
            }
	}
        
        public function login_validation()
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username','Username','required|trim|callback_validate_credentials');
            $this->form_validation->set_rules('password','Password','required|trim');

            if($this->form_validation->run())
            {
                redirect('company',"refresh");
            }
            else
            {
                $this->index();
            }
        }
        
        public function validate_credentials()
        {
            $this->load->model('authenticate');
            if($userDetails=$this->authenticate->validateUser())
            {
                $this->session->set_userdata($userDetails);
            }
            else
            {
                $this->form_validation->set_message('validate_credentials','Incorrect Username/Password');
                return false;
            }
        }
        
        public function logout()
        {
            $this->session->sess_destroy();
            //$this->session->unset_userdata($userSessionData);
            redirect('/login','refresh');
        }
}
