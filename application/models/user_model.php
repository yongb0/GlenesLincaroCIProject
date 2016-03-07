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
			/* $updateData=array("last_login_time" => date('Y-m-d H:i:s'));
				
			//die($rows->id.'ddddddddddddd');
			$this->db->where("id",$rows->id);
			$this->db->update("user",$updateData);  */
           
			$this->session->set_userdata($newdata);
            return true;            
		}
		return false;
    }
	public function add_user()
	{
		$data=array(
			'username'=>$this->input->post('user_name'),
			'name'=>$this->input->post('name'),
			'email'=>$this->input->post('email_address'),
			'password'=>md5($this->input->post('password')),
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('user',$data);
	}
}
?>