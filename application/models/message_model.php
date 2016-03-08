<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

	public function new_message() {
		$data=array(
			'to_id' => $this->input->post('to_id'),
			'from_id'=>$this->input->post('from_id'),
			'content'=>$this->input->post('content'),
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('messages',$data);
	}
    
    public function get_my_recipients($user_id) {
        
        $query = $this->db->query('SELECT to_id FROM messages WHERE from_id='.$user_id.' GROUP BY to_id ORDER BY created ASC');
        return $query->result_array();
		
    }
    
}
?>