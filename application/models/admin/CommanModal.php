<?php
/**
Created by Sukhdev Pawar (sukhde.pawar97@gmail.com) for all type of comman operation  in CI. You can extend it in your any modal than can be call his methods.
///*** Ping me on mail if you want something else ////***
 * 
Thanks
 */

class CommanModal extends CI_Model
 {
  
    // if you want to add single row data in a table 
    public function AddData($table,$data){
        $this->db->insert($table, $data);
        $q = $this->db->insert_id();
        return $q;
    }

    // if you want to add multiple row with single query so pass multi-D array in it
    public function AddMultiple($table,$data){
        $this->db->insert_batch($table, $data);
        $q = $this->db->insert_id();
        return $q;
    }
	
	 // if you want to add multiple row with single query so pass multi-D array in it
    public function UpdateMultiple($table,$data,$index,$where){
		$this->db->where($where);
        $this->db->update_batch($table, $data, $index);
        return true;
    }

    // update a table data using this function
    public function updateData($table,$set,$where){
        return $this->db->update($table,$set,$where);
    }
    
    // call this function if yout want to delete data from a table
    public function DeleteData($table,$where){
        return $this->db->delete($table,$where);
    }

    // call this function if yout want to get multiple data from a table
    public function GetData($table,$coloum,$where,$joins=array()){
		
        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }

        $q = $this->db->get();
        if($q->num_rows()){
          return $q->result_array();
        }
        return false;
    }

    // call this function if yout want to get only single row data
    public function RowData($table,$coloum='*',$where='',$joins=array()){
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
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

    // call this function if yout want to get only number of rows in a table
    public function NumRows($table,$where){
        $this->db->select("id");
        $this->db->from($table);
        $this->db->where($where);
        $q = $this->db->get();
        if($q->num_rows()){
          return $q->num_rows();
        }
        return false;
    }

    // call  this function if you want to pagination data  
    public function Pagination($table, $coloum='*',$where,$page_no='1',$order_by='id',$order='DESC',$joins=array(),$perpage = 20)
    {
        
        $offset    = (intval($page_no)*intval($perpage))-intval($perpage);
        if($offset<=0){
            $offset = 0;
        }
        $this->db->select($coloum);
        $this->db->from($table);
        $this->db->where($where);
        if($order_by !=''){
            $this->db->order_by($order_by,$order);
        }
        if(count($joins)>0){
            foreach($joins as $join){
                $join['join'] = (array_key_exists('join', $join))?$join['join']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['join']);
            }
        }

        $this->db->limit($perpage,$offset);
        $query  = $this->db->get();         
        $result =  $query->result_array();
        //echo $this->db->last_query();die;
        $query     = explode('LIMIT', $this->db->last_query())[0];
        $pagination = $this->GenratePagination($query,$page_no,$perpage,$offset);

        $data['record'] = $result;
        // $data['pagination'] = $pagination;
        return array_merge($data,$pagination);
    }
    
    // genrate pagination using this function it's accosiated with Pagination() function 
    public function GenratePagination($query=0,$page_no=1,$perpage=20,$offset=0)
    {
        $pagination = array();
      $q = $this->db->query($query);  // fetch all row count 
        $num_row =  $q->num_rows();
        $pageCount = $num_row/$perpage; // get page no

        if(strpos($pageCount,'.')) // check if decimal 
        {
          $pageCount = floor($pageCount)+1;
        }

        // start code on;y for showing 4 page link at a time
       if($pageCount>0) 
       {
         $pagination = range(1, $pageCount);
         
         if($page_no>($pageCount-4))
         {
          $limit = (($pageCount-4)<0)?0:(($pageCount-4));
          $pagination = array_slice($pagination, $limit);
         }
         else
         {
          $pagination = array_slice($pagination, ($page_no-1),4);
         }
       }

       $result =  array('pagination'=>$pagination,'pageCount'=>$pageCount,'offset'=>$offset);
       return $result;
       
    }

    function GetLimitedData($table, $coloum='*',$where,$limit=5,$joins=array(),$order_by='id',$order='DESC')
    {
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        ($order_by)?$this->db->order_by($order_by,$order):"";
        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }
        $this->db->limit($limit);
        $query  = $this->db->get();
        if($query->num_rows()>0)
        {
          return $query->result_array();
        }   
        return false;
    }
    

    // call this function if yout want to apply join and  simple query like getdata()
    public function Join($table, $coloum='*',$where,$joins=array(),$order_by='id',$order='DESC')
    {
        $this->db->select($coloum);
        $this->db->from($table);
        ($where)?$this->db->where($where):"";
        ($order_by)?$this->db->order_by($order_by,$order):"";

        if(count($joins)>0){
            foreach($joins as $join){
                $join['type'] = (array_key_exists('type', $join))?$join['type']:'inner';
                $this->db->join($join['table'],$join['condition'],$join['type']);
            }
        }

        $query  = $this->db->get();
        if($query->num_rows()>0)
        {
          return $query->result_array();
        }   
        return false;
    }

}
?>