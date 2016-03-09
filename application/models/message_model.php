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
    
    public function get_message_details($my_id, $to_id){
        
        $sql = '
                SELECT m.to_id, m.from_id, m.content, m.created, q.name, q.image
                  FROM 
                    (
                      SELECT msg.to_id, msg.from_id, msg.created, user.name, user.image
                        FROM messages msg 
                        LEFT JOIN user ON user.id = msg.from_id
                        WHERE 
                        (to_id = '.$to_id.' OR from_id = '.$to_id.') AND (to_id = '.$my_id.' OR from_id = '.$my_id.')
                        GROUP BY to_id, from_id
                    ) q JOIN messages m
                        ON q.to_id = m.to_id
                        AND q.from_id = m.from_id
                   
        ';
        
        $query = $this->db->query($sql);
    
        return $query->result_array();
    }
    
    public function add_reply($to_id, $from_id, $reply) {
        $data=array(
			'to_id' => $to_id,
			'from_id'=> $from_id,
            'content'=> $reply,
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('messages',$data);
    }
    
}
?>