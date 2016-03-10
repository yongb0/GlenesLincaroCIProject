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
    
            $this->form_validation->set_rules('content', 'Content', 'trim|required|min_length[1]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                //$this->register();
            } else {
                $this->message_model->new_message();
                //$this->thank();
                redirect('message/details/'.$this->input->post('to_id'));
            }
        }
        
        $my_id = $this->session->userdata('user_id');
        $recip = $this->get_sidebar_list($my_id);
        $data['recipient_ids'] = $recip;
        
        $data['title']= 'Sign Up';
        $this->load->view('header_view',$data);
        $this->load->view("add_message_view.php", $data);
        $this->load->view('footer_view',$data);
    }
    
    public function remove(){
        $to_id = $this->input->post('to_id');
        if ($this->session->userdata('logged_in') == true) {
            $my_id = $this->session->userdata('user_id');
            
            $this->message_model->msg_delete($my_id, $to_id);
            $return['status'] = 'success';
        } else {
            $return['status'] = 'error';
        }
         echo json_encode($return);
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
            
            $message_details = $this->message_model->get_message_details($my_id, $to_id);
            $message_count = $this->message_model->get_message_count($my_id, $to_id);
            $to_info = $this->user_model->get_user_info($to_id);
            
            $data['to_name'] = $to_info['name'];
            
            $data['to_id'] = $to_info['id'];
            $data['message_info'] = $message_details;
            $data['message_count'] = $message_count;
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
    
    public function reply() {
        if ($this->session->userdata('logged_in') == true) {
            
            $to_id = $this->input->post('to_id');
            $my_id = $this->session->userdata('user_id');
            $reply = $this->input->post('reply');
            
            if ($to_id != '' && $my_id != '' && $reply != '') {
                
                $reply_details_array = $this->message_model->add_reply($to_id, $my_id, $reply);
                $html = '';
                if (count($reply_details_array) > 0) {
                    $timespan = date('m-d-Y',strtotime($reply_details_array[0]['created']));
                    $html .='<div class="row msg_container base_sent">
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_sent">
                                <p>'.$reply_details_array[0]['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2 avatar">
                        </div>
                    </div>';
                }
                
                $return['status'] = 'success';
                $return['message_html'] = $html;
                
            } else {
       
                $return['status'] = 'error';
            }
            echo json_encode($return);
        }
    }
    
    public function loadmore() {
        
      if ($this->session->userdata('logged_in') == true) {
          $limit = $this->input->post('limit');
          $offset = $this->input->post('offset');
          $to_id = $this->input->post('to_id');
          $my_id = $this->session->userdata('user_id');
           
          $this->load->model('message_model');
          $message_info  = $this->message_model->get_more($my_id, $to_id, $offset, $limit);
          $html = '';
           foreach ($message_info as $msg) {
               $image = $msg['image'];
               if ($image!='') {
                    $img = base_url().'images/avatar/'.$image;
                } else {
                    $img = base_url().'images/default-profile.png';
                }
                
                //$timespan = timespan(strtotime($msg['created']), time()) . ' ago';
                $timespan = date('m-d-Y',strtotime($msg['created']));
               
               if ($msg['from_id'] == $my_id) {
                   
                   
                   $html .= '<div class="row msg_container base_sent">
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_sent">
                                <p>'.$msg['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2 avatar">
                        </div>
                    </div>';
               
                } else {
                   
                    $html .= '<div class="row msg_container base_receive">
                        <div class="col-md-2 col-xs-2 avatar">
                            <img src="'.$img.'" class=" img-responsive ">
                        </div>
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_receive">
                                <p>'.$msg['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                            </div>
                        </div>
                    </div>';
              
               
               }
          }
          
          $data['view'] = $html;
          $data['offset'] =$offset +5;
          $data['limit'] =$limit;
      }
      
      echo json_encode($data);
    }
   
    
}
?>