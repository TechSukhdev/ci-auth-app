<?php 
	class RestModal extends CI_Model
	{     
	    
	    function  AddToken($data)
		{
			$this->db->insert('auth', $data);
	        $q = $this->db->insert_id();
	        return $q;
		}

		function updateToken($data,$where)
	    {
	          return $this->db->update('auth',$data,$where);
	    }

	    function  deleteToken($where)
	    {
	         return $this->db->delete('auth',$where);
	    }
	    function checkToken($token)
	    {
	        $q = $this->db->get_where('auth',$token);
	        $check = $q->num_rows();
	        if($check>0)
	        {
	        	return $q->result_array()[0];
	        }
	        return false;
	    }
	    function checkUserToken($where)
	    {
	    	 $q = $this->db->get_where('auth',$where);
	        $check = $q->num_rows();
	        if($check>0)
	        {
	        	return $q->row();
	        }
	        return false;
	    }
	     // call this function if yout want to get only single row data
    public function RowData($table,$coloum='*',$where='',$joins=array()){
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        
        // get row data with join if need
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }

        $q = $this->db->get();
        if($q->num_rows()){
          return $q->row();
        }
        return false;
    }

    }
?>