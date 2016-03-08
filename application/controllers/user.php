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
            $this->welcome();
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
                $this->welcome();
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
            redirect($this->welcome);
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
        redirect($this->welcome);
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
         
        if ($id==null) {
            redirect($this->profile());
        }
        
        if ($this->session->userdata('logged_in') == true) {
            $data = $this->user_model->get_user_info($this->session->userdata('user_id'));
        }
        
        if ($this->input->post()) {
    
            $this->form_validation->set_rules('name', 'User Name', 'trim|required|min_length[5]| max_length[20]|xss_clean');
           
            if ($this->form_validation->run() == FALSE) {
                 
            } else {
                $this->user_model->edit_user($this->session->userdata('user_id'));
                //$this->thank();
                $this->profile();
            }
        } else {
             $data['title'] = 'Edit Info';
            $this->load->view('header_view',$data);
            $this->load->view('edit_view.php', $data);
            $this->load->view('footer_view',$data);
        }
        
       
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
    
    
}
?>