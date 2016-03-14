<?php 
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Message extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('message_model');
        $this->load->model('user_model');
    }
    
    /*
        Adds message in db
    */
    public function add() { 
        
        $this->load->library('form_validation');
        // field name, error message, validation rules
        if ($this->input->post()) {
    
            $this->form_validation->set_rules('content', 'Content', 'trim|required|min_length[1]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                //$this->register();
            } else {
                $to_id = $this->input->post('to_id');
                $from_id = $this->input->post('from_id');
                $content = $this->input->post('content');
                $data['content'] = $content;
                if ($to_id == '') {
                    $data['error'] = 'Recipient does not exist';
                }
                
                if ($from_id == '') {
                    $data['error'] = 'Something went wrong.';
                }
                
                if ($content == '') {
                    $data['error'] = 'Please enter your message';
                }
                
                if ($to_id != '' && $from_id != '' && $content != '') {
                    $this->message_model->new_message();
                    redirect('message/details/'.$this->input->post('to_id'));
                }
            }
        }
        
        $my_id = $this->session->userdata('user_id');
        $recip = $this->get_sidebar_list($my_id);
        $data['recipient_ids'] = $recip;
        
        $data['title']= 'Create new message';
        $this->load->view('header_view',$data);
        $this->load->view("add_message_view.php", $data);
        $this->load->view('footer_view',$data);
    }
    
    /*
        Removes message in db
        return json for ajax
    */
    public function remove() {
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
    
    public function delete_single() {
        $id = $this->input->post('id');
        if ($this->session->userdata('logged_in') == true) {
            $delete_single = $this->message_model->msg_delete_single($id);
            if ($delete_single != false) {
               $return['status'] = 'success'; 
            } else {
               $return['status'] = 'error';
            }
            
        } else {
            $return['status'] = 'error';
        }
         echo json_encode($return);
    }
    
    /*
        Called on message list page
    */
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
    
    /*
        Retrieves conversation details
        parameter: $to_id
    */
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
            $data['title']= 'Message - '.$to_info['name'];
            $this->load->view('header_view',$data);
            $this->load->view("message_details_view.php", $data);
            $this->load->view('footer_view',$data);
        
        } else {
            redirect('user/login');
        }
        
    }
    
    /*
        Retrieves users you had conversation with
        To be displayed on sidebar
        parameter: current user's user id
        returns user ids of users
    */
    public function get_sidebar_list($my_id) {
        
        if ($this->session->userdata('logged_in') == true) {
            $my_id = $this->session->userdata('user_id');
        }
        $recipients_or_sender_id = $this->message_model->get_sender_recipient_id($my_id);
        $recip = array();
        
        $x = 0;
        foreach ($recipients_or_sender_id as $row) {
            $recip[] = $this->user_model->get_user_info($row['id']); 
            
            if ($my_id != $row['id']) {     
                $recip[$x]['unread'] = $this->message_model->count_unread_messages($row['id'], $my_id);   
            } else {
                $recip[$x]['unread'] = 0;
            }
            $x++;
        }
        return $recip;
    }
    
    /*
        Saves message reply to the database
        On success returns html to be displayed using ajax
        On failure returns error status
        Returns json data
    */
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
                    $html .='<div class="row msg_container base_sent" id="msg_'.$reply_details_array[0]['id'].'">
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_sent">
                                <p>'.$reply_details_array[0]['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                                 <a href="javascript:void(0)" class="msgDel" onClick="del_single_msg('.$reply_details_array[0]['id'].'); ">x</a>
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
    
    /*
        Retrieves more messages from db
        to be displayed in page using ajax
        returns json encoded html
    */
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
                   
                   
                   $html .= '<div class="row msg_container base_sent" id="msg_'.$msg['id'].'">
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_sent">
                                <p>'.$msg['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                                <a href="javascript:void(0)" class="msgDel" onClick="del_single_msg('.$msg['id'].'); ">x</a>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2 avatar">
                        </div>
                    </div>';
               
                } else {
                   
                    $html .= '<div class="row msg_container base_receive" id="msg_'.$msg['id'].'">
                        <div class="col-md-2 col-xs-2 avatar">
                            <img src="'.$img.'" class=" img-responsive ">
                        </div>
                        <div class="col-md-10 col-xs-10" style="position:relative">
                            <div class="messages msg_receive">
                                <p>'.$msg['content'].'</p>
                                <div class="timeSent">'.$timespan.'</div>
                                <a href="javascript:void(0)" class="msgDel" onClick="del_single_msg('.$msg['id'].'); ">x</a>
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
    
    public function set_seen_message() {
        if ($this->session->userdata('logged_in') == true) {
            
            $id = $this->input->post('msg_id');
            $current_id = $this->session->userdata('user_id');
            
            if ($id != '' && $current_id != '') {
                $this->message_model->message_seen($id, $current_id);
            }
        }
    }
   
    
}
?>