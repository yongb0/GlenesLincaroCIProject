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
    
    public function remove($to_id){
        
        if ($this->session->userdata('logged_in') == true) {
            $my_id = $this->session->userdata('user_id');
            
            $this->message_model->msg_delete($my_id, $to_id);
            redirect('message/home');
        } else {
            redirect('user/login');
        }
    }
    
    public function home() {
        
        $my_id = $this->session->userdata('user_id');
        $recip = $this->get_sidebar_list($my_id);
        /* print_r($recip);
        die; */
        $data['recipient_ids'] = $recip;
        $data['title']= 'Message List';
        $this->load->view('header_view',$data);
        $this->load->view("message_list_view.php", $data);
        $this->load->view('footer_view',$data);
        
    }
    
    public function details($to_id) {
        
        if ($this->session->userdata('logged_in') == true) {
        
            $my_id = $this->session->userdata('user_id');
            $recip = $this->get_sidebar_list($my_id);
            
            if ($this->input->post()) {
                if ($to_id != '' && $my_id != '') {
                    $reply = $this->input->post('reply');
                    $this->message_model->add_reply($to_id, $my_id, $reply);
                }
            }
            
            $message_details = $this->message_model->get_message_details($my_id, $to_id);
            $to_info = $this->user_model->get_user_info($to_id);
            
            $data['to_name'] = $to_info['name'];
            
            $data['to_id'] = $to_info['id'];
            $data['message_info'] = $message_details;
            $data['recipient_ids'] = $recip;
            $data['my_id'] = $my_id;
            $data['title']= 'Message List';
            $this->load->view('header_view',$data);
            $this->load->view("message_details_view.php", $data);
            $this->load->view('footer_view',$data);
        
        } else {
            redirect('user/login');
        }
        
    }
    
    public function get_sidebar_list($my_id) {
        
        $recipients_or_sender_id = $this->message_model->get_sender_recipient_id($my_id);
        $recip = array();
        foreach ($recipients_or_sender_id as $row) {
            $recip[] = $this->user_model->get_user_info($row['id']);
            
        }
        
        return $recip;
    }
   
    
}
?>