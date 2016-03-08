<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }
	function login($email,$password)
    {
		$where = '(email="'.$email.'" OR username="'.$email.'")';
		$this->db->where($where);
        $this->db->where("password",$password);
            
        $query=$this->db->get("user");
        if($query->num_rows()>0)
        {
         	foreach($query->result() as $rows)
            {
            	//add all data to session
                $newdata = array(
                	   	'user_id' 		=> $rows->id,
                    	'user_name' 	=> $rows->username,
		                'user_email'    => $rows->email,
	                    'logged_in' 	=> TRUE,
                   );
			}
			//update last login time
			$updateData=array("last_login_time" => date('Y-m-d H:i:s'));
			$this->db->where("id",$rows->id);
			$this->db->update("user",$updateData);
           
			$this->session->set_userdata($newdata);
            return true;            
		}
		return false;
    }
	public function add_user() {
		$data=array(
			'username' => $this->input->post('user_name'),
			'name'=>$this->input->post('name'),
			'email'=>$this->input->post('email_address'),
			'password'=>md5($this->input->post('password')),
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('user',$data);
	}
    
    public function edit_user($user_id){
        $data=array(
			'name' => $this->input->post('name'),
			'birthdate'=>$this->input->post('birthdate'),
			'gender'=>$this->input->post('gender'),
			'hobby'=>$this->input->post('hobby'),
			'modified'=>date('Y-m-d H:i:s')
		);
        /* print_r($data);
        die; */
		$this->db->where("id",$user_id);
		$this->db->update("user",$data);
    }
	
	function email_exists() {
        $email = trim($this->input->post('email_address'));
		$email = strtolower($email);	
	
		$query = $this->db->query('SELECT * FROM user where email="'.$email.'"');
		
		if($query->num_rows() > 0)
			return true;
		else
			return false;
	}
	
	function username_exists() {
        $username = trim($this->input->post('user_name'));
		$username = strtolower($username);	
	
		$query = $this->db->query('SELECT * FROM user where username="'.$username.'"');
		
		if($query->num_rows() > 0)
			return true;
		else
			return false;
	}
	
	function get_user_info($user_id) {
		$query = $this->db->query("SELECT * FROM user WHERE id='$user_id'");
		if ($query->num_rows() > 0) {
			/* foreach ($query->result_array() as $row)
			{
			   echo $row['name'];
			   echo $row['birthdate'];
			   echo $row['username'];
			} */
			
			return $query->result_array()[0];
		}
	}
    
    function add_image($user_id, $newImageName) {
        
        $updateData=array("image" => $newImageName);
		$this->db->where("id",$user_id);
		$return = $this->db->update("user",$updateData);
     
    }
}
?>