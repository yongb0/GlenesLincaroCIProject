<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }
    public function index()
    {
        if(($this->session->userdata('user_name')!=""))
        {
            //$this->welcome();
            redirect('message/home');
        }
        else{
            $this->login();
            /* $data['title']= 'Home';
            $this->load->view('header_view',$data);
            $this->load->view("registration_view.php", $data);
            $this->load->view('footer_view',$data); */
        }
    }
    public function welcome()
    {
        $data['title']= 'Welcome';
        $this->load->view('header_view',$data);
        $this->load->view('welcome_view.php', $data);
        $this->load->view('footer_view',$data);
    }
    public function login()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $email=$this->input->post('email');
            $password=md5($this->input->post('pass'));

            $result=$this->user_model->login($email,$password);
            if ($result) {
                //$this->welcome();
                redirect('message/home');
            } else {      
                //$this->index();
                    $data['error']= 'Invalid username or password.';
            }
        }
        
        $data['title']= 'Login';
        $this->load->view('header_view',$data);
        $this->load->view('login_view.php', $data);
        $this->load->view('footer_view',$data);
        
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
        $this->load->view('header_view',$data);
        $this->load->view("registration_view.php", $data);
        $this->load->view('footer_view',$data);
    }
    public function thank() {
        $data['title']= 'Thank';
        $this->load->view('header_view',$data);
        $this->load->view('thank_view.php', $data);
        $this->load->view('footer_view',$data);
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
        $this->load->view('header_view',$data);
        $this->load->view('profile_view.php', $data);
        $this->load->view('footer_view',$data);
    }
    
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
       
        if ($this->input->post()) {
            
            $this->form_validation->set_rules('name', 'User Name', 'trim|required|min_length[5]| max_length[20]|xss_clean');
           
            if ($this->form_validation->run() == FALSE) {
                 
            } else {
                $this->user_model->edit_user($this->session->userdata('user_id'));
                redirect('user/profile');
            }
            $user_id = $this->session->userdata('user_id');
           
            if ($this->input->post('_method') == 'PUT') {
                $upload_fail = $this->profile_upload_fail($user_id);
                if ($upload_fail == false ) {
                    redirect('user/profile');
                } else {
                    $error = $upload_fail;
                    
                }
            }
            
            
        }
       
        $data['upload_error'] = $error;
        $data['title'] = 'Edit Info';
        $this->load->view('header_view',$data);
        $this->load->view('edit_view.php', $data);
        $this->load->view('footer_view',$data);
        
        
       
    }
    
    /*
    Return upload error message if it fails
    */
    public function profile_upload_fail($user_id){
            
            list($txt, $ext) = explode('.',$_FILES['img']['name']);
            $newImageName = time().'.'.$ext;
            
            $config = array(
                    'upload_path' => "images/avatar",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "768",
                    'max_width' => "1024",
                    'file_name' => $newImageName
                );
            $this->load->library('upload', $config);
            
            if($this->upload->do_upload('img'))
            {
              
                /* $this->load->library('image_lib');
                $config2['image_library'] = 'gd2';
                $config2['source_image'] = $this->upload->upload_path.$this->upload->file_name;
                $config2['new_image'] = 'images/thumbs';
                $config2['maintain_ratio'] = FALSE;
                $config2['create_thumb'] = TRUE;
                $config2['thumb_marker'] = '_thumb';
                $config2['width'] = 200;
                $config2['height'] = 200;
             
                $this->image_lib->initialize($config2);
                $this->image_lib->resize();
                
                $conf_new = array(
                    'image_library' => 'gd2',
                    'source_image' => $this->upload->upload_path.$this->upload->file_name,
                    'create_thumb' => FALSE,
                    'maintain_ratio' => FALSE,
                    'width' => 200,
                    'height' => 200,
                    'x_axis' => 0,
                    'y_axis' => 0
                );

                if (!$this->image_lib->resize()){
                    $this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
                 } */

                $this->user_model->add_image($user_id, $newImageName);
                return false;
            }
            else
            {
            
                $error = $this->upload->display_errors();
                
            }
       
        return $error;
        
    }
 
    
    public function logout()
    {
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
    
    public function email_not_exists($email){
        $this->form_validation->set_message('email_not_exists','Email address already exists.');
        if($this->user_model->email_exists()) {
            return false;
        } else {
            return true;
        }
    }
    
    public function username_not_exists($username){
        $this->form_validation->set_message('username_not_exists','Username already exists.');
        if($this->user_model->username_exists()) {
            return false;
        } else {
            return true;
        }
    }
    
    public function search_user(){
        $keyword=$this->input->post('keyword');
        $data=$this->user_model->GetRow($keyword);        
        echo json_encode($data);
    }
    
    
}
?>