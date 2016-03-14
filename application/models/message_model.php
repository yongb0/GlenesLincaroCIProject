<?php 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Message_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

	public function new_message() {
		
        $to_id = $this->input->post('to_id');
        $from_id = $this->input->post('from_id');
        $content = $this->input->post('content');
        
        $data=array(
            'to_id' => $to_id,
            'from_id'=> $from_id,
            'content'=> $content,
            'seen_sender' => 1,
            'created'=> date('Y-m-d H:i:s')
            );
        $this->db->insert('messages',$data);
	}
    
    public function get_sender_recipient_id($user_id) {
        
       //my old code
      //check for messages you have sent
        //$query = $this->db->query('SELECT to_id as id FROM messages WHERE from_id='.$user_id.' GROUP BY to_id ORDER BY created ASC');
        
       /*  if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            //if you dont have messages send then search if you have any messages received.
            $query = $this->db->query('SELECT from_id as id FROM messages WHERE to_id='.$user_id.' GROUP BY to_id ORDER BY created ASC');
            return $query->result_array();
        } */
      
      $sql =' SELECT DISTINCT
                CASE 
                    WHEN to_id = '.$user_id.' then from_id
                    WHEN from_id = '.$user_id.' then to_id
                END as id
              FROM messages
             ';
      $query = $this->db->query($sql);
      
      $arr = array_filter(array_map('array_filter', $query->result_array()));

      return $arr;
     
    }
    
    public function get_message_details($my_id, $to_id, $offset=0, $limit=5) {
        
        $sql = '
                SELECT m.id, m.to_id, m.from_id, m.content, m.created, q.name, q.image
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
                        ORDER BY created DESC
                        LIMIT '.$offset.', '.$limit.'
                       
                        
                   
        ';
        
        $query = $this->db->query($sql);
        return array_reverse($query->result_array(), true);
    }
    
    public function get_message_count($my_id, $to_id) {
        
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
                        ORDER BY created DESC
        ';
        
        $query = $this->db->query($sql);
        return count($query->result_array());
    }
    
    public function add_reply($to_id, $from_id, $reply) {
        $data=array(
			'to_id' => $to_id,
			'from_id'=> $from_id,
            'content'=> $reply,
            'seen_sender' => 1,
			'created'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('messages',$data);
        
        $insert_id = $this->db->insert_id();
        return $this->get_reply_detail($insert_id);
    }
    
    public function get_reply_detail($id) {
        $sql = '
                SELECT id, from_id, content, created FROM messages 
                WHERE id = '.$id.' 
                LIMIT 1
        ';
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function msg_delete($my_id, $to_id) {
        
        $sql = '
                DELETE FROM messages 
                WHERE (to_id = '.$to_id.' OR from_id = '.$to_id.')
                AND (to_id = '.$my_id.' OR from_id = '.$my_id.')
               ';
               
        $query = $this->db->query($sql);
    }
    
    public function msg_delete_single($id) {
        
        if ($id != '') {
            $sql = '
                    DELETE FROM messages 
                    WHERE id = '.$id.'
                   ';
                   
            $query = $this->db->query($sql);
            return true;
        } else {
            return false;
        }
    }
    
    public function get_more($my_id, $to_id, $offset, $limit) {
        $return_array = $this->get_message_details($my_id, $to_id, $offset, $limit);
        return $return_array;
    }
    
    public function message_seen($id, $current_id) {
        $sql = '
            SELECT to_id FROM messages 
            WHERE id = '.$id.'
        ';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) { 
         
            $to_id = $query->result_array()[0]['to_id'];
            
            if ($to_id == $current_id) {
                
                $updateData=array("seen_recipient" => 1);
                $this->db->where("id", $id);
                $this->db->update("messages", $updateData);
                
            }
        }
    }
    
    public function count_unread_messages($to_id, $my_id) {
        $sql = '
            SELECT count(seen_recipient) as unread
            FROM messages
            WHERE from_id = '.$to_id.'
            AND to_id = '.$my_id.'
            AND seen_recipient = 0
        ';
        
        $count = 0;
        $query = $this->db->query($sql);
         if ($query->num_rows() > 0) { 
            foreach ($query->result_array() as $row) {
                
                //echo $row['seen_recipient'].'<br />';
                $count = $count + $row['unread'];
            }
            return $count;
         }
    }
    
}
?>