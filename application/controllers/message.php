<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
    }
   
    public function add() {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        if ($this->input->post()) {
    
           /*  $this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[5]|xss_clean|callback_username_not_exists');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[5]| max_length[20]|xss_clean');
            $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email|callback_email_not_exists');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                //$this->register();
            } else {
                $this->user_model->add_user();
                //$this->thank();
                redirect($this->login());
            } */
        }
       
        $data['title']= 'Sign Up';
        $this->load->view('header_view',$data);
        $this->load->view("add_message_view.php", $data);
        $this->load->view('footer_view',$data);
    }
   
    
}
?>