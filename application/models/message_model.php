<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

	public function new_message() {
		/* $data=array(
			'username' => $this->input->post('user_name'),
			'name'=>$this->input->post('name'),
			'email'=>$this->input->post('email_address'),
			'password'=>md5($this->input->post('password')),
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('message',$data); */
	}
    
}
?>