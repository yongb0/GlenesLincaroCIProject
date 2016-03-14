<?php 
if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    public function index() {
        
        if (($this->session->userdata('user_name')!="")) {
            //$this->welcome();
            redirect('message/home');
        } else {
            $this->login();
            /* $data['title']= 'Home';
            $this->load->view('header_view',$data);
            $this->load->view("registration_view.php", $data);
            $this->load->view('footer_view',$data); */
        }
    }
    
    public function welcome() {
        $data['title']= 'Welcome';
        $this->load->view('header_view', $data);
        $this->load->view('welcome_view.php', $data);
        $this->load->view('footer_view', $data);
    }
    
    public function login() {
        $this->load->library('form_validation');
        if ($this->input->post()) {
            $email=$this->input->post('email');
            $password=md5($this->input->post('pass'));
            $result=$this->user_model->login($email, $password);
            if ($result) {
                //$this->welcome();
                redirect('message/home');
            } else {      
                //$this->index();
                $data['error']= 'Invalid username or password.';
            }
        }   
        $data['title']= 'Login';
        $this->load->view('header_view', $data);
        $this->load->view('login_view.php', $data);
        $this->load->view('footer_view', $data);
        
        if ($this->session->userdata('logged_in') == true) {
            redirect('message/home');
        }
    }
    
    public function register() {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        if ($this->input->post()) {
    
            $this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[5]|xss_clean|callback_username_not_exists');
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
            }
        }
    
        if ($this->session->userdata('logged_in') == true) {
            redirect('message/home');
        }
        
        $data['title']= 'Sign Up';
        $this->load->view('header_view', $data);
        $this->load->view("registration_view.php", $data);
        $this->load->view('footer_view', $data);
    }
    
    public function thank() {
        $data['title']= 'Thank';
        $this->load->view('header_view', $data);
        $this->load->view('thank_view.php', $data);
        $this->load->view('footer_view', $data);
    }
    
    /**
        profile page controller
    */
    public function profile() {
        
        $this->load->helper('html');
        if ($this->session->userdata('logged_in') == true) {
            $data = $this->user_model->get_user_info($this->session->userdata('user_id'));
        } else {
            redirect($this->login());
        }
        $data['title'] = 'profile';
        $this->load->view('header_view', $data);
        $this->load->view('profile_view.php', $data);
        $this->load->view('footer_view', $data);
    }
    
    /*
        Edit user information
        parameter: user id
    */
    public function edit($id = null) {
        $this->load->library('form_validation');
        $this->load->helper('html');
        
        $error = '';
        //die('xxxxxxxxxxxx');
        if ($id==null) {
            redirect('user/profile');
        }
        //you can't edit if it is not your profile.
        if ($this->session->userdata('user_id') != $id) {
             redirect('user/profile');
        }
        if ($this->session->userdata('logged_in') == true) {
            $data = $this->user_model->get_user_info($this->session->userdata('user_id'));
        }
        
        $data['msg'] = '';
        $data['imgSrc'] = '';
        $data['displayname'] = '';
        $data['upload_error'] = '';
        
        if ($this->input->post()) {
            
            $this->form_validation->set_rules('name', 'User Name', 'trim|required|min_length[5]| max_length[20]|xss_clean');
           
            if ($this->form_validation->run() == FALSE) {
                 
            } else {
                $this->user_model->edit_user($this->session->userdata('user_id'));
                redirect('user/profile');
            }
            $user_id = $this->session->userdata('user_id');
           
            if ($this->input->post()) {
                /* if(isset($_POST)){
                        print_r($_POST);
                        die;
                } */
                $upload = $this->profile_upload($user_id, $_FILES, $_POST);
                if ($upload != false ) {
                    //redirect('user/profile');
                 /*   print_r($upload);
                   die; */
                    $data['msg'] = $upload['msg'];
                    $data['imgSrc'] =  $upload['imgSrc'];
                    $data['displayname'] = $upload['displayname'];
                    
                    if (isset($upload['upload_error'])) {
                        $error = $upload['upload_error'];
                    }
                
                } else {
                    $error = $this->upload->display_errors();
                    //die($error);
                }
               
                $data['upload_error'] = $error;
                $data['title'] = 'Edit Info';
                $this->load->view('header_view', $data);
                $this->load->view('edit_view.php', $data);
                $this->load->view('footer_view', $data);
                
                return;
            }
        }
       
        $data['upload_error'] = $error;
        $data['title'] = 'Edit Info';
       /*  print_r($data);
        die; */
        
        $this->load->view('header_view', $data);
        $this->load->view('edit_view.php', $data);
        $this->load->view('footer_view', $data);
        
        
       
    }
    
    /*
    Return upload error message if it fails
    */
    public function profile_upload($user_id, $file, $post) {
            
            $profile_id = 12345;
        /***********************************************************
            0 - Remove The Temp image if it exists
        ***********************************************************/
        if (!isset($post['x']) && !isset($file['image']['name']) ) {
            //Delete users temp image
                $temppath = 'images/'.$profile_id.'_temp.jpeg';
                if (file_exists ($temppath)) { 
                    @unlink($temppath); 
                }
        } 
        
        
        if (isset($file['image']['name'])) {

            list($txt, $ext) = explode('.', $file['image']['name']);
            $newImageName = time().'.'.$ext;
            
            $config = array(
                    'upload_path' => "images",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "768",
                    'max_width' => "1024",
                    'file_name' => $newImageName
                );
            
            $this->load->library('upload', $config);
           
            if ($this->upload->do_upload('image')) {   
                 $imgSrc = $this->upload->file_name; 
               
            } else {
                $data['upload_error'] = $this->upload->display_errors();
            }                
        } 
            
      

        /***********************************************************
            3- Cropping & Converting The Image To Jpg
        ***********************************************************/
            
        if (isset($post['x'])) {
            //die('xxxxxxxxxxxxxxx');
            //the file type posted
                $type = $post['type'];	
            //the image src
                $src = $post['src'];	
                //die($src);
                $finalname = time();	
            
                $this->load->library('image_lib');
                $config2['image_library'] = 'gd2';
                $config2['source_image'] = $_SERVER['DOCUMENT_ROOT'].'GlenesLincaroCIProject/images/'.$src;
                $config2['new_image'] = 'images/avatar';
                $config2['maintain_ratio'] = FALSE;
                $config2['create_thumb'] = TRUE;
                $config2['quality'] = 90;
                $config2['thumb_marker'] = '_thumb';
                $config2['width'] = $post['w'];
                $config2['height'] = $post['h'];
                $config2['x_axis'] = $post['x'];
                $config2['y_axis'] = $post['y'];
         
               
                $this->image_lib->initialize($config2);
                $this->image_lib->resize();
                
                $this->image_lib->clear();
                
                
                $this->image_lib->initialize($config2); 
                $this->image_lib->crop();

                 if ($this->image_lib->crop()) {
                    list($txt, $ext) = explode('.', $src);
                    $new_name = $txt.'_thumb.'.$ext;
                    $temppath = 'images/'.$src;
                    if (file_exists ($temppath)) { 
                        @unlink($temppath); 
                    }
                    $this->user_model->add_image($user_id, $new_name);
                    redirect('user/profile');
                 } else {
                    die($this->image_lib->display_errors('', ''));   
                 }
                                                              
        }// post x
        $data['msg'] = '';
        $data['imgSrc'] = '';
        $data['displayname'] = '';
        
        if (isset($msg)) {
            $data['msg'] = $msg;
        }
        if (isset($imgSrc)) {
            $data['imgSrc'] = $imgSrc;
        }
        if (isset($displayname)) {
            $data['displayname'] = $displayname;
        }
       
        return $data;
        
    }
 
    
    public function logout() {
        $newdata = array(
        'user_id'   =>'',
        'user_name'  =>'',
        'user_email'     => '',
        'logged_in' => FALSE,
        );
        $this->session->unset_userdata($newdata );
        $this->session->sess_destroy();
        $this->login();
    }
    
    /*
        Checks if email exists
        returns false if email exists else true
        parameter: $email
    */
    public function email_not_exists($email) {
        $this->form_validation->set_message('email_not_exists','Email address already exists.');
        if ($this->user_model->email_exists()) {
            return false;
        } else {
            return true;
        }
    }
    
    /*
        Checks if username exists
        returns false if it exists else true
        parameter: username
    */
    public function username_not_exists($username) {
        $this->form_validation->set_message('username_not_exists','Username already exists.');
        if ($this->user_model->username_exists()) {
            return false;
        } else {
            return true;
        }
    }
    
    /*
        Search a user
        if user found returns userdata
    */
    public function search_user() {
        if ($this->session->userdata('logged_in') == true) {
            $current_userid = $this->session->userdata('user_id');
        }
        $keyword = $this->input->post('keyword');
        $users = $this->user_model->GetRow($keyword, $current_userid);
        
        if ($users != false) { 
            $data = array();
            $data['status'] = 'success';
            $data['res'] = $users;
            //print_r($data);
            echo json_encode($data);
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Not found';
             //print_r($data);
            echo json_encode($data);
        }
        
            
    }
    
    
}
?>