<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
        $this->load->model('user_model');
    }
   
    public function add() {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        if ($this->input->post()) {
    
            $this->form_validation->set_rules('content', 'Content', 'trim|required|min_length[5]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                //$this->register();
            } else {
                $this->message_model->new_message();
                //$this->thank();
                redirect('message/home');
            }
        }
       
        $data['title']= 'Sign Up';
        $this->load->view('header_view',$data);
        $this->load->view("add_message_view.php", $data);
        $this->load->view('footer_view',$data);
    }
    
    public function home() {
        
        $user_id = $this->session->userdata('user_id');
        $my_recipients_id = $this->message_model->get_my_recipients($user_id);
        $recip = array();
        foreach ($my_recipients_id as $row) {
            $recip[] = $this->user_model->get_user_info($row['to_id']);
            
        }
        
        /* print_r($recip);
        die; */
  
        
        
        $data['recipient_ids'] = $recip;
        $data['title']= 'SMeesage List';
        $this->load->view('header_view',$data);
        $this->load->view("message_list_view.php", $data);
        $this->load->view('footer_view',$data);
        
    }
   
    
}
?>